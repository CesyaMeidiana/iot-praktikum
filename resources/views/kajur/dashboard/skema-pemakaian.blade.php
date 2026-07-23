<div class="bg-white rounded-xl shadow p-5 h-full">

    {{-- Header --}}
    <div class="mb-4">
        <h2 class="text-lg font-bold text-gray-800">
            Sistem Pemakaian Skema
        </h2>

        <p class="text-xs text-gray-500 mt-1">
            Berdasarkan Praktikum Terakhir
        </p>
    </div>

    <div class="grid grid-cols-3 gap-3">

        {{-- ================= TOPOLOGI ================= --}}
        <div>

            <h3 class="text-[12px] font-semibold uppercase tracking-wide text-gray-500 mb-2">
                Topologi
            </h3>

            <div class="space-y-1.5">

                <div class="flex items-center justify-between rounded-md border border-gray-200 bg-gray-50 px-2 py-1.5">
                    <span class="text-[12px] font-medium text-gray-700">Point to Point</span>
                    <span class="text-[13px] font-bold text-blue-600">{{ $skemaTopologi['Point to Point'] }}</span>
                </div>

                <div class="flex items-center justify-between rounded-md border border-gray-200 bg-gray-50 px-2 py-1.5">
                    <span class="text-[12px] font-medium text-gray-700">Star</span>
                    <span class="text-[13px] font-bold text-blue-600">{{ $skemaTopologi['Star'] }}</span>
                </div>

                <div class="flex items-center justify-between rounded-md border border-gray-200 bg-gray-50 px-2 py-1.5">
                    <span class="text-[12px] font-medium text-gray-700">Mesh</span>
                    <span class="text-[13px] font-bold text-blue-600">{{ $skemaTopologi['Mesh'] }}</span>
                </div>

                <div class="flex items-center justify-between rounded-md border border-gray-200 bg-gray-50 px-2 py-1.5">
                    <span class="text-[12px] font-medium text-gray-700">Tree</span>
                    <span class="text-[13px] font-bold text-blue-600">{{ $skemaTopologi['Tree'] }}</span>
                </div>

            </div>

        </div>

        {{-- ================= SCENARIO ================= --}}
        <div>

            <h3 class="text-[12px] font-semibold uppercase tracking-wide text-gray-500 mb-2">
                Scenario
            </h3>

            <div class="space-y-1.5">

                <div class="flex items-center justify-between rounded-md border border-gray-200 bg-gray-50 px-2 py-1.5">
                    <span class="text-[12px] font-medium text-gray-700">LOS</span>
                    <span class="text-[13px] font-bold text-green-600">{{ $skemaLosNlos['LOS'] }}</span>
                </div>

                <div class="flex items-center justify-between rounded-md border border-gray-200 bg-gray-50 px-2 py-1.5">
                    <span class="text-[12px] font-medium text-gray-700">NLOS</span>
                    <span class="text-[13px] font-bold text-green-600">{{ $skemaLosNlos['NLOS'] }}</span>
                </div>

            </div>

        </div>

        {{-- ================= JARAK ================= --}}
        <div>

            <h3 class="text-[12px] font-semibold uppercase tracking-wide text-gray-500 mb-2">
                Jarak
            </h3>

            <div class="space-y-1.5">

                <div class="flex items-center justify-between rounded-md border border-gray-200 bg-gray-50 px-2 py-1.5">
                    <span class="text-[12px] font-medium text-gray-700">1 M</span>
                    <span class="text-[13px] font-bold text-orange-600">{{ $skemaJarak[1] }}</span>
                </div>

                <div class="flex items-center justify-between rounded-md border border-gray-200 bg-gray-50 px-2 py-1.5">
                    <span class="text-[12px] font-medium text-gray-700">5 M</span>
                    <span class="text-[13px] font-bold text-orange-600">{{ $skemaJarak[5] }}</span>
                </div>

                <div class="flex items-center justify-between rounded-md border border-gray-200 bg-gray-50 px-2 py-1.5">
                    <span class="text-[12px] font-medium text-gray-700">10 M</span>
                    <span class="text-[13px] font-bold text-orange-600">{{ $skemaJarak[10] }}</span>
                </div>

                <div class="flex items-center justify-between rounded-md border border-gray-200 bg-gray-50 px-2 py-1.5">
                    <span class="text-[12px] font-medium text-gray-700">15 M</span>
                    <span class="text-[13px] font-bold text-orange-600">{{ $skemaJarak[15] }}</span>
                </div>

                <div class="flex items-center justify-between rounded-md border border-gray-200 bg-gray-50 px-2 py-1.5">
                    <span class="text-[12px] font-medium text-gray-700">20 M</span>
                    <span class="text-[13px] font-bold text-orange-600">{{ $skemaJarak[20] }}</span>
                </div>

            </div>

        </div>

    </div>

</div>