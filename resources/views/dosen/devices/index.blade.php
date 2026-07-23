@extends('layouts.dosen')

@section('title','Master Device')

@section('page-title','Master Device')

@section('content')

<div class="bg-white rounded-lg shadow p-6">

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-2xl font-bold">

            Master Device

        </h2>

        <a
            href="{{ route('dosen.devices.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">

            + Tambah Device

        </a>

    </div>

    <table class="w-full border">

        <thead class="bg-gray-100">

            <tr>

                <th class="border p-3">No</th>

                <th class="border p-3">Node</th>

                <th class="border p-3">Nama Device</th>

                <th class="border p-3">Jumlah Sensor</th>

                <th class="border p-3">Aksi</th>

            </tr>

        </thead>

        <tbody>

        @forelse($devices as $device)

            <tr>

                <td class="border p-3">

                    {{ $loop->iteration }}

                </td>

                <td class="border p-3">

                    Node {{ $device->node }}

                </td>

                <td class="border p-3">

                    {{ $device->nama_device }}

                </td>

                <td class="border p-3 text-center">

                    {{ $device->sensors->count() }}

                </td>

                <td class="border p-3 space-x-2">

                    <a
                        href="{{ route('dosen.devices.show',$device) }}">

                        👁

                    </a>

                    <a
                        href="{{ route('dosen.devices.edit',$device) }}">

                        ✏

                    </a>

                    <form
                        action="{{ route('dosen.devices.destroy',$device) }}"
                        method="POST"
                        style="display:inline">

                        @csrf

                        @method('DELETE')

                        <button
                            onclick="return confirm('Yakin ingin menghapus Master Device ini?')">

                            🗑

                        </button>

                    </form>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="5" class="text-center p-6">

                    Belum ada Master Device.

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

    <div class="mt-6">

        {{ $devices->links() }}

    </div>

</div>

@endsection