<x-layouts.app :title="__('Edit Team')">
    <x-container>
        <div class="max-w-2xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <x-heading>{{ __('Edit Team') }}</x-heading>
                <a href="{{ route('teams.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700">
                    <x-phosphor-arrow-left width="16" height="16" />
                    {{ __('Back') }}
                </a>
            </div>

            <form action="{{ route('teams.update', $team) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <x-label for="name" :value="__('Team Name')" />
                        <x-input id="name" name="name" type="text" class="mt-1 block w-full"
                            :value="old('name', $team->name)" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="team_type_id" :value="__('Team Type')" />
                        <x-select id="team_type_id" name="team_type_id"
                            class="mt-1 block w-full dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:focus:border-indigo-500 dark:focus:ring-indigo-500"
                            required>
                            @foreach($teamTypes as $type)
                            <option value="{{ $type->id }}" {{ (old('team_type_id', $team->team_type_id) == $type->id) ?
                                'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                            @endforeach
                        </x-select>
                        <x-input-error :messages="$errors->get('team_type_id')" class="mt-2" />
                    </div>


                    <div class="flex justify-end mt-6">
                        <x-button type="submit">{{ __('Update Team') }}</x-button>
                    </div>
                </div>
            </form>
        </div>
    </x-container>
</x-layouts.app>