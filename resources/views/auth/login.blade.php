<x-guest-layout>
    <div class="flex min-h-screen items-center justify-center bg-zinc-50 dark:bg-zinc-900">
        <div class="w-full max-w-md">
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white">🔧 MyUOS</h1>
                <p class="mt-2 text-zinc-500">Sistem Manajemen Bengkel</p>
            </div>

            <flux:card class="p-8">
                <flux:heading size="lg" class="mb-6">Masuk ke Akun</flux:heading>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="space-y-4">
                        <flux:input
                            label="Email"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required autofocus
                            placeholder="nama@email.com"
                        />

                        <flux:input
                            label="Password"
                            type="password"
                            name="password"
                            required
                            placeholder="••••••••"
                        />

                        @if ($errors->any())
                            <flux:callout variant="danger" icon="exclamation-triangle">
                                {{ $errors->first() }}
                            </flux:callout>
                        @endif

                        <flux:button type="submit" variant="primary" class="w-full">
                            Masuk
                        </flux:button>
                    </div>
                </form>
            </flux:card>
        </div>
    </div>
</x-guest-layout>