<x-layouts.app :title="__('Team Members')">
    <x-container>
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <x-heading>{{ $team->name }} {{ __('Members') }}</x-heading>
                    <p class="text-gray-500 dark:text-gray-400">{{ __('Manage your team members.') }}</p>
                </div>

                @can('invite', $team)
                <x-button href="{{ route('invitations.create', $team) }}" before="phosphor-user-plus">
                    {{ __('Invite Member') }}
                </x-button>
                @endcan
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
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
                        @foreach($members as $member)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $member->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $member->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                $role = \App\Models\Role::find($member->pivot->role_id);
                                @endphp
                                {{ $role->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                @if($team->owner_id !== $member->id)
                                <div class="flex justify-end space-x-2">
                                    @can('updateMembers', $team)
                                    <x-button size="sm" href="{{ route('teams.members.edit', [$team, $member]) }}">
                                        {{ __('Edit') }}
                                    </x-button>
                                    @endcan

                                    @can('removeMembers', $team)
                                    <form action="{{ route('teams.members.destroy', [$team, $member]) }}" method="POST">
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