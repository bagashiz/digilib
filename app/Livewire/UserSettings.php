<?php

namespace App\Livewire;

use App\Livewire\Forms\UserForm;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Mary\Traits\Toast;

class UserSettings extends Component
{
    use Toast;

    public UserForm $form;

    /**
     * Update user handler
     *
     * @return void
     */
    public function update(): void
    {
        $this->form->update();
        $this->success('Account information updated successfully!');
    }

    /**
     * Exception hook
     *
     * @param \Exception $e
     * @param mixed $stopPropagation
     */
    public function exception(\Exception $e, mixed $stopPropagation): void
    {
        if (!$e instanceof ValidationException) {
            $this->addError('form.email', $e->getMessage());
        }
        $this->form->reset('password');
        $stopPropagation();
    }

    /**
     * Runs at the beginning of the first initial request
     *
     * @return void
     */
    public function mount(): void
    {
        $user = auth()->user();
        $this->form->name = $user->name ?? '';
        $this->form->email = $user->email ?? '';
    }

    /**
     * Runs at the beginning of every "subsequent" request
     *
     * @return void
     */
    public function hydrate(): void
    {
        $this->resetValidation();
    }

    /**
     * Render the Livewire component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('livewire.user-settings');
    }
}
