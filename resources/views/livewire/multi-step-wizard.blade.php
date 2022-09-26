{{--
    Multi-step wizard — Livewire single-component approach.
    Per-step validation: $stepRules[n] passed to validate() before advancing.
    Progress bar: CSS width tied to $progressPercent computed property.
    React comparison: no Context needed, no prop drilling — one server component holds all.
--}}
<div class="max-w-md bg-white rounded-xl shadow overflow-hidden">

    {{-- Progress header --}}
    <div class="px-6 py-4 border-b border-gray-100">
        <div class="flex justify-between text-xs text-gray-500 mb-2">
            @foreach($this->stepLabels as $i => $label)
                <span class="{{ ($i + 1) === $currentStep ? 'font-semibold text-indigo-600' : '' }}">
                    {{ $label }}
                </span>
            @endforeach
        </div>
        <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
            <div
                class="h-full bg-indigo-500 rounded-full transition-all duration-500"
                style="width: {{ $progressPercent }}%"
            ></div>
        </div>
        <p class="text-xs text-gray-400 mt-1.5">Step {{ $currentStep }} of {{ $totalSteps }}</p>
    </div>

    {{-- Flash success --}}
    @if(session('success'))
        <div class="px-6 py-4 bg-green-50 border-b border-green-100 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="px-6 py-6">

        {{-- Step 1: Personal Info --}}
        @if($currentStep === 1)
            <div>
                <h3 class="text-base font-semibold text-gray-900 mb-4">Personal Information</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">First Name</label>
                            <input wire:model.lazy="firstName" type="text" placeholder="Jane"
                                   class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2
                                          focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                          @error('firstName') border-red-400 @enderror">
                            @error('firstName') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Last Name</label>
                            <input wire:model.lazy="lastName" type="text" placeholder="Doe"
                                   class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2
                                          focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                          @error('lastName') border-red-400 @enderror">
                            @error('lastName') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                        <input wire:model.lazy="email" type="email" placeholder="jane@example.com"
                               class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2
                                      focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                      @error('email') border-red-400 @enderror">
                        @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        @endif

        {{-- Step 2: Company Info --}}
        @if($currentStep === 2)
            <div>
                <h3 class="text-base font-semibold text-gray-900 mb-4">Company Details</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Company Name</label>
                        <input wire:model.lazy="companyName" type="text" placeholder="Acme Corp"
                               class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2
                                      focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400
                                      @error('companyName') border-red-400 @enderror">
                        @error('companyName') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Team Size</label>
                            <select wire:model="companySize"
                                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2
                                           focus:ring-2 focus:ring-indigo-400
                                           @error('companySize') border-red-400 @enderror">
                                <option value="">Select…</option>
                                <option value="1-10">1–10</option>
                                <option value="11-50">11–50</option>
                                <option value="51-200">51–200</option>
                                <option value="201+">201+</option>
                            </select>
                            @error('companySize') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Industry</label>
                            <input wire:model.lazy="industry" type="text" placeholder="SaaS"
                                   class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2
                                          focus:ring-2 focus:ring-indigo-400
                                          @error('industry') border-red-400 @enderror">
                            @error('industry') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Step 3: Plan Selection --}}
        @if($currentStep === 3)
            <div>
                <h3 class="text-base font-semibold text-gray-900 mb-4">Choose Your Plan</h3>
                <div class="space-y-3">
                    @foreach(['starter' => ['$29/mo', 'Up to 5 users, core features'], 'pro' => ['$79/mo', 'Up to 25 users, advanced features'], 'enterprise' => ['Custom', 'Unlimited users, dedicated support']] as $value => $details)
                        <label
                            class="flex items-start gap-3 p-3 rounded-lg border cursor-pointer transition-colors
                                   {{ $plan === $value ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:bg-gray-50' }}"
                        >
                            <input wire:model="plan" type="radio" value="{{ $value }}" class="mt-1 text-indigo-600">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900 capitalize">{{ $value }}</p>
                                <p class="text-xs text-gray-500">{{ $details[1] }}</p>
                            </div>
                            <span class="text-sm font-bold text-indigo-600">{{ $details[0] }}</span>
                        </label>
                    @endforeach

                    <label class="flex items-center gap-2 mt-4">
                        <input wire:model="agreeToTerms" type="checkbox" class="text-indigo-600 rounded">
                        <span class="text-xs text-gray-600">
                            I agree to the <a href="#" class="text-indigo-600 hover:underline">Terms of Service</a>
                        </span>
                    </label>
                    @error('agreeToTerms') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        @endif

    </div>

    {{-- Navigation --}}
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-between">
        @if($currentStep > 1)
            <button wire:click="previousStep"
                    class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-white">
                Back
            </button>
        @else
            <div></div>
        @endif

        @if($currentStep < $totalSteps)
            <button wire:click="nextStep"
                    class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Continue
            </button>
        @else
            <button wire:click="submit"
                    class="px-4 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700">
                Complete Setup
            </button>
        @endif
    </div>

</div>
