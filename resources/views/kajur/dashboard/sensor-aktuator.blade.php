<div class="bg-white rounded-xl shadow p-6">

    <div class="mb-5">
        <h2 class="text-lg font-bold text-gray-800">Sensor & Aktuator</h2>
    </div>

    <div class="flex items-center justify-around text-center">

        <div class="flex flex-col items-center gap-2">
            <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-green-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h3.041a1.5 1.5 0 011.36.865l1.94 4.155a.75.75 0 001.395-.116l2.207-7.98a.75.75 0 011.395-.116l1.94 4.155a1.5 1.5 0 001.36.865h3.612" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-800">{{ $totalSensor }}</h3>
            <p class="text-sm text-gray-500">Total Sensor</p>
        </div>

        <div class="flex flex-col items-center gap-2">
            <div class="w-14 h-14 rounded-full bg-violet-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-violet-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-800">{{ $totalAktuator }}</h3>
            <p class="text-sm text-gray-500">Total Aktuator</p>
        </div>

    </div>
</div>