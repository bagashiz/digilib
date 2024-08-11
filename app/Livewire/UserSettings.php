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

    public function update(): void
    {
        try {
            $this->form->update();
            $this->success('Account information updated successfully!');
        } catch (\Exception $e) {
            if (!$e instanceof ValidationException) {
                $this->addError('form.email', $e->getMessage());
            }
            $this->form->reset('password');
        }
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
