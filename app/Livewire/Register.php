<?php

namespace App\Livewire;

use App\Livewire\Forms\RegisterForm;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Mary\Traits\Toast;

class Register extends Component
{
    use Toast;

    public RegisterForm $form;


    /**
     * Register handler
     */
    public function register(): void
    {
        try {
            $this->form->create();
            $this->success('User created successfully! Please login');
            $this->redirect('/login', navigate: true);
        } catch (\Exception $e) {
            if (!$e instanceof ValidationException) {
                $this->addError('form.email', $e->getMessage());
            }
            $this->form->reset('password');
        }
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
        return view('livewire.register');
    }
}
