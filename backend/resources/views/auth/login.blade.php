{{-- <div>
    <!-- Nothing worth having comes easy. - Theodore Roosevelt -->
</div> --}}
<x-layout.root>

    <div class="min-h-screen flex">

        {{-- Left Side --}}
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

                <h1
                    class="text-5xl font-bold leading-tight"
                >
                    Build better
                    conversations.
                </h1>

                <p
                    class="mt-6 max-w-lg text-lg text-blue-100"
                >
                    A modern messaging platform for teams,
                    communities and customers.
                </p>
            </div>
        </div>

        {{-- Right Side --}}
        <div
            class="flex flex-1 items-center justify-center p-6"
        >
            <div
                class="w-full max-w-md"
            >

                <div
                    class="rounded-3xl bg-white p-10 shadow-xl"
                >

                    <div class="mb-8">

                        <h2
                            class="text-3xl font-bold text-slate-900"
                        >
                            Sign In
                        </h2>
                        {{-- <div class="bg-red-500 text-white p-10 text-5xl">TAILWIND WORKING</div> --}}

                        <p
                            class="mt-2 text-slate-500"
                        >
                            Welcome back.
                        </p>

                    </div>

                    <x-form.form
                        method="POST"
                        action="{{ route('login') }}"
                    >
                        @csrf

                        <div>
                            <x-form.label for="email">
                                Email Address
                            </x-form.label>

                            <x-form.input
                                type="email"
                                name="email"
                                id="email"
                                placeholder="john@example.com"
                            />

                            @error('email')
                                <x-form.error
                                    :message="$message"
                                />
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
                                placeholder="Enter your password"
                            />

                            @error('password')
                                <x-form.error
                                    :message="$message"
                                />
                            @enderror
                        </div>

                        <div
                            class="flex items-center justify-between"
                        >
                            <label
                                class="flex items-center gap-2 text-sm text-slate-600"
                            >
                                <input
                                    type="checkbox"
                                    name="remember"
                                >

                                Remember me
                            </label>

                            <a
                                href="#"
                                class="text-sm text-blue-600 hover:text-blue-700"
                            >
                                Forgot password?
                            </a>
                        </div>

                        <x-form.button
                            type="submit"
                        >
                            Sign In
                        </x-form.button>
                    </x-form.form>

                </div>

                <p
                    class="mt-6 text-center text-sm text-slate-600"
                >
                    Don't have an account?

                    <a
                        href="{{ route('register') }}"
                        class="font-medium text-blue-600 hover:text-blue-700"
                    >
                        Create Account
                    </a>
                </p>

            </div>
        </div>

    </div>

</x-layout.root>
