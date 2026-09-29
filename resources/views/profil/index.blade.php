@extends('layouts.app')

@section('title', 'Profil')

@section('content')

<div class="card-panel">

    <h5 class="mb-4">
        Profil Administrator
    </h5>

    <div class="row">

        {{-- FOTO PROFIL --}}

        <div class="col-md-3 text-center">

            <img
                src="{{ $user->foto_profil
                    ? asset('storage/' . $user->foto_profil)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                class="profile-photo mb-3"
                alt="Foto profil">

        </div>


        {{-- DATA PROFIL --}}

        <div class="col-md-9">

            <form
                method="POST"
                action="{{ route('profil.update') }}"
                enctype="multipart/form-data">

                @csrf
                @method('PUT')


                <div class="mb-3">

                    <label class="form-label">
                        Nama
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ old('name', $user->name) }}"
                        maxlength="100"
                        required>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email', $user->email) }}"
                        maxlength="150"
                        required>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Foto Profil
                    </label>

                    <input
                        type="file"
                        name="foto_profil"
                        class="form-control"
                        accept="image/*">

                    <small class="text-muted">
                        Maksimal ukuran 2 MB.
                    </small>

                </div>


                <button
                    type="submit"
                    class="btn-sipika-green">

                    Simpan Perubahan

                </button>


                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    data-bs-toggle="modal"
                    data-bs-target="#gantiAkunModal">

                    Ganti Password

                </button>

            </form>

        </div>

    </div>

</div>


{{-- MODAL GANTI PASSWORD --}}

<div
    class="modal fade"
    id="gantiAkunModal"
    tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                method="POST"
                action="{{ route('profil.ganti-akun') }}">

                @csrf
                @method('PUT')


                <div class="modal-header">

                    <h6 class="modal-title">
                        Ganti Password
                    </h6>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <label class="form-label">
                        Password Baru
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control mb-3"
                        minlength="6"
                        required>


                    <label class="form-label">
                        Konfirmasi Password
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                        minlength="6"
                        required>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button
                        type="submit"
                        class="btn-sipika-green">

                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
