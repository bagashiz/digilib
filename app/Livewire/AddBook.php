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

    /**
    * Add book handler
    *
    * @return void
    */
    public function add(): void
    {
        $userId = auth()->id();
        if (!$userId) {
            abort(403);
        }

        $this->form->create((int) $userId);
        $this->success('Book added successfully!');
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
            $this->addError('form.title', $e->getMessage());
        }
        $stopPropagation();
    }

    /**
     * Runs at the beginning of the first initial request
     *
     * @return void
     */
    public function mount(): void
    {
        $this->form->fetchAllCategories();
    }

    /**
    }
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
