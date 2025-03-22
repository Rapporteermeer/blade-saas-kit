<x-layouts.app :title="__('Create Team')">
    <x-container>
        <div class="max-w-2xl mx-auto">
            <div class="flex items-center mb-6">

                <x-heading>{{ __('Create New Team') }}</x-heading>
            </div>

            <form action="{{ route('teams.store') }}" method="POST">
                @csrf

                <div class="space-y-4">
                    <div>
                        <x-label for="name" :value="__('Team Name')" />
                        <x-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')"
                            required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="team_type_id" :value="__('Team Type')" />
                        <x-select id="team_type_id" name="team_type_id" class="mt-1 block w-full" required>
                            <option value="">{{ __('Select a team type') }}</option>
                            @foreach($teamTypes as $type)
                            <option value="{{ $type->id }}" {{ old('team_type_id')==$type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                            @endforeach
                        </x-select>
                        <x-input-error :messages="$errors->get('team_type_id')" class="mt-2" />
                    </div>

                    <div class="flex justify-end mt-6">
                        <a href="{{ route('teams.index') }}" class="my-2 mr-4 text-gray-500 dark:text-gray-400">
                            {{ __('Terug') }}
                        </a>
                        <x-button type="submit">{{ __('Create Team') }}</x-button>
                    </div>
                </div>
            </form>
        </div>
    </x-container>
</x-layouts.app>