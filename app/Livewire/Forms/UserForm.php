<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Illuminate\Database\QueryException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UserForm extends Form
{
    #[Validate('nullable')]
    public string $name = '';

    #[Validate('nullable|email')]
    public string $email = '';

    #[Validate('nullable|min:8')]
    public string $password = '';

    public function update(): void
    {
        try {
            $validated = $this->validate();

            foreach ($validated as $key => $value) {
                if ($value === '') {
                    unset($validated[$key]);
                }
            }

            if (!empty($validated['password'])) {
                $validated['password'] = bcrypt($validated['password']);
            }

            $user = User::find(auth()->id());
            if (!$user) {
                throw new \Exception('User not found');
            }

            $user->fill($validated);
            $user->save();
        } catch (QueryException $e) {
            if (is_array($e->errorInfo) && count($e->errorInfo) > 1) {
                $errorCode = $e->errorInfo[1];
                switch ($errorCode) {
                    case 1062:
                        throw new \Exception('Email already taken');
                    default:
                        throw new \Exception('An error occurred while updating your account information');
                }
            }
        }
    }
}
