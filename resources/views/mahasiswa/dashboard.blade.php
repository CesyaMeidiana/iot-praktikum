@extends('layouts.mahasiswa')

@section('title', 'Dashboard Mahasiswa')

@section('page-title', 'Dashboard Mahasiswa')

@section('content')

{{-- Welcome --}}
<div class="mb-8">

    <h1 class="text-3xl font-bold text-slate-800">

        Halo, {{ auth()->user()->name }} 👋

    </h1>

    <p class="text-slate-500 mt-2">

        Selamat datang di Dashboard Monitoring IoT Smart Home Wireless Sensor Network ZigBee.

    </p>

</div>

@include('mahasiswa.components.summary')

@include('mahasiswa.components.monitoring')

@include('mahasiswa.components.chart')

@include('mahasiswa.components.alarm')

@include('mahasiswa.components.praktikum-monitoring')

@include('mahasiswa.components.qos')

@include('mahasiswa.components.qos-analysis')

@endsection