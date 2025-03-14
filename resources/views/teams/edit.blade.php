<x-layouts.app :title="__('Edit Team')">
    <x-container>
        <div class="max-w-2xl mx-auto">
            <x-heading class="mb-6">{{ __('Edit Team') }}</x-heading>

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
                        <x-select id="team_type_id" name="team_type_id" class="mt-1 block w-full" required>
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