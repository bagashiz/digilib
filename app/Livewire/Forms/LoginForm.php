<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|min:8')]
    public string $password = '';

    /**
     * Authenticate the user
     *
     * @throws \Exception
     * @return void
     */
    public function authenticate(): void
    {
        $validated = $this->validate();
        if (!auth()->attempt($validated)) {
            throw new \Exception('Incorrect email or password');
        }
    }
}
