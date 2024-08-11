<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Illuminate\Database\QueryException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class RegisterForm extends Form
{
    #[Validate('required')]
    public string $name = '';

    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|min:8')]
    public string $password = '';

    /**
    * Create a new user
     *
     * @throws \Exception
     * @return void
     */
    public function create(): void
    {
        try {
            $validated = $this->validate();
            $validated['password'] = bcrypt($validated['password']);
            User::create($validated);
        } catch (QueryException $e) {
            if (is_array($e->errorInfo) && count($e->errorInfo) > 1) {
                $errorCode = $e->errorInfo[1];
                switch ($errorCode) {
                    case 1062:
                        throw new \Exception('Email already exists');
                    default:
                        throw new \Exception('An error occurred while creating the user');
                }
            }
        }
    }
}
