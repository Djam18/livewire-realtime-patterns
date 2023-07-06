<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\Attributes\Computed;

// Multi-step form wizard — Livewire is perfect for this.
// React pattern: useState for step + separate component per step + stepper UI.
// Livewire pattern: single component, $currentStep property, per-step $rules,
// validate() only the current step's fields before advancing.
//
// LIVEWIRE 3 MIGRATION — Jul 2023
// getProgressPercentProperty() → #[Computed] attribute (LW3 caches computed properties)
// getStepLabelsProperty()      → #[Computed] attribute
// No more magic get*Property() naming — explicit #[Computed] is clearer.

class MultiStepWizard extends Component
{
    public int $currentStep = 1;
    public int $totalSteps  = 3;

    // Step 1: Personal info
    public string $firstName = '';
    public string $lastName  = '';
    public string $email     = '';

    // Step 2: Company info
    public string $companyName = '';
    public string $companySize = '';
    public string $industry    = '';

    // Step 3: Plan selection
    public string $plan    = 'starter';
    public bool $agreeToTerms = false;

    protected array $stepRules = [
        1 => [
            'firstName' => ['required', 'min:2', 'max:50'],
            'lastName'  => ['required', 'min:2', 'max:50'],
            'email'     => ['required', 'email'],
        ],
        2 => [
            'companyName' => ['required', 'min:2', 'max:100'],
            'companySize' => ['required', 'in:1-10,11-50,51-200,201+'],
            'industry'    => ['required', 'min:2', 'max:50'],
        ],
        3 => [
            'plan'         => ['required', 'in:starter,pro,enterprise'],
            'agreeToTerms' => ['accepted'],
        ],
    ];

    public function nextStep(): void
    {
        $this->validate($this->stepRules[$this->currentStep]);

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function submit(): void
    {
        $this->validate($this->stepRules[$this->totalSteps]);

        // In prod: User::create([...]) + plan subscription
        session()->flash('success', "Welcome, {$this->firstName}! Your {$this->plan} plan is ready.");

        $this->reset(['currentStep', 'firstName', 'lastName', 'email',
                      'companyName', 'companySize', 'industry', 'plan', 'agreeToTerms']);
        $this->currentStep = 1;
    }

    // LW3: #[Computed] replaces get*Property() magic naming
    #[Computed]
    public function progressPercent(): int
    {
        return (int) (($this->currentStep - 1) / ($this->totalSteps - 1) * 100);
    }

    #[Computed]
    public function stepLabels(): array
    {
        return ['Personal', 'Company', 'Plan'];
    }

    public function render()
    {
        return view('livewire.multi-step-wizard');
    }
}
