<x-layouts.app :title="$team->name">
    <x-container>
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <x-heading>{{ $team->name }}</x-heading>
                    <p class="text-gray-500 dark:text-gray-400">{{ $team->teamType->name }}</p>
                </div>

                <div class="flex space-x-2">
                    @can('invite', $team)
                    <x-button href="{{ route('invitations.create', $team) }}" before="phosphor-user-plus">
                        {{ __('Invite Member') }}
                    </x-button>
                    @endcan

                    @can('update', $team)
                    <x-button href="{{ route('teams.edit', $team) }}" variant="secondary" before="phosphor-pencil">
                        {{ __('Edit Team') }}
                    </x-button>
                    @endcan
                </div>
            </div>
        </div>

        @if(session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
            class="mb-4 p-4 bg-green-100 dark:bg-green-800/20 border border-green-200 dark:border-green-800/30 rounded-lg text-green-700 dark:text-green-400">
            {{ session('success') }}
        </div>
        @endif


        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-xl font-semibold mb-4">{{ __('Team Members') }}</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('Name') }}</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('Email') }}</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('Role') }}</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($team->users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                $role = \App\Models\Role::find($user->pivot->role_id);
                                @endphp
                                {{ $role->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                @if($team->owner_id !== $user->id)
                                <div class="flex justify-end space-x-2">
                                    @can('updateMembers', $team)
                                    <x-button size="sm" href="{{ route('teams.members.edit', [$team, $user]) }}">
                                        {{ __('Edit') }}
                                    </x-button>
                                    @endcan

                                    @can('removeMembers', $team)
                                    <form action="{{ route('teams.members.destroy', [$team, $user]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <x-button size="sm" variant="danger" type="submit"
                                            onclick="return confirm('Are you sure you want to remove this member?')">
                                            {{ __('Remove') }}
                                        </x-button>
                                    </form>
                                    @endcan
                                </div>
                                @else
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Owner') }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </x-container>
</x-layouts.app>