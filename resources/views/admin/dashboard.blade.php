@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard Monitoring')

@section('content')

<div class="space-y-6">

    @include('admin.dashboard.summary-cards')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
        <div class="lg:col-span-2">
            @include('admin.dashboard.node-table')
        </div>
        @include('admin.dashboard.kelas-aktif')
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
        @include('admin.dashboard.qos-summary')
        <div class="lg:col-span-2">
            @include('admin.dashboard.skema-pemakaian')
        </div>
    </div>

    @include('admin.dashboard.device-monitoring')

</div>

@endsection