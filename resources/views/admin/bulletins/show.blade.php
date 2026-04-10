<x-app-layout title="{{ $bulletin->subject }} — Admin">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.bulletins.index') }}" class="text-hali-text-secondary hover:text-primary text-sm">← Bulletins</a>
        <span class="text-gray-300">/</span>
        <h1 class="text-xl font-bold text-hali-text-primary truncate">{{ $bulletin->subject }}</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-5xl">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-hali-border shadow-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        @if($bulletin->sent_at)
                            <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700">Sent</span>
                        @else
                            <span class="text-xs px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700">Draft</span>
                        @endif
                        <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">
                            {{ ucfirst(str_replace('_', ' ', $bulletin->audience ?? 'all')) }}
                        </span>
                    </div>
                    @if(!$bulletin->sent_at)
                        <a href="{{ route('admin.bulletins.edit', $bulletin) }}" class="text-sm text-primary hover:underline">Edit</a>
                    @endif
                </div>

                <h2 class="text-lg font-bold text-hali-text-primary mb-4">{{ $bulletin->subject }}</h2>

                <div class="prose prose-sm max-w-none text-hali-text-primary border-t border-hali-border pt-4">
                    {!! $bulletin->body !!}
                </div>
            </div>
        </div>

        <div class="space-y-5">
            <div class="bg-white rounded-2xl border border-hali-border shadow-card p-5">
                <h3 class="text-sm font-semibold text-hali-text-primary mb-3">Details</h3>
                <dl class="space-y-2 text-sm">
                    <div>
                        <dt class="text-xs text-hali-text-secondary">Created</dt>
                        <dd class="text-hali-text-primary">{{ $bulletin->created_at->format('M j, Y g:i A') }}</dd>
                    </div>
                    @if($bulletin->sent_at)
                        <div>
                            <dt class="text-xs text-hali-text-secondary">Sent</dt>
                            <dd class="text-hali-text-primary">{{ $bulletin->sent_at->format('M j, Y g:i A') }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-xs text-hali-text-secondary">Audience</dt>
                        <dd class="text-hali-text-primary capitalize">{{ str_replace('_', ' ', $bulletin->audience ?? 'All Members') }}</dd>
                    </div>
                </dl>
            </div>

            @if(!$bulletin->sent_at)
                <div class="bg-white rounded-2xl border border-hali-border shadow-card p-5">
                    <h3 class="text-sm font-semibold text-hali-text-primary mb-3">Actions</h3>
                    <form method="POST" action="{{ route('admin.bulletins.send', $bulletin) }}"
                          onsubmit="return confirm('Send this bulletin now? This will email all recipients.')">
                        @csrf
                        <button type="submit"
                                class="w-full bg-accent hover:bg-accent-dark text-white font-semibold py-2.5 rounded-lg text-sm transition-colors">
                            Send Bulletin Now
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
