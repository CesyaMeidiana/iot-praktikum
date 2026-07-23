<aside class="w-72 h-screen bg-[#0F172A] text-white flex flex-col">

    {{-- Logo --}}
    <div class="px-7 py-7 border-b border-slate-700">

    <h1 class="text-2xl font-bold tracking-wide">
        IoT Smart Home
    </h1>

    <p class="text-sm text-slate-400 mt-1">
        Wireless Sensor Network ZigBee
    </p>

</div>

    {{-- Menu --}}
   <div class="flex-1 flex flex-col px-5 py-6">
    <p class="text-xs uppercase text-slate-500 font-semibold mb-4 tracking-widest">
    Main Menu
</p>

        <ul class="space-y-3">

            {{-- Dashboard --}}
            <li>
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl transition
{{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 shadow-lg' : 'hover:bg-slate-800' }}"

                    <span class="text-lg">🏠</span>

                    <span class="font-medium">
                        Dashboard
                    </span>
                </a>
            </li>

            {{-- Device --}}
            <li>
                <a href="{{ route('devices.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl transition
{{ request()->routeIs('devices.index') ? 'bg-blue-600 shadow-lg' : 'hover:bg-slate-800' }}"

                    <span class="text-lg">📦</span>

                    <span class="font-medium">
                        Device
                    </span>
    
                </a>
            </li>

            {{-- Riwayat --}}
            <li>
                <a href="{{ route('admin.riwayat.index') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-xl transition
{{ request()->routeIs('admin.riwayat.index') ? 'bg-blue-600 shadow-lg' : 'hover:bg-slate-800' }}"

                   <span class="text-lg">🕒</span>

                    <span class="font-medium">
                        Riwayat
                    </span>
                    

                </a>
            </li>

            {{-- User Management --}}
            <li>
                <a href="{{ route('users.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl transition
{{ request()->routeIs('users.index') ? 'bg-blue-600 shadow-lg' : 'hover:bg-slate-800' }}"

                    <span class="text-lg">👤</span>

                    <span class="font-medium">
                        User Management
                    </span>
                    

                </a>
            </li>

            {{-- Kelompok --}}
            <li>
                <a href="{{ route('classrooms.index') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-xl transition
{{ request()->routeIs('classrooms.index') ? 'bg-blue-600 shadow-lg' : 'hover:bg-slate-800' }}"

                   <span class="text-lg">👥</span>

                    <span class="font-medium">
                        Kelas
                    </span>
                    

                </a>
            </li>

            {{-- Profile --}}
            <li>
                <a href="#"
                class="flex items-center gap-4 px-4 py-3 rounded-xl transition
{{ request()->routeIs('#') ? 'bg-blue-600 shadow-lg' : 'hover:bg-slate-800' }}"

                   <span class="text-lg">👨‍💼</span>

                    <span class="font-medium">
                        Profile
                    </span>
                    

                </a>
            </li>

        </ul>

    </nav>

    {{-- Logout --}}
    <div class="mt-auto pt-6 border-t border-slate-700">

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button
type="submit"
class="w-full flex items-center gap-4 px-4 py-3 rounded-xl bg-red-600 hover:bg-red-700 transition font-medium">
                🚪 Logout

            </button>

        </form>

    </div>

</aside>