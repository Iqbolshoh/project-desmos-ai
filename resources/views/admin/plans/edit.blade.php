@extends('layouts.dashboard')

@section('title', 'Edit Plan')
@section('header_title', 'Edit Plan')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-white flex items-center gap-2">
            <x-lucide-crown class="w-7 h-7 text-[var(--accent)]" />
            Edit Plan — {{ $plan->name }}
        </h1>
        <a href="{{ route('admin.plans.index') }}" class="btn-secondary flex items-center gap-2">
            <x-lucide-arrow-left class="w-4 h-4" /> Back to Plans
        </a>
    </div>

    <form action="{{ route('admin.plans.update', $plan) }}" method="POST"
        class="card bg-[var(--bg-overlay)] border-[var(--border-strong)] rounded-2xl shadow-xl p-8 space-y-6">
        @csrf
        @method('PUT')
        @include('admin.plans.partials.form', ['plan' => $plan])

        <div class="pt-4 border-t border-[var(--border-subtle)] flex justify-end">
            <button type="submit" class="btn-primary flex items-center gap-2">
                <x-lucide-save class="w-4 h-4" /> Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
