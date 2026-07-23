<div class="bg-white rounded-xl shadow p-6">

    <div class="mb-5">

        <h2 class="text-lg font-bold text-gray-800">
            Statistik Sistem
        </h2>

        <p class="text-sm text-gray-500">
            Ringkasan data Smart Home
        </p>

    </div>

    <div class="grid grid-cols-2 gap-4">

        <div class="bg-slate-100 rounded-lg p-5">

            <p class="text-sm text-gray-500">
                Total Sensor
            </p>

            <h2 class="text-3xl font-bold text-slate-800 mt-2">
                {{ $totalSensor }}
            </h2>

        </div>

        <div class="bg-slate-100 rounded-lg p-5">

            <p class="text-sm text-gray-500">
                Total Aktuator
            </p>

            <h2 class="text-3xl font-bold text-slate-800 mt-2">
                {{ $totalAktuator }}
            </h2>

        </div>

        <div class="bg-slate-100 rounded-lg p-5">

            <p class="text-sm text-gray-500">
                Total Kelompok
            </p>

            <h2 class="text-3xl font-bold text-slate-800 mt-2">
                {{ $totalKelompok }}
            </h2>

        </div>

        <div class="bg-slate-100 rounded-lg p-5">

            <p class="text-sm text-gray-500">
                Data Hari Ini
            </p>

            <h2 class="text-3xl font-bold text-slate-800 mt-2">
                1.248
            </h2>

        </div>

    </div>

</div>