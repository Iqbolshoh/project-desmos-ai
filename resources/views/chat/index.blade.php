@extends('layouts.dashboard')

@section('title', 'AI Chat Tutor')

@section('content')
<div class="max-w-4xl mx-auto h-[calc(100vh-8rem)] flex flex-col" x-data="chatTutor()">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-4 flex-shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                <x-lucide-messages-square class="w-7 h-7 text-[var(--gold)]" />
                AI Chat Tutor
            </h1>
            <p class="text-[var(--text-muted)] text-xs mt-0.5">Interactive SAT Math Assistant</p>
        </div>
        <div class="flex items-center gap-2 px-3 py-1.5 bg-emerald-500/10 border border-emerald-500/30 rounded-full">
            <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
            <span class="text-emerald-400 text-xs font-semibold">AI Online</span>
        </div>
    </div>

    {{-- Chat Window --}}
    <div class="flex-1 min-h-0 bg-black/40 border border-[var(--border-strong)] rounded-2xl flex flex-col overflow-hidden shadow-2xl relative">

        <div class="absolute inset-0 opacity-[0.03] pointer-events-none"
             style="background-image: radial-gradient(var(--gold) 1px, transparent 1px); background-size: 24px 24px;"></div>

        {{-- Messages Container --}}
        <div id="messages-container" class="flex-1 overflow-y-auto p-4 md:p-6 space-y-6 relative z-10">

            {{-- Welcome Message --}}
            <div class="flex gap-3">
                <div class="w-9 h-9 shrink-0 rounded-xl bg-gradient-to-br from-[var(--accent)] to-[var(--accent-hover)] flex items-center justify-center shadow-lg">
                    <x-lucide-bot class="w-5 h-5 text-white" />
                </div>
                <div class="flex-1 max-w-[85%]">
                    <div class="bg-[var(--bg-overlay)] border border-[var(--border-subtle)] rounded-2xl rounded-tl-sm p-4 text-[var(--text-primary)] text-sm leading-relaxed shadow-md">
                        Hello! I am your <span class="text-[var(--gold)] font-bold">Desmos AI</span> assistant. Feel free to ask any questions about SAT Math concepts, formulas, or Desmos graphing calculator techniques.
                    </div>
                    <span class="text-[10px] text-[var(--text-muted)] ml-2 mt-1 block">Desmos AI</span>
                </div>
            </div>

            @foreach($messages as $msg)
                @if($msg->role === 'user')
                    <div class="flex gap-3 flex-row-reverse">
                        <div class="w-9 h-9 shrink-0 rounded-xl bg-gradient-to-br from-[var(--gold-deep)] to-[var(--gold)] flex items-center justify-center shadow-lg">
                            <x-lucide-user class="w-5 h-5 text-white" />
                        </div>
                        <div class="flex-1 max-w-[85%] flex flex-col items-end">
                            <div class="bg-gradient-to-r from-[var(--gold-soft)] to-[var(--bg-raised)] border border-[var(--gold-border)] rounded-2xl rounded-tr-sm p-4 text-white text-sm leading-relaxed shadow-lg">
                                {!! nl2br(e($msg->message)) !!}
                            </div>
                            <span class="text-[10px] text-[var(--text-muted)] mr-2 mt-1">You</span>
                        </div>
                    </div>
                @else
                    <div class="flex gap-3">
                        <div class="w-9 h-9 shrink-0 rounded-xl bg-gradient-to-br from-[var(--accent)] to-[var(--accent-hover)] flex items-center justify-center shadow-lg">
                            <x-lucide-bot class="w-5 h-5 text-white" />
                        </div>
                        <div class="flex-1 max-w-[85%]">
                            <div class="bg-[var(--bg-overlay)] border border-[var(--border-subtle)] rounded-2xl rounded-tl-sm p-4 text-[var(--text-primary)] text-sm leading-relaxed shadow-md">
                                {!! nl2br(e($msg->message)) !!}
                            </div>
                            <span class="text-[10px] text-[var(--text-muted)] ml-2 mt-1 block">Desmos AI</span>
                        </div>
                    </div>
                @endif
            @endforeach

            {{-- Typing indicator --}}
            <div x-show="isTyping" class="flex gap-3" style="display:none;">
                <div class="w-9 h-9 shrink-0 rounded-xl bg-gradient-to-br from-[var(--accent)] to-[var(--accent-hover)] flex items-center justify-center shadow-lg">
                    <x-lucide-bot class="w-5 h-5 text-white" />
                </div>
                <div class="flex-1 max-w-[85%]">
                    <div class="bg-[var(--bg-overlay)] border border-[var(--border-subtle)] rounded-2xl rounded-tl-sm px-5 py-4 inline-flex items-center gap-2">
                        <div class="w-2 h-2 bg-[var(--accent)] rounded-full animate-bounce" style="animation-delay:0s"></div>
                        <div class="w-2 h-2 bg-[var(--accent)] rounded-full animate-bounce" style="animation-delay:0.15s"></div>
                        <div class="w-2 h-2 bg-[var(--accent)] rounded-full animate-bounce" style="animation-delay:0.3s"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Suggestion Chips --}}
        <div class="px-4 pb-2 flex gap-2 flex-wrap relative z-10 border-t border-[var(--border-subtle)] pt-3">
            @foreach(['Quadratic Vertex Formula', 'Pythagorean Theorem', 'System of Equations', 'Desmos Graphing Tips'] as $chip)
            <button @click="newMessage = '{{ $chip }}'; $nextTick(() => sendMessage())"
                    class="text-xs px-3 py-1.5 border border-[var(--border-subtle)] text-[var(--text-secondary)] rounded-full hover:border-[var(--gold-border)] hover:text-[var(--gold)] transition-all duration-200 bg-[var(--bg-overlay)]">
                {{ $chip }}
            </button>
            @endforeach
        </div>

        {{-- Input area --}}
        <div class="p-4 bg-[var(--bg-raised)] border-t border-[var(--border-strong)] flex-shrink-0 relative z-10">
            <form @submit.prevent="sendMessage" class="flex items-end gap-3">
                <div class="flex-1 relative">
                    <textarea
                        x-model="newMessage"
                        @keydown.enter.prevent="sendMessage"
                        rows="1"
                        class="input w-full resize-none py-3 pr-4 rounded-xl bg-black/30 text-sm max-h-32 scroll-area focus:border-[var(--gold)] transition-colors"
                        placeholder="Type a message... (Press Enter to send)"
                        style="min-height:50px;"
                        x-ref="messageInput"
                    ></textarea>
                </div>
                <button
                    type="submit"
                    :disabled="!newMessage.trim() || isTyping"
                    class="h-[50px] w-[50px] rounded-xl flex items-center justify-center shrink-0 bg-gradient-to-br from-[var(--gold-deep)] to-[var(--gold)] hover:from-[var(--gold)] hover:to-[var(--gold-alt)] text-white disabled:opacity-40 disabled:cursor-not-allowed transition-all shadow-lg shadow-[var(--gold-glow)] hover:scale-105">
                    <x-lucide-send class="w-5 h-5" />
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('chatTutor', () => ({
        newMessage: '',
        isTyping: false,
        container: null,

        init() {
            this.container = document.getElementById('messages-container');
            this.scrollToBottom();
            this.$watch('newMessage', () => {
                const el = this.$refs.messageInput;
                el.style.height = '50px';
                if (el.scrollHeight > 50) el.style.height = Math.min(el.scrollHeight, 128) + 'px';
            });
        },

        scrollToBottom() {
            setTimeout(() => { this.container.scrollTop = this.container.scrollHeight; }, 60);
        },

        makeUserBubble(text) {
            return `<div class="flex gap-3 flex-row-reverse">
                <div class="w-9 h-9 shrink-0 rounded-xl bg-gradient-to-br from-[var(--gold-deep)] to-[var(--gold)] flex items-center justify-center shadow-lg">
                    <i data-lucide="user" class="w-5 h-5 text-white"></i>
                </div>
                <div class="flex-1 max-w-[85%] flex flex-col items-end">
                    <div class="bg-gradient-to-r from-[var(--gold-soft)] to-[var(--bg-raised)] border border-[var(--gold-border)] rounded-2xl rounded-tr-sm p-4 text-white text-sm leading-relaxed shadow-lg">${text.replace(/\n/g,'<br>')}</div>
                    <span class="text-[10px] text-[var(--text-muted)] mr-2 mt-1">You</span>
                </div>
            </div>`;
        },

        makeAiBubble(text) {
            return `<div class="flex gap-3">
                <div class="w-9 h-9 shrink-0 rounded-xl bg-gradient-to-br from-[var(--accent)] to-[var(--accent-hover)] flex items-center justify-center shadow-lg">
                    <i data-lucide="bot" class="w-5 h-5 text-white"></i>
                </div>
                <div class="flex-1 max-w-[85%]">
                    <div class="bg-[var(--bg-overlay)] border border-[var(--border-subtle)] rounded-2xl rounded-tl-sm p-4 text-[var(--text-primary)] text-sm leading-relaxed shadow-md">${text.replace(/\n/g,'<br>')}</div>
                    <span class="text-[10px] text-[var(--text-muted)] ml-2 mt-1 block">Desmos AI</span>
                </div>
            </div>`;
        },

        async sendMessage() {
            const text = this.newMessage.trim();
            if (!text || this.isTyping) return;

            const typingEl = document.querySelector('[x-show="isTyping"]');
            typingEl.insertAdjacentHTML('beforebegin', this.makeUserBubble(text));
            if (window.lucide) window.lucide.createIcons();

            this.newMessage = '';
            this.$refs.messageInput.style.height = '50px';
            this.isTyping = true;
            this.scrollToBottom();

            try {
                const res = await fetch("{{ route('chat.send', $thread->id) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ message: text })
                });

                if (res.ok) {
                    const data = await res.json();
                    typingEl.insertAdjacentHTML('beforebegin', this.makeAiBubble(data.reply));
                    if (window.lucide) window.lucide.createIcons();
                } else {
                    alert("An error occurred while sending message.");
                }
            } catch (e) {
                console.error(e);
                alert("Network error.");
            } finally {
                this.isTyping = false;
                this.scrollToBottom();
            }
        }
    }));
});
</script>
@endsection
