@extends('layouts.dashboard')

@section('title', 'Savollar Boshqaruvi')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                <x-lucide-database class="w-7 h-7 text-[var(--accent)]" />
                Savollar Boshqaruvi
            </h1>
            <p class="text-[var(--text-secondary)] mt-1">Platformadagi barcha test savollarini boshqarish paneli.</p>
        </div>
        <a href="{{ route('admin.questions.create') }}" class="btn-primary flex items-center gap-2">
            <x-lucide-plus class="w-4 h-4" /> Yangi Savol
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-500/10 border border-green-500/30 text-green-400 p-4 rounded-lg flex items-center gap-3">
        <x-lucide-check-circle class="w-5 h-5" />
        {{ session('success') }}
    </div>
    @endif

    <div class="card bg-[var(--bg-overlay)] border-[var(--border-strong)] overflow-hidden">
        <table class="w-full text-left text-sm text-[var(--text-secondary)]">
            <thead class="bg-black/40 text-[var(--text-muted)] font-mono text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">Mavzu</th>
                    <th class="px-6 py-4">Daraja / Tur</th>
                    <th class="px-6 py-4">Matni</th>
                    <th class="px-6 py-4">Harakatlar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[var(--border-subtle)]">
                @forelse($questions as $q)
                <tr class="hover:bg-white/5 transition-colors">
                    <td class="px-6 py-4 font-mono">{{ $q->id }}</td>
                    <td class="px-6 py-4 font-medium text-white flex items-center gap-2">
                        <x-dynamic-component :component="'lucide-' . ($q->topic->icon ?? 'help-circle')" class="w-4 h-4 text-[var(--accent)]" />
                        {{ $q->topic->name ?? 'Noma\'lum' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="badge badge-accent uppercase text-[0.65rem]">{{ $q->difficulty }}</span>
                        @if($q->is_diagnostic)
                            <span class="badge bg-yellow-500/10 text-yellow-500 uppercase text-[0.65rem] ml-1">Diag</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 w-1/3">
                        <div class="line-clamp-2" title="{{ $q->prompt }}">{{ $q->prompt }}</div>
                    </td>
                    <td class="px-6 py-4 flex gap-2">
                        <a href="{{ route('admin.questions.edit', $q->id) }}" class="text-[var(--accent-hover)] hover:text-[var(--accent-alt)] transition-colors p-2 rounded hover:bg-[var(--accent-soft)]" title="Tahrirlash">
                            <x-lucide-pencil class="w-4 h-4" />
                        </a>
                        <form action="{{ route('admin.questions.destroy', $q->id) }}" method="POST" onsubmit="return confirm('Rostdan ham o\'chirmoqchimisiz?');">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-400 hover:text-red-300 transition-colors p-2 rounded hover:bg-red-500/10" title="O'chirish">
                                <x-lucide-trash-2 class="w-4 h-4" />
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-[var(--text-muted)]">
                        Hozircha hech qanday savol kiritilmagan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $questions->links() }}
    </div>
</div>
@endsection
