@extends('layouts.admin')

@section('title', 'Tambah Kelompok')

@section('content')

<div class="p-6">

    <h2 class="text-2xl font-bold mb-6">
        Tambah Kelompok
    </h2>

    <form action="{{ route('groups.store') }}" method="POST">

        @csrf

        <div class="mb-4">
            <label class="block mb-2">Nama Kelompok</label>

            <input
                type="text"
                name="nama_kelompok"
                class="w-full border rounded p-2"
                required>
        </div>

        <div class="mb-4">

    <label>Angkatan</label>

    <select
name="angkatan"
id="angkatan"
        class="w-full border rounded p-2">

        <option value="">Pilih Angkatan</option>

        <option>2023</option>
        <option>2024</option>
        <option>2025</option>
        <option>2026</option>
        <option>2027</option>

    </select>

</div>

        <div class="mb-4">

<label>Kelas</label>

<select
id="kelas"
name="kelas"
class="w-full border rounded p-2">

<option value="">Pilih Kelas</option>

<option value="BM A">BM A</option>

<option value="BM B">BM B</option>

</select>

</div>

<div class="mb-4">

<label class="font-semibold">

Daftar Mahasiswa

</label>

<div
id="member-list"
class="border rounded p-3 max-h-64 overflow-y-auto">

@foreach($mahasiswas as $mhs)

<div
class="member-item"
data-kelas="{{ $mhs->kelas }}"
data-angkatan="{{ $mhs->angkatan }}">

<label>

<input
type="checkbox"
name="members[]"
value="{{ $mhs->id }}">

{{ $mhs->name }}

</label>

</div>

@endforeach

</div>

</div>

        <div class="mb-4">

            <label class="block mb-2">Dosen</label>

            <select
                name="dosen_id"
                class="w-full border rounded p-2"
                required>

                <option value="">Pilih Dosen</option>

                @foreach($dosens as $dosen)

                    <option value="{{ $dosen->id }}">
                        {{ $dosen->name }}
                    </option>

                @endforeach

            </select>

        </div>

        <button
            type="submit"
            class="bg-blue-600 text-white px-5 py-2 rounded">

            Simpan

        </button>

    </form>

</div>

<script>

const angkatan = document.getElementById('angkatan');
const kelas = document.getElementById('kelas');

function filterMahasiswa(){

    document.querySelectorAll('.member-item').forEach(item=>{

        const cocokAngkatan =
            item.dataset.angkatan===angkatan.value;

        const cocokKelas =
            item.dataset.kelas===kelas.value;

        item.style.display=
            (cocokAngkatan && cocokKelas)
            ?'block':'none';

    });

}

angkatan.addEventListener('change',filterMahasiswa);

kelas.addEventListener('change',filterMahasiswa);

</script>
@endsection