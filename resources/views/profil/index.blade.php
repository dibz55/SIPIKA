@extends('layouts.app')

@section('content')

<div class="profil-page">

    
<div class="profil-header">
    <h1>Profil Administrator</h1>
</div>

<div class="profil-card">

    {{-- FOTO PROFIL --}}
    <div class="profil-photo-section">

        <div class="profil-photo-wrapper" id="profilPhotoPreview">

            @if(auth()->user()->foto_profil)
                <img
                    src="{{ asset('storage/' . auth()->user()->foto_profil) }}"
                    alt="Foto Profil"
                    class="profil-photo"
                >
            @else
                <div class="profil-photo-placeholder">
                    <i class="bi bi-person-fill"></i>
                </div>
            @endif

        </div>

        <label for="foto_profil" class="btn-ubah-profil">
            Ubah Profil
        </label>

    </div>


    {{-- FORM PROFIL --}}
    <div class="profil-form-section">

        <form
            action="{{ route('profil.update') }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf
            @method('PUT')

            <input
                type="file"
                id="foto_profil"
                name="foto_profil"
                accept="image/*"
                hidden
            >


            {{-- NAMA --}}
            <div class="profil-form-group">

                <label for="nama">Nama</label>

                <input
                    type="text"
                    id="nama"
                    name="name"
                    value="{{ old('name', auth()->user()->name) }}"
                    class="profil-input"
                    required
                >

            </div>


            {{-- EMAIL --}}
            <div class="profil-form-group">

                <label for="email">Email</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', auth()->user()->email) }}"
                    class="profil-input"
                    required
                >

            </div>


            {{-- GANTI AKUN --}}
            <div class="profil-account-wrapper">

                <button
                    type="button"
                    class="btn-ganti-akun"
                >
                    Ganti akun
                </button>

            </div>


            {{-- SIMPAN --}}
            <div class="profil-save-wrapper">

                <button
                    type="submit"
                    class="btn-simpan-profil"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>


{{-- SUCCESS --}}
@if(session('success'))

    <div class="profil-alert profil-alert-success">
        {{ session('success') }}
    </div>

@endif


{{-- ERROR --}}
@if($errors->any())

    <div class="profil-alert profil-alert-error">

        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach

    </div>

@endif


</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('foto_profil');
    const preview = document.getElementById('profilPhotoPreview');

    if (!input || !preview) {
        return;
    }

    input.addEventListener('change', function () {

        const file = this.files[0];

        if (!file) {
            return;
        }

        const reader = new FileReader();

        reader.onload = function (event) {

            preview.innerHTML = `
                <img
                    src="${event.target.result}"
                    alt="Preview Foto Profil"
                    class="profil-photo"
                >
            `;

        };

        reader.readAsDataURL(file);

    });

});
</script>

@endsection
