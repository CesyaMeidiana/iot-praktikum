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
                <a href="{{ route('kajur.dashboard') }}"
                   class="flex items-center gap-4 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('kajur.dashboard') ? 'bg-blue-600 shadow-lg' : 'hover:bg-slate-800' }}">

                    <span class="text-lg">🏠</span>

                    <span class="font-medium">
                        Dashboard
                    </span>

                </a>
            </li>

            {{-- User Management --}}
            <li>
                <a href="{{ route('kajur.users.index') }}"
                   class="flex items-center gap-4 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('kajur.users.*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-slate-800' }}">

                    <span class="text-lg">👥</span>

                    <span>
                        User Management
                    </span>

                </a>
            </li>

            {{-- Kelas --}}
            <li>
                <a href="{{ route('kajur.classrooms.index') }}"
                   class="flex items-center gap-4 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('kajur.classrooms.*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-slate-800' }}">

                    <span class="text-lg">🏫</span>

                    <span>
                        Kelas
                    </span>

                </a>
            </li>

            {{-- Riwayat --}}
            <li>
                <a href="{{ route('kajur.riwayat.index') }}"
                   class="flex items-center gap-4 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('kajur.riwayat.*') ? 'bg-blue-600 shadow-lg' : 'hover:bg-slate-800' }}">

                    <span class="text-lg">🕒</span>

                    <span>
                        Riwayat
                    </span>

                </a>
            </li>

            {{-- Profile --}}
            <li>
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-4 px-4 py-3 rounded-xl transition
                   {{ request()->routeIs('profile.edit') ? 'bg-blue-600 shadow-lg' : 'hover:bg-slate-800' }}">

                    <span class="text-lg">👤</span>

                    <span>
                        Profile
                    </span>

                </a>
            </li>

        </ul>

        {{-- Logout --}}
        <div class="mt-auto pt-6 border-t border-slate-700">

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button
                    type="submit"
                    class="w-full flex items-center gap-4 px-4 py-3 rounded-xl bg-red-600 hover:bg-red-700 transition font-medium">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H9"/>

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 20H6a2 2 0 01-2-2V6a2 2 0 012-2h7"/>

                    </svg>

                    <span>Logout</span>

                </button>

            </form>

        </div>

    </div>

</aside>