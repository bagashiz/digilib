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
     *
     * @return void
     */
    public function login(): void
    {
        $this->form->authenticate();
        $this->success('Login successful! Welcome back!');
        $this->redirect('/', navigate: true);
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
