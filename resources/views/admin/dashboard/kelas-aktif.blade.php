<div class="bg-white rounded-xl shadow p-6 h-full flex flex-col">

    <div class="mb-4">
        <h2 class="text-lg font-bold text-gray-800">Kelas Aktif</h2>
    </div>

    <div class="space-y-4 overflow-y-auto pr-1 flex-1">
        @forelse ($kelasAktifList as $kelas)
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-violet-100 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-violet-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">{{ $kelas->dosen }}</p>
                    <p class="text-xs text-gray-500">
                        Kelompok: {{ $kelas->jumlahKelompok }} &nbsp; Mahasiswa: {{ $kelas->jumlahMahasiswa }}
                    </p>
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-400">Belum ada kelas aktif.</p>
        @endforelse
    </div>
</div>