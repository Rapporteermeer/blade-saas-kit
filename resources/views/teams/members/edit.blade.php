<x-layouts.app :title="__('Edit Team Member')">
    <x-container>
        <div class="max-w-2xl mx-auto">
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <x-heading>{{ __('Edit Member Role') }}</x-heading>
                        <p class="text-gray-500 dark:text-gray-400">{{ __('Update the role for') }} {{ $user->name }}
                        </p>
                    </div>
                    <x-button href="{{ route('teams.show', $team) }}" variant="secondary" before="phosphor-arrow-left">
                        {{ __('Back to Team') }}
                    </x-button>
                </div>
            </div>

            <form action="{{ route('teams.members.update', [$team, $user]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <x-label for="role_id" :value="__('Role')" />
                        <x-select id="role_id" name="role_id" class="mt-1 block w-full" required>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ $currentRole==$role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                            @endforeach
                        </x-select>
                        <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
                    </div>

                    <div class="flex justify-end mt-6">
                        <x-button type="submit">{{ __('Update Role') }}</x-button>
                    </div>
                </div>
            </form>
        </div>
    </x-container>
</x-layouts.app>