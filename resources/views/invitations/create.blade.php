<x-layouts.app :title="__('Invite Team Member')">
    <x-container>
        <div class="max-w-2xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <x-heading>{{ __('Invite to') }} {{ $team->name }}</x-heading>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">
                        {{ __('Send invitations to new team members.') }}
                    </p>
                </div>
                <a href="{{ route('teams.show', $team) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700">
                    <x-phosphor-arrow-left width="16" height="16" />
                    {{ __('Back') }}
                </a>
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
                        <x-select id="role_id" name="role_id"
                            class="mt-1 block w-full dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:focus:border-indigo-500 dark:focus:ring-indigo-500"
                            required>
                            <option value="" disabled selected>{{ __('Select a role') }}</option>
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