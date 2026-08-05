<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 "
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="https://demos.creative-tim.com/soft-ui-dashboard/pages/dashboard.html"
            target="_blank">
            <img src="/assets/img/logo-ct.png" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">TokoNote</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto  max-height-vh-100 h-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ route('page.home') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-chart-pie text-sm opacity-10 {{ Request::is('/') ? 'text-white' : 'text-dark' }}"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('stok-barang-719*') ? 'active' : '' }}" href="{{ route('page.stok-barang-719.index') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-boxes-stacked text-sm opacity-10 {{ Request::is('stok-barang-719*') ? 'text-white' : 'text-dark' }}"></i>
                    </div>
                    <span class="nav-link-text ms-1">Stok Barang</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('catatan-masuk-729*') ? 'active' : '' }}" href="{{ route('page.catatan-masuk-729.index') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-file-import text-sm opacity-10 {{ Request::is('catatan-masuk-729*') ? 'text-white' : 'text-dark' }}"></i>
                    </div>
                    <span class="nav-link-text ms-1">Catatan Masuk</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('catatan-keluar-742*') ? 'active' : '' }}" href="{{ route('page.catatan-keluar-742.index') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-file-export text-sm opacity-10 {{ Request::is('catatan-keluar-742*') ? 'text-white' : 'text-dark' }}"></i>
                    </div>
                    <span class="nav-link-text ms-1">Catatan Keluar</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('rekomendasi-stok*') ? 'active' : '' }}" href="{{ route('page.rekomendasi.index') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-brain text-sm opacity-10 {{ Request::is('rekomendasi-stok*') ? 'text-white' : 'text-dark' }}"></i>
                    </div>
                    <span class="nav-link-text ms-1">Rekomendasi AI</span>
                </a>
            </li>
            @if (auth()->user()->role === 'admin')
            <li class="nav-item">
                <a class="nav-link {{ Request::is('karyawan*') ? 'active' : '' }}" href="{{ route('page.karyawan.index') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-user-plus text-sm opacity-10 {{ Request::is('karyawan*') ? 'text-white' : 'text-dark' }}"></i>
                    </div>
                    <span class="nav-link-text ms-1">Tambah Karyawan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('laporan*') ? 'active' : '' }}" href="{{ route('page.report.index') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-file-lines text-sm opacity-10 {{ Request::is('laporan*') ? 'text-white' : 'text-dark' }}"></i>
                    </div>
                    <span class="nav-link-text ms-1">Laporan</span>
                </a>
            </li>
            @endif
        </ul>
    </div>
</aside>
