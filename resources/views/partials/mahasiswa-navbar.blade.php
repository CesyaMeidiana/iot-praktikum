<header class="bg-white h-20 border-b border-slate-200 flex items-center justify-between px-8 shadow-sm">

    {{-- Left --}}
    <div>

        <h2 class="text-2xl font-bold text-slate-800">
            @yield('page-title', 'Dashboard')
        </h2>

        <p class="text-sm text-slate-500 mt-1">
            Selamat datang di Sistem Monitoring IoT Smart Home
        </p>

    </div>

    {{-- Right --}}
    <div class="flex items-center gap-6">

        {{-- Date & Time --}}
        <div class="hidden lg:flex items-center gap-3 bg-slate-100 px-5 py-3 rounded-xl">

            <div class="text-blue-600">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-6 h-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10m-11 9h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v11a2 2 0 002 2z"/>

                </svg>

            </div>

            <div>

                <div id="currentDate"
                    class="text-sm font-semibold text-slate-700">
                </div>

                <div id="currentTime"
                    class="text-xs text-slate-500">
                </div>

            </div>

        </div>

{{-- Notification --}}
<div class="relative">

    <button
        id="notifBtn"
        class="relative w-11 h-11 rounded-xl bg-slate-100 hover:bg-slate-200 transition flex items-center justify-center">

        <svg xmlns="http://www.w3.org/2000/svg"
            class="w-6 h-6 text-slate-700"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor">

            <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118
                14.158V11a6.002 6.002 0 00-4-5.659V5a2
                2 0 10-4 0v.341C7.67 6.165 6 8.388 6
                11v3.159c0 .538-.214 1.055-.595
                1.436L4 17h5m6 0a3 3 0
                11-6 0m6 0H9"/>

        </svg>

        <span
            id="notifBadge"
            class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] w-5 h-5 rounded-full items-center justify-center"
            style="display:none;">
            0
        </span>

    </button>

    {{-- Dropdown --}}
    <div
        id="notifDropdown"
        class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-slate-200 z-50 max-h-96 overflow-y-auto"
        style="display: none;">

        <div class="px-4 py-3 border-b border-slate-100 font-semibold text-slate-700">
            Notifikasi
        </div>

        <div id="notifList" class="divide-y divide-slate-100"></div>

        <div id="notifEmpty" class="px-4 py-6 text-center text-sm text-slate-400" style="display:none;">
            Belum ada notifikasi
        </div>

        <a href="{{ route('mahasiswa.notifications.page') }}"
           class="block text-center text-sm font-medium text-blue-600 hover:bg-slate-50 px-4 py-3 border-t border-slate-100">
            Lihat semua notifikasi
        </a>

    </div>

</div>

<script>
(function () {
    const btn      = document.getElementById('notifBtn');
    const dropdown = document.getElementById('notifDropdown');
    const badge    = document.getElementById('notifBadge');
    const list     = document.getElementById('notifList');
    const empty    = document.getElementById('notifEmpty');

    const NOTIF_URL          = "{{ route('mahasiswa.notifications.index') }}";
    const MARK_READ_URL      = (id) => `/mahasiswa/notifications/${id}/read`;
    const MARK_ALL_READ_URL  = "{{ route('mahasiswa.notifications.markAllRead') }}";
    const CSRF_TOKEN         = document.querySelector('meta[name="csrf-token"]')?.content;

    function render(data) {
        // badge
        if (data.unread_count > 0) {
            badge.style.display = 'flex';
            badge.textContent = data.unread_count > 9 ? '9+' : data.unread_count;
        } else {
            badge.style.display = 'none';
        }

        // list
        list.innerHTML = '';
        if (data.notifications.length === 0) {
            empty.style.display = 'block';
            return;
        }
        empty.style.display = 'none';

        data.notifications.forEach((n) => {
            const item = document.createElement('div');
            item.className = 'px-4 py-3 hover:bg-slate-50 cursor-pointer flex gap-3 ' + (n.is_read ? '' : 'bg-blue-50/50');
            item.innerHTML = `
                <div class="mt-1 w-2 h-2 rounded-full flex-shrink-0 ${n.is_read ? 'bg-transparent' : 'bg-blue-500'}"></div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-slate-800 truncate">${n.title}</p>
                    <p class="text-xs text-slate-500 mt-0.5">${n.message}</p>
                    <p class="text-[11px] text-slate-400 mt-1">${n.time}</p>
                </div>
            `;
            item.addEventListener('click', () => markAsRead(n.id));
            list.appendChild(item);
        });
    }

    function fetchNotifications() {
        fetch(NOTIF_URL, { headers: { 'Accept': 'application/json' } })
            .then((res) => res.json())
            .then(render)
            .catch((err) => console.error('Gagal ambil notifikasi:', err));
    }

    function markAsRead(id) {
        fetch(MARK_READ_URL(id), {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
            },
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.url) window.location.href = data.url;
            })
            .catch((err) => console.error('Gagal menandai notifikasi:', err));
    }

    function markAllAsRead() {
        fetch(MARK_ALL_READ_URL, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
            },
        }).catch((err) => console.error('Gagal menandai semua dibaca:', err));
    }

    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        const isOpen = dropdown.style.display === 'block';
        dropdown.style.display = isOpen ? 'none' : 'block';

        if (!isOpen) {
            // Render dulu pake status is_read yang lama (biar keliatan mana yang baru),
            // baru setelah itu tandai semua sudah dibaca di server.
            fetch(NOTIF_URL, { headers: { 'Accept': 'application/json' } })
                .then((res) => res.json())
                .then((data) => {
                    render(data);
                    markAllAsRead();
                })
                .catch((err) => console.error('Gagal ambil notifikasi:', err));
        }
    });

    document.addEventListener('click', (e) => {
        if (!dropdown.contains(e.target) && !btn.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });

    // Polling tiap 20 detik buat update badge count
    fetchNotifications();
    setInterval(fetchNotifications, 20000);
})();
</script>


        {{-- User --}}
        <div class="flex items-center gap-3">

            <div class="text-right">

                <h4 class="font-semibold text-slate-800">

                    {{ auth()->user()->name }}

                </h4>

                <p class="text-sm text-slate-500">

                    {{ auth()->user()->getRoleNames()->first() }}

                </p>

            </div>

            <div
                class="w-11 h-11 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">

                {{ strtoupper(substr(auth()->user()->name,0,1)) }}

            </div>

        </div>

    </div>

</header>

<script>

function updateClock(){

    const now = new Date();

    const date = now.toLocaleDateString('id-ID',{
        weekday:'long',
        day:'numeric',
        month:'long',
        year:'numeric'
    });

    const time = now.toLocaleTimeString('id-ID');

    document.getElementById('currentDate').innerHTML = date;

    document.getElementById('currentTime').innerHTML = time;

}

updateClock();

setInterval(updateClock,1000);

</script>