@extends('pages.partials.main')
@section('page')
    <div class="container-fluid py-2">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show text-white" role="alert">
                <span class="alert-icon"><i class="fa-solid fa-check"></i></span>
                <span class="alert-text">{{ session('success') }}</span>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show text-white" role="alert">
                <span class="alert-icon"><i class="fa-solid fa-circle-exclamation"></i></span>
                <span class="alert-text">
                    <strong>Gagal menyimpan perubahan!</strong>
                    <ul class="mb-0 mt-1 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </span>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6>Edit Karyawan</h6>
                        <a href="{{ route('page.karyawan.index') }}" class="btn bg-gradient-secondary btn-sm mb-0">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                    <div class="card-body px-4 pt-4 pb-2">
                        <form action="{{ route('page.karyawan.update', $user->id) }}" method="POST" novalidate>
                            @csrf
                            @method('PUT')

                            {{-- Informasi tetap (tidak bisa diubah) --}}
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted text-uppercase text-xs">Role</label>
                                    <input type="text" class="form-control" value="Karyawan" disabled>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted text-uppercase text-xs">ID Number</label>
                                    <input type="text" class="form-control"
                                        value="{{ $user->identification_number ?? '-' }}" disabled>
                                </div>
                            </div>

                            {{-- Nama --}}
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" placeholder="Masukkan nama lengkap"
                                    maxlength="255" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Username --}}
                            <div class="mb-3">
                                <label class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" name="username"
                                    class="form-control @error('username') is-invalid @enderror"
                                    value="{{ old('username', $user->username) }}" placeholder="Masukkan username"
                                    maxlength="255" required>
                                <small class="form-text text-muted">Digunakan untuk login.</small>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Password Baru (opsional) --}}
                            <div class="mb-3">
                                <label class="form-label">Password Baru</label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Kosongkan jika tidak ingin mengubah">
                                <small class="form-text text-muted">Minimal 3 karakter.</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" class="form-control"
                                    placeholder="Ulangi password baru">
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn bg-gradient-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
