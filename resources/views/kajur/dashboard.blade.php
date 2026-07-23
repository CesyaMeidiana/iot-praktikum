@extends('layouts.kajur')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard Monitoring')

@section('content')

<div class="space-y-6">

    @include('kajur.dashboard.summary-cards')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
        <div class="lg:col-span-2">
            @include('kajur.dashboard.node-table')
        </div>
        @include('kajur.dashboard.kelas-aktif')
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
        @include('kajur.dashboard.qos-summary')
        <div class="lg:col-span-2">
            @include('kajur.dashboard.skema-pemakaian')
        </div>
    </div>

    @include('kajur.dashboard.device-monitoring')

</div>

@endsection