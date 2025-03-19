<x-layouts.app :title="__('Subscription Successful')">
    <div class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden rounded-lg">
            <div class="px-4 py-5 sm:p-6 text-center">
                <div
                    class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 dark:bg-green-900 mb-6">
                    <x-phosphor-check-circle-fill class="h-10 w-10 text-green-600 dark:text-green-400" />
                </div>

                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                    {{ __('Subscription Successful!') }}
                </h3>

                <p class="text-gray-600 dark:text-gray-300 mb-6">
                    {{ __('Thank you for your subscription. Your account has been successfully updated.') }}
                </p>

                <div class="mt-8 flex justify-center">
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                        <x-phosphor-arrow-left class="mr-2 -ml-1 h-5 w-5" />
                        {{ __('Return to Dashboard') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>