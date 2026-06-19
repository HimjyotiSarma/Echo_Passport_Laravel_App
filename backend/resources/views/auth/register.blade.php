{{-- <div>
    <!-- People find pleasure in different ways. I find it in keeping my mind clear. - Marcus Aurelius -->
</div> --}}
<x-layout.root>

    <x-slot:title>
        Register
    </x-slot:title>

    <div class="min-h-screen flex">

        {{-- Form Side --}}
        <div
            class="flex flex-1 items-center justify-center p-6 bg-slate-100"
        >
            <div class="w-full max-w-md">

                <div
                    class="rounded-3xl bg-white p-10 shadow-xl"
                >

                    <div class="mb-8">

                        <h1
                            class="text-3xl font-bold text-slate-900"
                        >
                            Create Account
                        </h1>

                        <p
                            class="mt-2 text-slate-500"
                        >
                            Join the platform and start building.
                        </p>

                    </div>

                    <x-form.form
                        method="POST"
                        action="{{ route('register') }}"
                    >
                        @csrf

                        <div>
                            <x-form.label for="name">
                                Full Name
                            </x-form.label>

                            <x-form.input
                                type="text"
                                name="name"
                                id="name"
                                placeholder="John Doe"
                                required
                                autofocus
                            />

                            @error('name')
                                <x-form.error :message="$message" />
                            @enderror
                        </div>

                        <div>
                            <x-form.label for="email">
                                Email Address
                            </x-form.label>

                            <x-form.input
                                type="email"
                                name="email"
                                id="email"
                                placeholder="john@example.com"
                                required
                            />

                            @error('email')
                                <x-form.error :message="$message" />
                            @enderror
                        </div>

                        <div>
                            <x-form.label for="password">
                                Password
                            </x-form.label>

                            <x-form.input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="Create a password"
                                required
                            />

                            @error('password')
                                <x-form.error :message="$message" />
                            @enderror
                        </div>

                        <div>
                            <x-form.label for="password_confirmation">
                                Confirm Password
                            </x-form.label>

                            <x-form.input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                placeholder="Confirm your password"
                                required
                            />

                            @error('password_confirmation')
                                <x-form.error :message="$message" />
                            @enderror
                        </div>

                        <x-form.button type="submit">
                            Create Account
                        </x-form.button>

                    </x-form.form>

                </div>

                <p
                    class="mt-6 text-center text-sm text-slate-600"
                >
                    Already have an account?

                    <a
                        href="{{ route('login') }}"
                        class="font-medium text-blue-600 hover:text-blue-700"
                    >
                        Sign In
                    </a>
                </p>

            </div>
        </div>

        {{-- Branding Side --}}
        <div
            class="hidden lg:flex lg:w-1/2 bg-blue-700 text-white"
        >
            <div
                class="flex flex-col justify-center px-20"
            >
                {{-- Branding Logo --}}
                {{-- <div
                    class="mb-10 h-16 w-16 rounded-2xl bg-white/20 flex items-center justify-center text-2xl font-bold"
                >
                    E
                </div> --}}

                <h2
                    class="text-5xl font-bold leading-tight"
                >
                    Start your
                    journey today.
                </h2>

                <p
                    class="mt-6 max-w-lg text-lg text-blue-100"
                >
                    Create an account to connect with your team,
                    exchange messages, and collaborate in real time.
                </p>

                <div
                    class="mt-12 grid grid-cols-2 gap-8"
                >
                    <div>
                        <div class="text-3xl font-bold">
                            Secure
                        </div>

                        <div class="text-blue-200">
                            Authentication
                        </div>
                    </div>

                    <div>
                        <div class="text-3xl font-bold">
                            Fast
                        </div>

                        <div class="text-blue-200">
                            Real-time Messaging
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

</x-layout.root>
