<?php

namespace App\Livewire;

use App\Livewire\Forms\EditBookForm;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

class EditBook extends Component
{
    use Toast;
    use WithFileUploads;

    public EditBookForm $form;

    /**
    * Update book handler
    *
    * @return void
    */
    public function update(): void
    {
        $userId = auth()->id();
        if (!$userId) {
            abort(403);
        }

        $this->form->update((int) $userId);
        $this->success('Book updated successfully!');
        $this->redirect(route('books.show', [ 'uid' => $this->form->book->uid ]), navigate: true);
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
     * @param string $uid
     * @return void
     */
    public function mount(string $uid): void
    {
        $this->form->fetchBook($uid);
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
        return view('livewire.edit-book', [ 'uid' => $this->form->book->uid ]);
    }
}
