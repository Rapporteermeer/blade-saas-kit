<x-layouts.app :title="__('Invite Team Member')">
    <x-container>
        <div class="max-w-2xl mx-auto">
            <div class="mb-6">
                <x-heading>{{ __('Invite to') }} {{ $team->name }}</x-heading>
                <p class="text-gray-500 dark:text-gray-400">{{ __('Send invitations to new team members.') }}</p>
            </div>

            <form action="{{ route('invitations.store', $team) }}" method="POST">
                @csrf

                <div class="space-y-4">
                    <div>
                        <x-label for="email" :value="__('Email Address')" />
                        <x-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')"
                            required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="role_id" :value="__('Role')" />
                        <x-select id="role_id" name="role_id" class="mt-1 block w-full" required>
                            <option value="">{{ __('Select a role') }}</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id')==$role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                            @endforeach
                        </x-select>
                        <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
                    </div>

                    <div class="flex justify-end mt-6">
                        <x-button type="submit">{{ __('Send Invitation') }}</x-button>
                    </div>
                </div>
            </form>
        </div>
    </x-container>
</x-layouts.app>