@extends('layouts.mahasiswa')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')

@section('content')

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

        {{-- Filter tabs --}}
        <div class="flex gap-2 px-6 pt-6 border-b border-slate-100 pb-4">
            <a href="{{ route('mahasiswa.notifications.page') }}"
               class="px-4 py-2 rounded-lg text-sm font-medium {{ $filter === 'all' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua
            </a>
            <a href="{{ route('mahasiswa.notifications.page', ['filter' => 'unread']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium {{ $filter === 'unread' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Belum dibaca
                @if($unreadCount > 0)
                    <span class="ml-1 bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full">{{ $unreadCount }}</span>
                @endif
            </a>
            <a href="{{ route('mahasiswa.notifications.page', ['filter' => 'read']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium {{ $filter === 'read' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Sudah dibaca
            </a>
        </div>

        {{-- List --}}
        <div class="divide-y divide-slate-100">
            @forelse($notifications as $n)
                <a href="{{ route('mahasiswa.notifications.open', $n->id) }}"
                   class="flex gap-4 px-6 py-4 hover:bg-slate-50 transition {{ $n->read_at ? '' : 'bg-blue-50/60' }}">

                    <div class="mt-1.5 flex-shrink-0">
                        <span class="block w-2.5 h-2.5 rounded-full {{ $n->read_at ? 'bg-slate-200' : 'bg-blue-500' }}"></span>
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-800">{{ $n->data['title'] }}</p>
                        <p class="text-sm text-slate-500 mt-0.5">{{ $n->data['message'] }}</p>
                        <p class="text-xs text-slate-400 mt-1.5">{{ $n->created_at->diffForHumans() }}</p>
                    </div>

                    @unless($n->read_at)
                        <span class="text-[11px] font-medium text-blue-600 bg-blue-100 px-2 py-1 rounded-full h-fit">Baru</span>
                    @endunless

                </a>
            @empty
                <div class="px-6 py-16 text-center text-slate-400">
                    Belum ada notifikasi{{ $filter !== 'all' ? ' di kategori ini' : '' }}.
                </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $notifications->appends(['filter' => $filter])->links() }}
            </div>
        @endif

    </div>

@endsection