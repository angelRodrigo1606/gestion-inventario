@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="bg-secondary-system-background rounded-2xl p-8">
        <h1 class="text-2xl font-bold text-label mb-1">Bienvenido, {{ Auth::user()->name }}</h1>
        <p class="text-sm text-secondary-label">{{ Auth::user()->email }}</p>

        <div class="h-px bg-separator mt-6 mb-6"></div>

        <p class="text-sm text-secondary-label">Panel de control del sistema de inventario.</p>
    </div>
@endsection
