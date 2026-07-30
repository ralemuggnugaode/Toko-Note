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
                <strong>Gagal menyimpan data!</strong>
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
                    <h6>Daftar Karyawan</h6>
                    <button class="btn bg-gradient-primary btn-sm mb-0" data-bs-toggle="modal" data-bs-target="#modalTambahKaryawan">
                        <i class="fa-solid fa-plus"></i> Tambah Karyawan
                    </button>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive-md p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Nama/Username</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-none d-md-table-cell">
                                        ID Number</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-none d-md-table-cell">
                                        Role</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $user->username }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-sm d-none d-md-table-cell">
                                            {{ $user->identification_number ?? '-' }}
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <span class="badge badge-sm bg-gradient-secondary">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-link text-secondary px-2 mb-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('page.karyawan.edit', $user->id) }}">
                                                            <i class="fa-regular fa-pen-to-square me-2"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('page.karyawan.destroy', $user->id) }}" method="POST"
                                                            onsubmit="return confirm('Yakin ingin menghapus karyawan {{ $user->name }}?')">
                                                            @method('delete')
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fa-regular fa-trash-can me-2"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Belum ada karyawan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTambahKaryawan" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Karyawan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('page.karyawan.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" placeholder="Masukkan nama lengkap" maxlength="255" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                                value="{{ old('username') }}" placeholder="Masukkan username" maxlength="255" required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                placeholder="Minimal 3 karakter" required>
                            <small class="form-text text-muted">Minimal 3 karakter.</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="Ulangi password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn bg-gradient-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                var tambahModal = new bootstrap.Modal(document.getElementById('modalTambahKaryawan'));
                tambahModal.show();
            });
        </script>
    @endif
</div>
@endsection
