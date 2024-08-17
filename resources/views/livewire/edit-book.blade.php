<div>
    <x-form wire:submit="update">
        <div class="flex flex-col p-4 space-y-1 text-center">
            <h2 class="whitespace-nowrap tracking-tight text-2xl font-bold">Update your book</h2>
            <p class="text-sm">Update your book details.</p>
        </div>

        <x-input label="Title" wire:model.blur="form.title" placeholder="Book title" icon="o-book-open" clearable />
        <x-input label="Author" wire:model.blur="form.author" placeholder="Book author" icon="o-user" clearable />
        <x-textarea label="Description" wire:model.blur="form.description" placeholder="Book description"
            icon="o-document-text" rows="5" clearable />
        <x-choices-offline label="Categories" wire:model="form.categoryIds" :options="$form->categories" searchable />
        <div class="space-y-2">
            <x-file wire:model.blur="form.coverImage" label="Cover" hint="Only JPG (Optional)" accept="image/jpeg" />
            @if ($form->book->cover_image)
                <a href="{{ asset($form->book->cover_image) }}" target="_blank"
                    class="text-sm text-primary hover:text-accent">
                    Current
                </a>
                <x-checkbox label="Remove Cover" wire:model="form.isCoverRemoved" left />
            @endif
        </div>
        <div class="space-y-2">
            <x-file wire:model.blur="form.pdfFile" label="PDF" hint="Only PDF" accept="application/pdf" />
            @if ($form->book->pdf_file)
                <a href="{{ asset($form->book->pdf_file) }}" target="_blank"
                    class="text-sm text-primary hover:text-accent">
                    Current
                </a>
            @endif
        </div>

        <x-slot:actions>
            <x-button label="Cancel" link="{{ route('books.show', $form->book->uid) }}" />
            <x-button label="Update" class="btn-primary" type="submit" spinner="update" />
        </x-slot:actions>
    </x-form>
</div>
