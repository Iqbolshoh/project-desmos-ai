@extends('layouts.dashboard')

@section('title', 'Chat Tutor')

@section('content')
<div class="max-w-4xl mx-auto h-[calc(100vh-8rem)] flex flex-col" x-data="chatTutor()">
    <div class="flex items-center justify-between mb-4 flex-shrink-0">
        <h1 class="text-2xl font-bold text-white flex items-center gap-2">
            <x-lucide-messages-square class="w-7 h-7 text-[var(--accent)]" />
            Chat Tutor
        </h1>
        <div class="badge badge-accent shadow-lg shadow-[var(--accent-glow)]">AI Faol</div>
    </div>

    <!-- Chat Window -->
    <div class="flex-1 min-h-0 bg-black/40 border border-[var(--border-strong)] rounded-2xl flex flex-col overflow-hidden shadow-2xl relative">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: radial-gradient(var(--accent) 1px, transparent 1px); background-size: 24px 24px;"></div>
        
        <!-- Messages Area -->
        <div id="messages-container" class="flex-1 overflow-y-auto p-4 md:p-6 space-y-6 scroll-area relative z-10">
            
            <div class="flex gap-4">
                <div class="w-10 h-10 shrink-0 rounded-full bg-[var(--accent-soft)] flex items-center justify-center border border-[var(--accent-border)]">
                    <x-lucide-bot class="w-5 h-5 text-[var(--accent-hover)]" />
                </div>
                <div class="flex-1 max-w-[85%]">
                    <div class="bg-[var(--bg-overlay)] border border-[var(--border-subtle)] rounded-2xl rounded-tl-sm p-4 text-white text-sm leading-relaxed inline-block shadow-md">
                        Salom! Men Desmos AI yordamchisiman. SAT matematika yoki boshqa mavzularda qanday yordam bera olaman? Masalalarni tashlang yoki tushunmagan joyingizni so'rang.
                    </div>
                </div>
            </div>

            @foreach($messages as $msg)
                @if($msg->sender === 'user')
                    <div class="flex gap-4 flex-row-reverse">
                        <div class="w-10 h-10 shrink-0 rounded-full bg-[var(--gold)]/20 flex items-center justify-center border border-[var(--gold-border)]">
                            <x-lucide-user class="w-5 h-5 text-[var(--gold)]" />
                        </div>
                        <div class="flex-1 max-w-[85%] flex justify-end">
                            <div class="bg-gradient-to-r from-[var(--gold-soft)] to-[var(--bg-raised)] border border-[var(--gold-border)] rounded-2xl rounded-tr-sm p-4 text-white text-sm leading-relaxed inline-block shadow-lg">
                                {!! nl2br(e($msg->content)) !!}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex gap-4">
                        <div class="w-10 h-10 shrink-0 rounded-full bg-[var(--accent-soft)] flex items-center justify-center border border-[var(--accent-border)]">
                            <x-lucide-bot class="w-5 h-5 text-[var(--accent-hover)]" />
                        </div>
                        <div class="flex-1 max-w-[85%]">
                            <div class="bg-[var(--bg-overlay)] border border-[var(--border-subtle)] rounded-2xl rounded-tl-sm p-4 text-white text-sm leading-relaxed inline-block shadow-md">
                                {!! nl2br(e($msg->content)) !!}
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
            
            <!-- Loading indicator -->
            <div x-show="isTyping" class="flex gap-4" style="display: none;">
                <div class="w-10 h-10 shrink-0 rounded-full bg-[var(--accent-soft)] flex items-center justify-center border border-[var(--accent-border)]">
                    <x-lucide-bot class="w-5 h-5 text-[var(--accent-hover)]" />
                </div>
                <div class="flex-1 max-w-[85%]">
                    <div class="bg-[var(--bg-overlay)] border border-[var(--border-subtle)] rounded-2xl rounded-tl-sm p-4 text-white text-sm inline-flex items-center gap-2 h-[52px]">
                        <div class="w-2 h-2 bg-[var(--text-muted)] rounded-full animate-bounce" style="animation-delay: 0s"></div>
                        <div class="w-2 h-2 bg-[var(--text-muted)] rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        <div class="w-2 h-2 bg-[var(--text-muted)] rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Input Area -->
        <div class="p-4 bg-[var(--bg-raised)] border-t border-[var(--border-strong)] flex-shrink-0 relative z-10">
            <form @submit.prevent="sendMessage" class="flex items-end gap-3 max-w-4xl mx-auto">
                <div class="flex-1 relative">
                    <textarea 
                        x-model="newMessage" 
                        @keydown.enter.prevent="sendMessage"
                        rows="1" 
                        class="input w-full resize-none py-3 pr-12 rounded-xl bg-black/30 text-sm max-h-32 scroll-area" 
                        placeholder="Xabar yozing... (Enter bosib jo'nating)"
                        style="min-height: 50px;"
                        x-ref="messageInput"
                    ></textarea>
                </div>
                <button 
                    type="submit" 
                    :disabled="!newMessage.trim() || isTyping"
                    class="h-[50px] w-[50px] rounded-xl flex items-center justify-center shrink-0 btn-primary disabled:opacity-50 transition-all"
                >
                    <x-lucide-send class="w-5 h-5" />
                </button>
            </form>
            <div class="text-center mt-2">
                <span class="text-xs text-[var(--text-muted)]">Mock rejimida "rahmat" yoki "nega" so'zlariga javob qaytaradi.</span>
            </div>
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
            
            // Auto resize textarea
            this.$watch('newMessage', () => {
                const el = this.$refs.messageInput;
                el.style.height = '50px';
                if(el.scrollHeight > 50) {
                    el.style.height = Math.min(el.scrollHeight, 128) + 'px';
                }
            });
        },

        scrollToBottom() {
            setTimeout(() => {
                this.container.scrollTop = this.container.scrollHeight;
            }, 50);
        },

        async sendMessage() {
            const text = this.newMessage.trim();
            if (!text || this.isTyping) return;

            // Add user message to UI immediately
            const userHtml = `
                <div class="flex gap-4 flex-row-reverse">
                    <div class="w-10 h-10 shrink-0 rounded-full bg-[var(--gold)]/20 flex items-center justify-center border border-[var(--gold-border)]">
                        <i data-lucide="user" class="w-5 h-5 text-[var(--gold)]"></i>
                    </div>
                    <div class="flex-1 max-w-[85%] flex justify-end">
                        <div class="bg-gradient-to-r from-[var(--gold-soft)] to-[var(--bg-raised)] border border-[var(--gold-border)] rounded-2xl rounded-tr-sm p-4 text-white text-sm leading-relaxed inline-block shadow-lg">
                            ${text.replace(/\n/g, '<br>')}
                        </div>
                    </div>
                </div>
            `;
            
            const typingIndicator = document.querySelector('[x-show="isTyping"]');
            typingIndicator.insertAdjacentHTML('beforebegin', userHtml);
            if(window.lucide) window.lucide.createIcons();
            
            this.newMessage = '';
            this.$refs.messageInput.style.height = '50px';
            this.isTyping = true;
            this.scrollToBottom();

            try {
                const response = await fetch("{{ route('chat.send', $thread->id) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ message: text })
                });

                if (response.ok) {
                    const data = await response.json();
                    
                    // Add AI reply to UI
                    const aiHtml = `
                        <div class="flex gap-4">
                            <div class="w-10 h-10 shrink-0 rounded-full bg-[var(--accent-soft)] flex items-center justify-center border border-[var(--accent-border)]">
                                <i data-lucide="bot" class="w-5 h-5 text-[var(--accent-hover)]"></i>
                            </div>
                            <div class="flex-1 max-w-[85%]">
                                <div class="bg-[var(--bg-overlay)] border border-[var(--border-subtle)] rounded-2xl rounded-tl-sm p-4 text-white text-sm leading-relaxed inline-block shadow-md">
                                    ${data.reply.replace(/\n/g, '<br>')}
                                </div>
                            </div>
                        </div>
                    `;
                    typingIndicator.insertAdjacentHTML('beforebegin', aiHtml);
                    if(window.lucide) window.lucide.createIcons();
                } else {
                    alert("Xatolik yuz berdi. Iltimos qayta urinib ko'ring.");
                }
            } catch (error) {
                console.error(error);
                alert("Tarmoq xatosi.");
            } finally {
                this.isTyping = false;
                this.scrollToBottom();
            }
        }
    }));
});
</script>
@endsection
