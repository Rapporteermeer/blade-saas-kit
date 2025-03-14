<x-layouts.app :title="__('Teams')">
    <x-container>
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <x-heading>{{ __('Your Teams') }}</x-heading>

                @can('create', App\Models\Team::class)
                <x-button href="{{ route('teams.create') }}" before="phosphor-plus-circle">
                    {{ __('Create Team') }}
                </x-button>
                @endcan
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach($teams as $team)
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h3 class="text-lg font-semibold">{{ $team->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $team->teamType->name }}</p>
                    </div>
                    @if(auth()->user()->current_team_id === $team->id)
                    <span
                        class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded dark:bg-green-900 dark:text-green-100">Current</span>
                    @endif
                </div>

                <div class="flex space-x-2 mt-4">
                    <x-button size="sm" href="{{ route('teams.show', $team) }}">{{ __('View') }}</x-button>
                    @if(auth()->user()->current_team_id !== $team->id)
                    <form action="{{ route('teams.switch', $team) }}" method="POST">
                        @csrf
                        <x-button size="sm" variant="secondary" type="submit">{{ __('Switch') }}</x-button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </x-container>
</x-layouts.app>