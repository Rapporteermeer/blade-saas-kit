<x-layouts.app :title="__('Billing')">
    <section class="w-full">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ __('Subscription Management') }}</h1>

            @if(session('success'))
            <div class="mt-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                {{ session('success') }}
            </div>
            @endif

            @if(session('warning'))
            <div class="mt-4 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700">
                {{ session('warning') }}
            </div>
            @endif

            <div class="mt-6">
                @if($onTrial && $trialEndsAt)
                <div class="p-4 bg-blue-50 border-l-4 border-blue-400 mb-6">
                    <p class="text-sm text-blue-700">
                        {{ __('You are currently on a trial period that ends on') }} {{ $trialEndsAt->format('d-m-Y')
                        }}.
                    </p>
                </div>
                @endif

                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        {{ $subscribed ? __('Manage Your Subscription') : __('Start a Subscription') }}
                    </h2>

                    <p class="mb-6 text-gray-600 dark:text-gray-400">
                        {{ $subscribed
                        ? __('You can manage your subscription, update payment methods, or view billing history through
                        the Stripe Customer Portal.')
                        : __('Choose a subscription plan that fits your needs.') }}
                    </p>
                    @if(!$subscribed)
                    <div class="mb-6" x-data="{ selectedPlan: '{{ env('STRIPE_PRICE_THREE_MONTHLY_ID') }}' }">
                        <form action="{{ route('billing.checkout') }}" method="POST">
                            @csrf
                            <input type="hidden" name="price_id" x-model="selectedPlan">

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                <!-- Monthly Plan Card -->
                                <div @click="selectedPlan = '{{ env('STRIPE_PRICE_MONTHLY_ID') }}'"
                                    :class="{'border-2 border-blue-500 bg-blue-50 dark:bg-blue-900/20': selectedPlan === '{{ env('STRIPE_PRICE_MONTHLY_ID') }}'}"
                                    class="cursor-pointer rounded-lg p-5 border dark:border-gray-700 hover:shadow-md transition-all duration-200">
                                    <div class="flex flex-col h-full">
                                        <h3 class="text-lg font-semibold mb-2">{{ __('Monthly') }}</h3>
                                        <div class="text-2xl font-bold mb-1">
                                            {{ config('cashier.currency_symbol') }}{{ env('CASHIER_CURRENCY_LOCALE') ===
                                            'nl_NL' ? '12,99' : '12.99' }}
                                        </div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ __('per month') }}
                                        </p>
                                        <div class="mt-auto">
                                            <div :class="{'bg-blue-500': selectedPlan === '{{ env('STRIPE_PRICE_MONTHLY_ID') }}', 'bg-gray-200 dark:bg-gray-700': selectedPlan !== '{{ env('STRIPE_PRICE_MONTHLY_ID') }}'}"
                                                class="w-5 h-5 rounded-full flex items-center justify-center">
                                                <div x-show="selectedPlan === '{{ env('STRIPE_PRICE_MONTHLY_ID') }}'"
                                                    class="w-2 h-2 bg-white rounded-full"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quarterly Plan Card -->
                                <div @click="selectedPlan = '{{ env('STRIPE_PRICE_THREE_MONTHLY_ID') }}'"
                                    :class="{'border-2 border-blue-500 bg-blue-50 dark:bg-blue-900/20': selectedPlan === '{{ env('STRIPE_PRICE_THREE_MONTHLY_ID') }}'}"
                                    class="cursor-pointer rounded-lg p-5 border dark:border-gray-700 hover:shadow-md transition-all duration-200">
                                    <div class="flex flex-col h-full">
                                        <h3 class="text-lg font-semibold mb-2">{{ __('Quarterly') }}</h3>
                                        <div class="text-2xl font-bold mb-1">
                                            {{ config('cashier.currency_symbol') }}{{ env('CASHIER_CURRENCY_LOCALE') ===
                                            'nl_NL' ? '39,99' : '39.99' }}
                                        </div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ __('per quarter') }}
                                        </p>
                                        <div class="mt-auto">
                                            <div :class="{'bg-blue-500': selectedPlan === '{{ env('STRIPE_PRICE_THREE_MONTHLY_ID') }}', 'bg-gray-200 dark:bg-gray-700': selectedPlan !== '{{ env('STRIPE_PRICE_THREE_MONTHLY_ID') }}'}"
                                                class="w-5 h-5 rounded-full flex items-center justify-center">
                                                <div x-show="selectedPlan === '{{ env('STRIPE_PRICE_THREE_MONTHLY_ID') }}'"
                                                    class="w-2 h-2 bg-white rounded-full"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Yearly Plan Card -->
                                <div @click="selectedPlan = '{{ env('STRIPE_PRICE_YEARLY_ID') }}'"
                                    :class="{'border-2 border-blue-500 bg-blue-50 dark:bg-blue-900/20': selectedPlan === '{{ env('STRIPE_PRICE_YEARLY_ID') }}'}"
                                    class="cursor-pointer rounded-lg p-5 border dark:border-gray-700 hover:shadow-md transition-all duration-200">
                                    <div class="flex flex-col h-full">
                                        <h3 class="text-lg font-semibold mb-2">{{ __('Yearly') }}</h3>
                                        <div class="text-2xl font-bold mb-1">
                                            {{ config('cashier.currency_symbol') }}{{ env('CASHIER_CURRENCY_LOCALE') ===
                                            'nl_NL' ? '129,99' : '129.99' }}
                                        </div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ __('per year') }}
                                        </p>
                                        <div class="mt-auto">
                                            <div :class="{'bg-blue-500': selectedPlan === '{{ env('STRIPE_PRICE_YEARLY_ID') }}', 'bg-gray-200 dark:bg-gray-700': selectedPlan !== '{{ env('STRIPE_PRICE_YEARLY_ID') }}'}"
                                                class="w-5 h-5 rounded-full flex items-center justify-center">
                                                <div x-show="selectedPlan === '{{ env('STRIPE_PRICE_YEARLY_ID') }}'"
                                                    class="w-2 h-2 bg-white rounded-full"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <x-button type="submit" class="w-full">
                                {{ __('Subscribe with Stripe Checkout') }}
                            </x-button>
                        </form>
                    </div>
                    @else


                    <a href="{{ route('billing.portal') }}" class="block w-full">
                        <x-button type="button"
                            class="w-full {{ $subscribed ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-gray-600 hover:bg-gray-700' }}">
                            {{ $subscribed ? __('Manage Your Subscription') : __('Subscribe via Billing Portal') }}
                        </x-button>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>