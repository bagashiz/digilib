<?php

namespace App\Livewire;

use App\Livewire\Forms\AddBookForm;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class AddBook extends Component
{
    use Toast;
    use WithFileUploads;

    public AddBookForm $form;

    public function add(): void
    {
        try {
            $userId = auth()->id();
            if (!$userId) {
                abort(403);
            }

            $this->form->create((int) $userId);
            $this->success('Book added successfully!');
            $this->redirect('/', navigate: true);
        } catch (\Exception $e) {
            if (!$e instanceof ValidationException) {
                $this->addError('form.title', $e->getMessage());
            }
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
        return view('livewire.add-book');
    }
}
