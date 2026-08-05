<?php

namespace App\Services;

use App\Models\Masuk729;
use App\Models\Pengeluaran_742;
use App\Models\StokBarang_719;
use Carbon\Carbon;

/**
 * Smart Stock Reorder System (SSRS)
 * Implementasi Fuzzy Logic Sugeno untuk rekomendasi jumlah pembelian (reorder) stok.
 *
 * Mengikuti dokumen "Rancangan Fitur Kecerdasan Buatan (AI) - Sistem Pendukung
 * Keputusan untuk Manajemen Stok Barang Berbasis Fuzzy Logic Sugeno".
 *
 * Alur (sesuai Gambar 1 di PDF):
 *  1) Input Data Pengguna & Sistem  -> collectMetrics()
 *  2) Fuzzifikasi                   -> fuzzifyPenjualan(), fuzzifyStok(), fuzzifyTrend(), fuzzifyPasokan()
 *  3) Mesin Inferensi (Rule Base)   -> RULES + evaluateRules()
 *  4) Defuzzifikasi (Weighted Avg)  -> defuzzify()
 *  5) Output ke Pengguna            -> recommend() (dipakai controller/view)
 */
class StockRecommendationService
{
    /**
     * 16 Rule Base (Metode Sugeno) — persis Tabel "B. Rule Base" di PDF.
     * z dihitung memakai closure agar rumus z = f(penjualan, stok, stokMin) bisa dieksekusi.
     */
    protected const RULES = [
        ['penjualan' => 'RENDAH', 'stok' => 'RENDAH',   'trend' => 'TURUN',  'pasokan' => 'LAMBAT', 'formula' => 'r1'],
        ['penjualan' => 'RENDAH', 'stok' => 'RENDAH',   'trend' => 'TURUN',  'pasokan' => 'NORMAL', 'formula' => 'r2'],
        ['penjualan' => 'RENDAH', 'stok' => 'RENDAH',   'trend' => 'NAIK',   'pasokan' => 'LAMBAT', 'formula' => 'r3'],
        ['penjualan' => 'RENDAH', 'stok' => 'RENDAH',   'trend' => 'NAIK',   'pasokan' => 'NORMAL', 'formula' => 'r4'],
        ['penjualan' => 'RENDAH', 'stok' => 'AMAN',     'trend' => 'TURUN',  'pasokan' => 'CEPAT',  'formula' => 'r5'],
        ['penjualan' => 'RENDAH', 'stok' => 'AMAN',     'trend' => 'STABIL', 'pasokan' => 'NORMAL', 'formula' => 'r6'],
        ['penjualan' => 'SEDANG', 'stok' => 'RENDAH',   'trend' => 'NAIK',   'pasokan' => 'LAMBAT', 'formula' => 'r7'],
        ['penjualan' => 'SEDANG', 'stok' => 'RENDAH',   'trend' => 'NAIK',   'pasokan' => 'NORMAL', 'formula' => 'r8'],
        ['penjualan' => 'SEDANG', 'stok' => 'AMAN',     'trend' => 'STABIL', 'pasokan' => 'NORMAL', 'formula' => 'r9'],
        ['penjualan' => 'SEDANG', 'stok' => 'BERLEBIH', 'trend' => 'TURUN',  'pasokan' => 'CEPAT',  'formula' => 'r10'],
        ['penjualan' => 'TINGGI', 'stok' => 'RENDAH',   'trend' => 'NAIK',   'pasokan' => 'LAMBAT', 'formula' => 'r11'],
        ['penjualan' => 'TINGGI', 'stok' => 'RENDAH',   'trend' => 'NAIK',   'pasokan' => 'NORMAL', 'formula' => 'r12'],
        ['penjualan' => 'TINGGI', 'stok' => 'AMAN',     'trend' => 'NAIK',   'pasokan' => 'NORMAL', 'formula' => 'r13'],
        ['penjualan' => 'TINGGI', 'stok' => 'AMAN',     'trend' => 'STABIL', 'pasokan' => 'CEPAT',  'formula' => 'r14'],
        ['penjualan' => 'TINGGI', 'stok' => 'BERLEBIH', 'trend' => 'TURUN',  'pasokan' => 'LAMBAT', 'formula' => 'r15'],
        ['penjualan' => 'TINGGI', 'stok' => 'BERLEBIH', 'trend' => 'STABIL', 'pasokan' => 'NORMAL', 'formula' => 'r16'],
    ];

    /**
     * Entry point utama: hitung rekomendasi reorder untuk 1 barang.
     */
    public function recommend(StokBarang_719 $barang): array
    {
        $metrics = $this->collectMetrics($barang);

        $mfPenjualan = $this->fuzzifyPenjualan($metrics['penjualan_bulan_ini'], $metrics['rata2_penjualan']);
        $mfStok      = $this->fuzzifyStok($metrics['stok'], $metrics['stok_min']);
        $mfTrend     = $this->fuzzifyTrend($metrics['trend_persen']);
        $mfPasokan   = $this->fuzzifyPasokan($metrics['rata2_pasokan'], $metrics['rata2_penjualan']);

        $firedRules = $this->evaluateRules($mfPenjualan, $mfStok, $mfTrend, $mfPasokan, $metrics);

        $z = $this->defuzzify($firedRules, $metrics);

        return [
            'barang'   => $barang,
            'metrics'  => $metrics,
            'fuzzy'    => [
                'penjualan' => $mfPenjualan,
                'stok'      => $mfStok,
                'trend'     => $mfTrend,
                'pasokan'   => $mfPasokan,
            ],
            'label'    => [
                'penjualan' => $this->dominantLabel($mfPenjualan),
                'stok'      => $this->dominantLabel($mfStok),
                'trend'     => $this->dominantLabel($mfTrend),
                'pasokan'   => $this->dominantLabel($mfPasokan),
            ],
            'rules_fired' => $firedRules,
            'rekomendasi' => $z,
        ];
    }

    /**
     * Jalankan recommend() untuk semua barang di stok_barang_719.
     */
    public function recommendAll()
    {
        return StokBarang_719::all()->map(fn ($barang) => $this->recommend($barang));
    }

    /* =====================================================================
     * 1) INPUT DATA PENGGUNA & SISTEM
     * ===================================================================== */

    /**
     * Ambil metrik mentah dari 3 sumber data (sesuai tabel "Variabel Input" PDF):
     *  - Total Penjualan  -> catatan_keluar_742 (items_742)
     *  - Trend Penjualan  -> perbandingan bulan ini vs bulan lalu
     *  - Stok Tersisa     -> stok_barang_719
     *  - Pasokan          -> masuk_729 (items)
     */
    protected function collectMetrics(StokBarang_719 $barang): array
    {
        $bulanIni  = Carbon::now()->startOfMonth();
        $bulanLalu = Carbon::now()->subMonthNoOverflow()->startOfMonth();

        $penjualanBulanIni  = $this->sumJumlahBarang(
            Pengeluaran_742::whereDate('tanggal_742', '>=', $bulanIni),
            'items_742',
            $barang->id
        );

        $penjualanBulanLalu = $this->sumJumlahBarang(
            Pengeluaran_742::whereDate('tanggal_742', '>=', $bulanLalu)
                ->whereDate('tanggal_742', '<', $bulanIni),
            'items_742',
            $barang->id
        );

        // rata-rata penjualan per bulan, dari 3 bulan terakhir (dipakai sebagai acuan relatif RENDAH/SEDANG/TINGGI)
        $rata2Penjualan = $this->rataRataPerBulan(Pengeluaran_742::class, 'tanggal_742', 'items_742', $barang->id, 3);

        // rata-rata barang masuk (pasokan) per bulan, dari 3 bulan terakhir
        $rata2Pasokan = $this->rataRataPerBulan(Masuk729::class, 'tanggal', 'items', $barang->id, 3);

        $trendPersen = $penjualanBulanLalu > 0
            ? (($penjualanBulanIni - $penjualanBulanLalu) / $penjualanBulanLalu) * 100
            : ($penjualanBulanIni > 0 ? 100 : 0);

        return [
            'penjualan_bulan_ini'  => $penjualanBulanIni,
            'penjualan_bulan_lalu' => $penjualanBulanLalu,
            'rata2_penjualan'      => max($rata2Penjualan, 1), // hindari pembagian 0
            'rata2_pasokan'        => $rata2Pasokan,
            'trend_persen'         => round($trendPersen, 1),
            'stok'                 => (int) $barang->{'719_stok_tercatat'},
            'stok_min'             => max((int) $barang->{'719_stok_min'}, 1),
        ];
    }

    /**
     * Jumlahkan kolom 'jumlah' pada JSON items untuk barang_id tertentu, dari sebuah query builder.
     */
    protected function sumJumlahBarang($query, string $itemsColumn, int $barangId): int
    {
        $total = 0;
        foreach ($query->get() as $row) {
            $items = is_array($row->{$itemsColumn}) ? $row->{$itemsColumn} : json_decode($row->{$itemsColumn} ?? '[]', true);
            foreach ((array) $items as $item) {
                if ((string) ($item['barang_id'] ?? '') === (string) $barangId) {
                    $total += (int) ($item['jumlah'] ?? 0);
                }
            }
        }
        return $total;
    }

    /**
     * Rata-rata jumlah per bulan untuk N bulan terakhir (termasuk bulan berjalan).
     */
    protected function rataRataPerBulan(string $modelClass, string $dateColumn, string $itemsColumn, int $barangId, int $months): float
    {
        $start = Carbon::now()->subMonthsNoOverflow($months - 1)->startOfMonth();

        $total = $this->sumJumlahBarang(
            $modelClass::whereDate($dateColumn, '>=', $start),
            $itemsColumn,
            $barangId
        );

        return $total / $months;
    }

    /* =====================================================================
     * 2) FUZZIFIKASI
     * Fungsi keanggotaan segitiga/trapesium. Karena tiap barang punya skala
     * penjualan berbeda-beda, ambang batas RENDAH/SEDANG/TINGGI & LAMBAT/
     * NORMAL/CEPAT dibuat *relatif* terhadap rata-rata historis barang itu
     * sendiri, sedangkan ambang stok relatif terhadap 719_stok_min barang.
     * ===================================================================== */

    protected function fuzzifyPenjualan(float $nilai, float $rata2): array
    {
        $r = $rata2 > 0 ? $nilai / $rata2 : 0; // rasio terhadap rata-rata
        return [
            'RENDAH' => $this->trapesium($r, -INF, -INF, 0.5, 1.0),
            'SEDANG' => $this->segitiga($r, 0.5, 1.0, 1.5),
            'TINGGI' => $this->trapesium($r, 1.0, 1.5, INF, INF),
        ];
    }

    protected function fuzzifyTrend(float $persen): array
    {
        return [
            'TURUN'  => $this->trapesium($persen, -INF, -INF, -10, 0),
            'STABIL' => $this->segitiga($persen, -10, 0, 10),
            'NAIK'   => $this->trapesium($persen, 0, 10, INF, INF),
        ];
    }

    protected function fuzzifyStok(int $stok, int $stokMin): array
    {
        $r = $stok / $stokMin; // rasio terhadap stok minimum
        return [
            'RENDAH'   => $this->trapesium($r, -INF, -INF, 1.0, 2.0),
            'AMAN'     => $this->segitiga($r, 1.0, 2.0, 4.0),
            'BERLEBIH' => $this->trapesium($r, 2.0, 4.0, INF, INF),
        ];
    }

    protected function fuzzifyPasokan(float $rata2Pasokan, float $rata2Penjualan): array
    {
        // seberapa besar pasokan menutupi permintaan
        $r = $rata2Penjualan > 0 ? $rata2Pasokan / $rata2Penjualan : ($rata2Pasokan > 0 ? 2 : 0);
        return [
            'LAMBAT' => $this->trapesium($r, -INF, -INF, 0.5, 1.0),
            'NORMAL' => $this->segitiga($r, 0.5, 1.0, 1.5),
            'CEPAT'  => $this->trapesium($r, 1.0, 1.5, INF, INF),
        ];
    }

    /** Fungsi keanggotaan segitiga (a,b,c) */
    protected function segitiga(float $x, float $a, float $b, float $c): float
    {
        if ($x <= $a || $x >= $c) return 0.0;
        if ($x == $b) return 1.0;
        if ($x < $b) return ($x - $a) / ($b - $a);
        return ($c - $x) / ($c - $b);
    }

    /** Fungsi keanggotaan trapesium (a,b,c,d); pakai INF untuk sisi terbuka */
    protected function trapesium(float $x, float $a, float $b, float $c, float $d): float
    {
        if ($x < $a || $x > $d) return 0.0;
        if ($x >= $b && $x <= $c) return 1.0;
        if ($x < $b) return $b == $a ? 1.0 : ($x - $a) / ($b - $a);
        return $d == $c ? 1.0 : ($d - $x) / ($d - $c);
    }

    protected function dominantLabel(array $membership): string
    {
        arsort($membership);
        return array_key_first($membership);
    }

    /* =====================================================================
     * 3) MESIN INFERENSI (RULE BASE) — 16 aturan IF-THEN metode Sugeno
     * Predikat (alpha) tiap rule = MIN dari 4 derajat keanggotaan (AND fuzzy)
     * ===================================================================== */

    protected function evaluateRules(array $mfPenjualan, array $mfStok, array $mfTrend, array $mfPasokan, array $metrics): array
    {
        $fired = [];

        foreach (self::RULES as $i => $rule) {
            $alpha = min(
                $mfPenjualan[$rule['penjualan']] ?? 0,
                $mfStok[$rule['stok']] ?? 0,
                $mfTrend[$rule['trend']] ?? 0,
                $mfPasokan[$rule['pasokan']] ?? 0
            );

            if ($alpha > 0) {
                $fired[] = [
                    'no'    => $i + 1,
                    'rule'  => $rule,
                    'alpha' => $alpha,
                    'z'     => $this->hitungZ($rule['formula'], $metrics),
                ];
            }
        }

        return $fired;
    }

    /**
     * Rumus output z untuk masing-masing rule, persis kolom "Rekomendasi (Output z)" di PDF.
     * Penjualan = penjualan_bulan_ini, Stok = stok_tercatat, Stok Min = stok_min.
     */
    protected function hitungZ(string $formula, array $m): float
    {
        $penjualan = $m['penjualan_bulan_ini'];
        $stok      = $m['stok'];
        $stokMin   = $m['stok_min'];

        return match ($formula) {
            'r1'  => ($penjualan * 0.5) + ($stokMin * 0.5),
            'r2'  => ($penjualan * 0.6) + ($stokMin * 0.4),
            'r3'  => ($penjualan * 1.2) - $stok,
            'r4'  => ($penjualan * 1.1) - ($stok * 0.5),
            'r5'  => 0,
            'r6'  => $stok * 0.1,
            'r7'  => ($penjualan * 1.5) - $stok,
            'r8'  => ($penjualan * 1.3) - $stok,
            'r9'  => ($penjualan * 0.8) - $stok,
            'r10' => 0,
            'r11' => ($penjualan * 2)   - $stok,
            'r12' => ($penjualan * 1.8) - $stok,
            'r13' => ($penjualan * 1.2) - $stok,
            'r14' => ($penjualan * 0.9) - $stok,
            'r15' => $stok * 0.1,
            'r16' => 0,
            default => 0,
        };
    }

    /* =====================================================================
     * 4) DEFUZZIFIKASI (Weighted Average)
     * ===================================================================== */

    protected function defuzzify(array $firedRules, array $metrics): int
    {
        if (empty($firedRules)) {
            // Fallback jika tak ada rule yang terpicu sama sekali (data ekstrem/di luar 16 skenario):
            // rekomendasi = kekurangan terhadap kebutuhan minimum, tidak boleh negatif.
            $fallback = max(0, $metrics['penjualan_bulan_ini'] - $metrics['stok'] + $metrics['stok_min']);
            return (int) round($fallback);
        }

        $sumAlphaZ = 0;
        $sumAlpha  = 0;
        foreach ($firedRules as $f) {
            $sumAlphaZ += $f['alpha'] * $f['z'];
            $sumAlpha  += $f['alpha'];
        }

        $z = $sumAlpha > 0 ? $sumAlphaZ / $sumAlpha : 0;

        return (int) max(0, round($z)); // jumlah beli tidak mungkin negatif
    }
}
