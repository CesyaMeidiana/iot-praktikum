<div class="bg-white rounded-xl shadow p-6">

    <div class="mb-5">
        <h2 class="text-lg font-bold text-gray-800">Sensor & Aktuator</h2>
    </div>

    <div class="flex items-center justify-around text-center">

        <div class="flex flex-col items-center gap-2">
            <div class="w-14 h-14 rounded-full bg-violet-100 flex items-center justify-center text-3xl">
    ⚙️
</div>
            <h3 class="text-2xl font-bold text-gray-800">{{ $totalSensor }}</h3>
            <p class="text-sm text-gray-500">Total Sensor</p>
        </div>

        <div class="flex flex-col items-center gap-2">
            <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center text-3xl">
    🌡️
</div>
            <h3 class="text-2xl font-bold text-gray-800">{{ $totalAktuator }}</h3>
            <p class="text-sm text-gray-500">Total Aktuator</p>
        </div>

    </div>
</div>