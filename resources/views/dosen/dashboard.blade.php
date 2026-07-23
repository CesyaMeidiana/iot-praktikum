@extends('layouts.dosen')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard Monitoring')

@section('content')

<div class="space-y-6">

    @include('dosen.dashboard.summary-cards')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
        <div class="lg:col-span-2">
            @include('dosen.dashboard.node-table')
        </div>
        @include('dosen.dashboard.kelas-aktif')
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
        @include('dosen.dashboard.qos-summary')
        <div class="lg:col-span-2">
            @include('dosen.dashboard.skema-pemakaian')
        </div>
    </div>

    @include('dosen.dashboard.device-monitoring')

</div>

@endsection