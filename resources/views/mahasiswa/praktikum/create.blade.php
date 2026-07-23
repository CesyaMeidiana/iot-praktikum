@extends('layouts.mahasiswa')

@section('title', 'Praktikum Baru')

@section('content')

<div class="max-w-6xl mx-auto">

    <h2 class="text-3xl font-bold mb-8">
        Praktikum Baru
    </h2>

    <form method="POST" action="{{ route('mahasiswa.praktikum.store') }}">
        @csrf

        @if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif

        <div class="bg-white rounded-xl shadow p-8">

            {{-- Topologi --}}
            <div class="mb-8">

                <label class="font-semibold text-lg">
                    Topologi
                </label>

                <select
                    id="topology"
                    name="topology"
                    class="w-full border rounded-lg mt-3 p-3">

                    <option value="Point to Point">Point to Point</option>
                    <option value="Star">Star</option>
                    <option value="Tree">Tree</option>
                    <option value="Mesh">Mesh</option>

                </select>

            </div>

           {{-- Node --}}
<div class="mb-8">

    <label class="font-semibold text-lg">
        Pilih Node
    </label>

    <div id="deviceContainer"
        class="grid md:grid-cols-3 gap-6 mt-4">

        @foreach($devices as $device)

        <label class="border rounded-xl p-5 hover:border-blue-500 cursor-pointer">

            <input
                class="node-input mb-4"
                type="radio"
                name="devices[]"
                value="{{ $device->id }}">

            <h3 class="font-bold text-lg">

                {{ $device->node }}

            </h3>

            <p class="text-gray-500 mb-3">

                {{ $device->nama_device }}

            </p>

            <div class="mb-3">

                <h4 class="font-semibold text-blue-600">

                    Sensor

                </h4>

                <ul class="list-disc ml-5 text-sm">

                    @foreach($device->sensors as $sensor)

                        <li>

                            {{ $sensor->nama_sensor }}

                            @if($sensor->parameter)

                                → {{ $sensor->parameter }}

                                {{ $sensor->satuan }}

                            @endif

                        </li>

                    @endforeach

                </ul>

            </div>

            <div>

                <h4 class="font-semibold text-green-600">

                    Aktuator

                </h4>

                <ul class="list-disc ml-5 text-sm">

                    @foreach($device->sensors as $sensor)

                        @foreach($sensor->actuators as $actuator)

                            <<li class="mb-2">
    {{ $actuator->nama_aktuator }}
    <div class="flex gap-2 mt-1">
        <select name="actuator_config[{{ $actuator->id }}][on_operator]" class="border rounded text-xs p-1">
            <option value="">-- ON saat --</option>
            <option value=">">&gt;</option>
            <option value=">=">&gt;=</option>
            <option value="<">&lt;</option>
            <option value="<=">&lt;=</option>
            <option value="=">=</option>
        </select>
        <input type="number" step="any" placeholder="nilai"
               name="actuator_config[{{ $actuator->id }}][on_value]"
               class="border rounded text-xs p-1 w-20">
    </div>
</li>

                        @endforeach

                    @endforeach

                </ul>

            </div>

        </label>

        @endforeach

    </div>

</div>

            {{-- Skema --}}
            <div class="mb-8">

                <label class="font-semibold text-lg">

                    Skema

                </label>

                <div class="mt-4 flex gap-8">

                    <label>

                        <input
                            type="radio"
                            name="scenario"
                            value="LOS">

                        LOS

                    </label>

                    <label>

                        <input
                            type="radio"
                            name="scenario"
                            value="NLOS">

                        NLOS

                    </label>

                </div>

            </div>

            {{-- Jarak --}}
            <div class="mb-8">

                <label class="font-semibold text-lg">

                    Jarak

                </label>

                <select
                    name="distance"
                    class="w-full border rounded-lg mt-3 p-3">

                    <option value="1">1 Meter</option>

                    <option value="5">5 Meter</option>

                    <option value="10">10 Meter</option>

                    <option value="15">15 Meter</option>

                    <option value="20">20 Meter</option>

                </select>

            </div>

            <div class="mb-4">
    <label class="block font-semibold mb-2">Kelas</label>

    <select name="classroom_id" class="w-full border rounded px-3 py-2">
    <option value="">Tanpa Kelas (Praktikum Mandiri)</option>

    @foreach ($classrooms as $classroom)
        <option value="{{ $classroom->id }}">
            {{ $classroom->name }} ({{ $classroom->class_name }} - {{ $classroom->academic_year }})
        </option>
    @endforeach
</select>

<p class="text-sm text-gray-500 mt-1">
    Opsional. Kosongkan jika ini praktikum mandiri di luar kelas.
</p>
</div>

            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg">

                Mulai Praktikum

            </button>

        </div>

    </form>

</div>

<script>

const topology = document.getElementById('topology');

const nodes = document.querySelectorAll('.node-input');

function updateNodeSelection(){

    if(topology.value === 'Point to Point'){

        nodes.forEach(node =>{

            node.type = 'radio';

            node.name='devices'

        });

    }else{

        nodes.forEach(node =>{

            node.type = 'checkbox';

            node.name='devices[]'

        });

    }

}

topology.addEventListener('change', updateNodeSelection);

updateNodeSelection();

</script>

@endsection