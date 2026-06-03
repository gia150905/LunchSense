<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Favorite Cafeteria -->
        <div>
            <x-input-label for="favorite_cafeteria" :value="__('Favorite Cafeteria')" />
            <select id="favorite_cafeteria" name="favorite_cafeteria" class="mt-1 block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm">
                <option value="">Select Cafeteria</option>
                @foreach(['DKG 1', 'DKG 2', 'DKG 3', 'DKG 4', 'DKG 5', 'DKG 6'] as $cafe)
                    <option value="{{ $cafe }}" {{ old('favorite_cafeteria', $user->favorite_cafeteria) === $cafe ? 'selected' : '' }}>{{ $cafe }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('favorite_cafeteria')" />
        </div>

        <!-- Dietary Preferences -->
        <div>
            <x-input-label :value="__('Dietary Preferences')" />
            <div class="mt-2 grid grid-cols-2 gap-2">
                @foreach(['Vegan', 'Halal', 'Nut-Free', 'Gluten-Free', 'Low-Carb'] as $tag)
                    @php
                        $userTags = $user->active_dietary_tags ?? [];
                        if (is_string($userTags)) {
                            $userTags = json_decode($userTags, true) ?? [];
                        }
                        $isChecked = is_array($userTags) && in_array($tag, $userTags);
                    @endphp
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="active_dietary_tags[]" value="{{ $tag }}" {{ $isChecked ? 'checked' : '' }} class="rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="ms-2 text-sm text-gray-600">{{ $tag }}</span>
                    </label>
                @endforeach
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('active_dietary_tags')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
