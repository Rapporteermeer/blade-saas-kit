<x-layouts.auth :title="__('Sign up')">
    <div class="space-y-6">
        <x-auth-header :title="__('Create an account')"
            :description="__('Enter your details below to create your account')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <x-form method="post" :action="route('register')" class="space-y-6">
            @if(session('info'))
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                <div class="flex">
                    <div>
                        <p class="text-sm text-blue-700">{{ session('info') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Name -->
            <x-input type="text" :label="__('Full name')" name="name" required autofocus autocomplete="name" />

            <!-- Email Address -->
            @if(session('invited_email'))
            <div>
                <x-label for="email" :value="__('Email address')" />
                <x-input id="email" type="email" name="email" :value="session('invited_email')"
                    class="mt-1 block w-full bg-gray-100 dark:bg-gray-700" readonly />
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ __('This email address is associated with your invitation and cannot be changed.') }}
                </p>
            </div>
            @else
            <x-input type="email" :label="__('Email address')" name="email" required autocomplete="email" />
            @endif

            <!-- Password -->
            <x-input type="password" :label="__('Password')" name="password" required autocomplete="new-password" />

            <!-- Confirm Password -->
            <x-input type="password" :label="__('Confirm password')" name="password_confirmation" required
                autocomplete="new-password" />

            <!-- Team Fields (only shown if not invited) -->
            @if(!session('invitation_token'))
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Create Your Team') }}</h3>

                <div>
                    <x-label for="team_name" :value="__('Team Name')" />
                    <x-input id="team_name" class="block mt-1 w-full" type="text" name="team_name"
                        :value="old('team_name')" required />
                    <x-input-error :messages="$errors->get('team_name')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-label for="team_type_id" :value="__('Team Type')" />
                    <x-select id="team_type_id" name="team_type_id" class="block mt-1 w-full" required>
                        <option value="">{{ __('Select a team type') }}</option>
                        @foreach($teamTypes as $type)
                        <option value="{{ $type->id }}" {{ old('team_type_id')==$type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                        @endforeach
                    </x-select>
                    <x-input-error :messages="$errors->get('team_type_id')" class="mt-2" />
                </div>
            </div>
            @else
            <div>
                <!-- Laat dit leeg! -->
            </div>
            @endif

            <x-button class="w-full">{{ __('Create account') }}</x-button>
        </x-form>

        <div class="space-x-1 text-center text-sm text-gray-600 dark:text-gray-400">
            {{ __('Already have an account?') }}
            <x-link :href="route('login')">Log in</x-link>
        </div>
    </div>
</x-layouts.auth>