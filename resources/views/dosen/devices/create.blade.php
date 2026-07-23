@extends('layouts.dosen')

@section('title','Master Device')
@section('page-title','Tambah Master Device')

@section('content')
<div class="bg-white rounded-lg shadow p-6">

    <h2 class="text-2xl font-bold mb-6">
        Tambah Master Device
    </h2>

    <form action="{{ route('dosen.devices.store') }}" method="POST">
        @csrf

        <div class="mb-5">
            <label class="block font-semibold mb-2">Node</label>
            <select name="node" class="w-full border rounded-lg px-4 py-2" required>
                <option value="">-- Pilih Node --</option>
                @for($i=1;$i<=10;$i++)
                    <option value="{{ $i }}">Node {{ $i }}</option>
                @endfor
            </select>
        </div>

        <div class="mb-5">
            <label class="block font-semibold mb-2">Nama Device</label>
            <input type="text" name="nama_device" class="w-full border rounded-lg px-4 py-2" required>
        </div>

        <div class="mb-5">
            <label class="block font-semibold mb-2">Keterangan</label>
            <textarea name="keterangan" rows="3" class="w-full border rounded-lg px-4 py-2"></textarea>
        </div>

        <div class="mb-6">
            <label class="block font-semibold mb-2">Jumlah Sensor</label>
            <select id="jumlahSensor" class="w-full border rounded-lg px-4 py-2">
                <option value="">-- Pilih Jumlah Sensor --</option>
                @for($i=1;$i<=5;$i++)
                    <option value="{{ $i }}">{{ $i }} Sensor</option>
                @endfor
            </select>
        </div>

        <div id="sensorContainer" class="space-y-5"></div>

        <div class="mt-6 flex gap-3">
            <button class="bg-blue-600 text-white px-6 py-2 rounded-lg">
                Simpan
            </button>

            <a href="{{ route('dosen.devices.index') }}"
               class="bg-gray-500 text-white px-6 py-2 rounded-lg">
                Kembali
            </a>
        </div>
    </form>
</div>

<script>
const jumlahSensor = document.getElementById('jumlahSensor');
const sensorContainer = document.getElementById('sensorContainer');

function buatAktuatorHTML(sensorIndex) {
    return `
    <div class="aktuator-item border border-gray-300 rounded-lg p-4 bg-white mb-3 relative">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="font-semibold block mb-1 text-sm">Nama Aktuator</label>
                <input type="text"
                       name="aktuator_nama[${sensorIndex}][]"
                       class="w-full border rounded-lg px-3 py-2"
                       placeholder="Mini Fan / Lampu / Pompa / Buzzer / Relay">
            </div>

            <div>
                <label class="font-semibold block mb-1 text-sm">Kondisi ON (saat apa)</label>
                <input type="text"
                       name="aktuator_on[${sensorIndex}][]"
                       class="w-full border rounded-lg px-3 py-2"
                       placeholder="Contoh: Suhu > 30°C">
            </div>

            <div>
                <label class="font-semibold block mb-1 text-sm">Kondisi OFF (saat apa)</label>
                <input type="text"
                       name="aktuator_off[${sensorIndex}][]"
                       class="w-full border rounded-lg px-3 py-2"
                       placeholder="Contoh: Suhu <= 30°C">
            </div>
        </div>
    </div>`;
}

function tambahAktuator(sensorIndex){

    const container = document.getElementById(
        'aktuatorContainer_' + sensorIndex
    );

    if(!container) return;

    container.insertAdjacentHTML(
        'beforeend',
        buatAktuatorHTML(sensorIndex)
    );

}

function hapusAktuator(sensorIndex){

    const container=document.getElementById(
        'aktuatorContainer_'+sensorIndex
    );

    const items=container.querySelectorAll('.aktuator-item');

    if(items.length>1){

        items[items.length-1].remove();

    }

}

function renderSensor(total) {
    sensorContainer.innerHTML = '';

    if (!total) return;

    for (let i = 0; i < total; i++) {

        sensorContainer.innerHTML += `
        <div class="border rounded-lg p-5 bg-gray-50">

            <h3 class="font-bold text-lg mb-4 text-gray-800">
                Sensor ${i + 1}
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div>
                    <label class="font-semibold block mb-1">Nama Sensor</label>
                    <input type="text"
                           name="nama_sensor[]"
                           class="w-full border rounded-lg px-3 py-2"
                           required>
                </div>

                <div>
                    <label class="font-semibold block mb-1">Mengukur</label>
                    <input type="text"
                           name="parameter[]"
                           class="w-full border rounded-lg px-3 py-2"
                           required>
                </div>

                <div>
                    <label class="font-semibold block mb-1">Satuan</label>
                    <input type="text"
                           name="satuan[]"
                           class="w-full border rounded-lg px-3 py-2"
                           placeholder="ppm / °C / Lux"
                           required>
                </div>

            </div>

            <div class="mt-4">
                <label class="font-semibold block mb-1">
                    Keterangan Sensor
                </label>
                <textarea
                    name="sensor_keterangan[]"
                    rows="2"
                    class="w-full border rounded-lg px-3 py-2"></textarea>
            </div>

            <div class="mt-5 border-t pt-4">
                <div class="flex justify-between items-center mb-3">

                    <label class="font-semibold text-gray-800">
                        Aktuator
                    </label>

                    <div class="flex gap-2">

                        <button
                            type="button"
                            onclick="tambahAktuator(${i})"
                            class="text-sm bg-blue-600 text-white px-3 py-1 rounded-lg hover:bg-blue-700">

                            + Tambah Aktuator

                        </button>

                        <button
                            type="button"
                            onclick="hapusAktuator(${i})"
                            class="text-sm bg-red-600 text-white px-3 py-1 rounded-lg hover:bg-red-700">

                            - Hapus Aktuator

                        </button>

                    </div>

                </div>

                <div id="aktuatorContainer_${i}"></div>
            </div>

        </div>`;
    }

    for (let i = 0; i < total; i++) {
        tambahAktuator(i);
    }
}

jumlahSensor.addEventListener('change', function () {
    renderSensor(parseInt(this.value));
});
</script>
@endsection