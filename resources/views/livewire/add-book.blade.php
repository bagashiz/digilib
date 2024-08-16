<div>
    <x-form wire:submit="add">
        <div class="flex flex-col p-4 space-y-1 text-center">
            <h2 class="whitespace-nowrap tracking-tight text-2xl font-bold">Add your book</h2>
            <p class="text-sm">Add a new book to your collection.</p>
        </div>

        <x-input label="Title" wire:model.blur="form.title" placeholder="Book title" icon="o-book-open" clearable />
        <x-input label="Author" wire:model.blur="form.author" placeholder="Book author" icon="o-user" clearable />
        <x-textarea label="Description" wire:model.blur="form.description" placeholder="Book description"
            icon="o-document-text" rows="5" clearable />
        <x-choices-offline label="Categories" wire:model="form.categoryIds" :options="$form->categories" searchable />
        <x-file wire:model.blur="form.coverImage" label="Cover" hint="Only JPG (Optional)" accept="image/jpeg" />
        <x-file wire:model.blur="form.pdfFile" label="PDF" hint="Only PDF" accept="application/pdf" />

        <x-slot:actions>
            <x-button label="Cancel" link="/" />
            <x-button label="Add" class="btn-primary" type="submit" spinner="add" />
        </x-slot:actions>
    </x-form>
</div>
