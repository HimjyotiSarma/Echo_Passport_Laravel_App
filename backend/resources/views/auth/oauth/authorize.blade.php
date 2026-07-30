<x-layout.root>

    <div class="min-h-screen flex">

        {{-- Left Side --}}
        <div
            class="hidden lg:flex lg:w-1/2 bg-blue-700 text-white"
        >
            <div
                class="flex flex-col justify-center px-20"
            >
                <h1
                    class="text-5xl font-bold leading-tight"
                >
                    Authorize
                    access.
                </h1>

                <p
                    class="mt-6 max-w-lg text-lg text-blue-100"
                >
                    Review the permissions requested by the application
                    before granting access to your account.
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
                            {{ $client->name }}
                        </h2>

                        <p
                            class="mt-2 text-slate-500"
                        >
                            wants permission to access your account.
                        </p>

                    </div>

                    {{-- Account Information --}}
                    <div
                        class="rounded-2xl border border-slate-200 bg-slate-50 p-5"
                    >

                        <p
                            class="text-sm text-slate-500"
                        >
                            Signed in as
                        </p>

                        <p
                            class="mt-1 font-semibold text-slate-900"
                        >
                            {{ $user->name }}
                        </p>

                        <p
                            class="text-sm text-slate-500"
                        >
                            {{ $user->email }}
                        </p>

                    </div>

                    {{-- Requested Scopes --}}
                    @if(count($scopes))
                        <div class="mt-8">

                            <h3
                                class="mb-4 text-sm font-semibold uppercase tracking-wide text-slate-500"
                            >
                                Requested Permissions
                            </h3>

                            <div class="space-y-3">

                                @foreach($scopes as $scope)
                                    <div
                                        class="flex gap-3 rounded-xl border border-slate-200 p-4"
                                    >

                                        <div
                                            class="mt-1 h-2.5 w-2.5 rounded-full bg-blue-600"
                                        ></div>

                                        <div>

                                            <p
                                                class="font-medium text-slate-900"
                                            >
                                                {{ $scope->description }}
                                            </p>

                                            <p
                                                class="mt-1 text-sm text-slate-500"
                                            >
                                                {{ $scope->id }}
                                            </p>

                                        </div>

                                    </div>
                                @endforeach

                            </div>

                        </div>
                    @endif

                    {{-- Actions --}}
                    <div
                        class="mt-10 flex gap-4"
                    >

                        {{-- Cancel --}}
                        <form
                            method="POST"
                            action="{{ route('passport.authorizations.deny') }}"
                            class="flex-1"
                        >
                            @csrf
                            @method('DELETE')

                            <input
                                type="hidden"
                                name="state"
                                value="{{ request('state') }}"
                            >

                            <input
                                type="hidden"
                                name="client_id"
                                value="{{ $client->getKey() }}"
                            >

                            <input
                                type="hidden"
                                name="auth_token"
                                value="{{ $authToken }}"
                            >

                            <x-form.button
                                type="submit"
                                variant="secondary"
                            >
                                Cancel
                            </x-form.button>

                        </form>

                        {{-- Authorize --}}
                        <form
                            method="POST"
                            action="{{ route('passport.authorizations.approve') }}"
                            class="flex-1"
                        >
                            @csrf

                            <input
                                type="hidden"
                                name="state"
                                value="{{ request('state') }}"
                            >

                            <input
                                type="hidden"
                                name="client_id"
                                value="{{ $client->getKey() }}"
                            >

                            <input
                                type="hidden"
                                name="auth_token"
                                value="{{ $authToken }}"
                            >

                            <x-form.button
                                type="submit"
                            >
                                Authorize
                            </x-form.button>

                        </form>

                    </div>

                </div>

            </div>
        </div>

    </div>

</x-layout.root>
