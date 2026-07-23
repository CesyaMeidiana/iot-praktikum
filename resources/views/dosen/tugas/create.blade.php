@extends('layouts.dosen')

@section('title','Buat Tugas')

@section('content')

<h2 class="text-3xl font-bold mb-8">
    Buat Tugas Praktikum
</h2>

<form action="{{ route('dosen.tugas.store') }}"
      method="POST"
      enctype="multipart/form-data"
      class="bg-white rounded-xl shadow p-8">

    @csrf

    <div class="grid grid-cols-2 gap-6">

        {{-- Judul --}}
        <div>
            <label class="font-semibold">Judul Tugas</label>

            <input
                type="text"
                name="title"
                class="w-full border rounded-lg p-3 mt-2">
        </div>

        {{-- Kelas --}}
        <div>
            <label class="font-semibold">Kelas</label>

            <select
                name="classroom_id"
                id="classroom"
                class="w-full border rounded-lg p-3 mt-2">

                <option value="">Pilih Kelas</option>

                @foreach($classrooms as $classroom)

                    <option value="{{ $classroom->id }}">
                        {{ $classroom->name }}
                    </option>

                @endforeach

            </select>
        </div>

        {{-- Deskripsi --}}
        <div class="col-span-2">

            <label class="font-semibold">
                Deskripsi
            </label>

            <textarea
                name="description"
                rows="5"
                class="w-full border rounded-lg p-3 mt-2"></textarea>

        </div>

        {{-- Target --}}
        <div>

            <label class="font-semibold">

                Target Tugas

            </label>

            <select
                name="target"
                id="target"
                class="w-full border rounded-lg p-3 mt-2">

                <option value="individual">

                    Individu

                </option>

                <option value="group">

                    Kelompok

                </option>

            </select>

        </div>

        {{-- Deadline --}}
        <div>

            <label class="font-semibold">

                Deadline

            </label>

            <input
                type="datetime-local"
                name="deadline"
                class="w-full border rounded-lg p-3 mt-2">

        </div>

        {{-- Lampiran --}}
        <div class="col-span-2">

            <label class="font-semibold">

                Lampiran

            </label>

            <input
                type="file"
                name="attachment"
                class="w-full border rounded-lg p-3 mt-2">

        </div>

        {{-- Kelompok --}}
        <div
            id="group-box"
            class="col-span-2 hidden">

            <label class="font-semibold">

                Pilih Kelompok

            </label>

            <div
                id="groups"
                class="grid grid-cols-3 gap-3 mt-3">

            </div>

        </div>

        <hr class="col-span-2">

        {{-- Topologi --}}
        <div>

            <label class="font-semibold">

                Topologi

            </label>

            <div class="space-y-2 mt-3">

                <label><input type="checkbox" name="topologies[]" value="Point to Point"> Point to Point</label><br>

                <label><input type="checkbox" name="topologies[]" value="Star"> Star</label><br>

                <label><input type="checkbox" name="topologies[]" value="Tree"> Tree</label><br>

                <label><input type="checkbox" name="topologies[]" value="Mesh"> Mesh</label>

            </div>

        </div>

        {{-- Skema --}}
        <div>

            <label class="font-semibold">

                Skema

            </label>

            <div class="space-y-2 mt-3">

                <label><input type="checkbox" name="scenarios[]" value="LOS"> LOS</label><br>

                <label><input type="checkbox" name="scenarios[]" value="NLOS"> NLOS</label>

            </div>

        </div>

        {{-- Jarak --}}
        <div>

            <label class="font-semibold">

                Jarak

            </label>

            <div class="space-y-2 mt-3">

                <label><input type="checkbox" name="distances[]" value="1"> 1 Meter</label><br>

                <label><input type="checkbox" name="distances[]" value="5"> 5 Meter</label><br>

                <label><input type="checkbox" name="distances[]" value="10"> 10 Meter</label><br>

                <label><input type="checkbox" name="distances[]" value="15"> 15 Meter</label><br>

                <label><input type="checkbox" name="distances[]" value="20"> 20 Meter</label>

            </div>

        </div>

        {{-- Node --}}
        <div>
    <label class="font-semibold">
        Pilih Node
    </label>

    <div class="space-y-4 mt-3">

        @foreach($devices as $device)

        <div class="border rounded-lg p-4">

            <label class="flex items-center gap-2 font-bold">

                <input
                    type="checkbox"
                    name="nodes[]"
                    value="{{ $device->id }}">

                {{ $device->node }} -
                {{ $device->nama_device }}

            </label>

            <div class="ml-6 mt-3">

                @foreach($device->sensors as $sensor)

                    <div>

                        🔹 {{ $sensor->nama_sensor }}

                        @if($sensor->parameter)
                            ({{ $sensor->parameter }}
                            {{ $sensor->satuan }})
                        @endif

                    </div>

                    @foreach($sensor->actuators as $actuator)

                        <div class="ml-6 text-gray-600">

                            ↳ {{ $actuator->nama_aktuator }}

                        </div>

                    @endforeach

                @endforeach

            </div>

        </div>

        @endforeach

    </div>

</div>

    </div>

    <button
        class="mt-8 bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg">

        Simpan Tugas

    </button>

</form>

<script>

const target=document.getElementById('target');
const classroom=document.getElementById('classroom');
const groupBox=document.getElementById('group-box');
const groups=document.getElementById('groups');

target.addEventListener('change',toggleGroup);
classroom.addEventListener('change',loadGroups);

function toggleGroup(){

    if(target.value==="group"){

        groupBox.classList.remove('hidden');

        loadGroups();

    }else{

        groupBox.classList.add('hidden');

        groups.innerHTML='';

    }

}

function loadGroups(){

    if(target.value!=="group") return;

    if(classroom.value==="") return;

    fetch('/dosen/classrooms/'+classroom.value+'/groups')

    .then(response=>response.json())

    .then(data=>{

        groups.innerHTML='';

        data.forEach(group=>{

            groups.innerHTML+=`

            <label class="border rounded-lg p-3 cursor-pointer">

                <input
                    type="checkbox"
                    name="groups[]"
                    value="${group.id}">

                ${group.nama_kelompok}

            </label>

            `;

        });

    });

}

toggleGroup();

</script>

@endsection