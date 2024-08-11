<?php

namespace App\Livewire;

use App\Livewire\Forms\LoginForm;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Mary\Traits\Toast;

class Login extends Component
{
    use Toast;

    public LoginForm $form;

    /**
     * Login handler
     */
    public function login(): void
    {
        try {
            $this->form->authenticate();
            $this->success('Login successful! Welcome back!');
            $this->redirect('/', navigate: true);
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
        return view('livewire.login');
    }
}
