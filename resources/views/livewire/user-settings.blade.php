<div>
    <x-form wire:submit="update">
        <div class="flex flex-col p-4 space-y-1 text-center">
            <h2 class="whitespace-nowrap tracking-tight text-2xl font-bold">Update your account</h2>
            <p class="text-sm">Update your account information below.</p>
        </div>
        <x-input label="Name" wire:model.blur="form.name" placeholder="Your new name" icon="o-identification" clearable />
        <x-input label="Email" wire:model.blur="form.email" placeholder="Your new email" icon="o-user" type="email"
            clearable />
        <x-input label="Password" wire:model.blur="form.password" icon="o-eye" type="password" clearable />

        <x-slot:actions>
            <x-button label="Back" link="/" />
            <x-button label="Update" class="btn-primary" type="submit" spinner="update" />
        </x-slot:actions>
    </x-form>
</div>
