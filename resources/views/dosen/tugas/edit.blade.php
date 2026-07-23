@extends('layouts.dosen')

@section('title','Edit Tugas')

@section('content')

<h2 class="text-3xl font-bold mb-8">
    Edit Tugas Praktikum
</h2>

<form action="{{ route('dosen.tugas.update',$assignment->id) }}"
      method="POST"
      enctype="multipart/form-data"
      class="bg-white rounded-xl shadow p-8">

    @csrf
    @method('PUT')

    <div class="grid grid-cols-2 gap-6">

        <div>
            <label class="font-semibold">Judul Tugas</label>
            <input
                type="text"
                name="title"
                value="{{ old('title',$assignment->title) }}"
                class="w-full border rounded-lg p-3 mt-2">
        </div>

        <div>
            <label class="font-semibold">Kelas</label>

            <select
                name="classroom_id"
                id="classroom"
                class="w-full border rounded-lg p-3 mt-2">

                @foreach($classrooms as $classroom)

                    <option
                        value="{{ $classroom->id }}"
                        {{ $assignment->classroom_id==$classroom->id?'selected':'' }}>

                        {{ $classroom->name }}

                    </option>

                @endforeach

            </select>

        </div>

        <div class="col-span-2">

            <label class="font-semibold">
                Deskripsi
            </label>

            <textarea
                name="description"
                rows="5"
                class="w-full border rounded-lg p-3 mt-2">{{ old('description',$assignment->description) }}</textarea>

        </div>

        <div>

            <label class="font-semibold">
                Target
            </label>

            <select
                name="target"
                id="target"
                class="w-full border rounded-lg p-3 mt-2">

                <option
                    value="individual"
                    {{ $assignment->target=='individual'?'selected':'' }}>

                    Individu

                </option>

                <option
                    value="group"
                    {{ $assignment->target=='group'?'selected':'' }}>

                    Kelompok

                </option>

            </select>

        </div>

        <div>

            <label class="font-semibold">
                Deadline
            </label>

            <input
                type="datetime-local"
                name="deadline"
                value="{{ $assignment->deadline->format('Y-m-d\TH:i') }}"
                class="w-full border rounded-lg p-3 mt-2">

        </div>

        <div class="col-span-2">

            <label class="font-semibold">
                Lampiran Baru
            </label>

            <input
                type="file"
                name="attachment"
                class="w-full border rounded-lg p-3 mt-2">

        </div>

        <div id="group-box" class="col-span-2 hidden">

            <label class="font-semibold">

                Pilih Kelompok

            </label>

            <div
                id="groups"
                class="grid grid-cols-3 gap-3 mt-3">

            </div>

        </div>

        <hr class="col-span-2">

        <div>

            <label class="font-semibold">

                Topologi

            </label>

            <div class="space-y-2 mt-3">

                @foreach(['Point to Point','Star','Tree','Mesh'] as $topology)

                <label>

                    <input
                        type="checkbox"
                        name="topologies[]"
                        value="{{ $topology }}"
                        {{ in_array($topology,$assignment->topologies ?? [])?'checked':'' }}>

                    {{ $topology }}

                </label><br>

                @endforeach

            </div>

        </div>

        <div>

            <label class="font-semibold">

                Skema

            </label>

            <div class="space-y-2 mt-3">

                @foreach(['LOS','NLOS'] as $scenario)

                <label>

                    <input
                        type="checkbox"
                        name="scenarios[]"
                        value="{{ $scenario }}"
                        {{ in_array($scenario,$assignment->scenarios ?? [])?'checked':'' }}>

                    {{ $scenario }}

                </label><br>

                @endforeach

            </div>

        </div>

        <div>

            <label class="font-semibold">

                Jarak

            </label>

            <div class="space-y-2 mt-3">

                @foreach([1,5,10,15,20] as $distance)

                <label>

                    <input
                        type="checkbox"
                        name="distances[]"
                        value="{{ $distance }}"
                        {{ in_array((string)$distance,$assignment->distances ?? [])?'checked':'' }}>

                    {{ $distance }} Meter

                </label><br>

                @endforeach

            </div>

        </div>

        <div>

            <label class="font-semibold">

                Node

            </label>

            <div class="space-y-2 mt-3">

                @for($i=1;$i<=3;$i++)

                <label>

                    <input
                        type="checkbox"
                        name="nodes[]"
                        value="{{ $i }}">

                    Node {{ $i }}

                </label><br>

                @endfor

            </div>

        </div>

    </div>

    <button
        class="mt-8 bg-yellow-500 hover:bg-yellow-600 text-white px-8 py-3 rounded-lg">

        Update Tugas

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

    fetch('/dosen/classrooms/'+classroom.value+'/groups')

    .then(res=>res.json())

    .then(data=>{

        groups.innerHTML='';

        data.forEach(group=>{

            groups.innerHTML+=`

            <label class="border rounded-lg p-3">

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