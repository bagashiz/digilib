<div>
    <x-form wire:submit="register">
        <div class="flex flex-col p-4 space-y-1 text-center">
            <h2 class="whitespace-nowrap tracking-tight text-2xl font-bold">Create an account</h2>
            <p class="text-sm">Get started by creating a new account.</p>
        </div>

        <x-input label="Name" wire:model.blur="form.name" placeholder="John Doe" icon="o-identification" clearable />
        <x-input label="Email" wire:model.blur="form.email" placeholder="test@example.com" icon="o-user" type="email"
            clearable />
        <x-input label="Password" wire:model.blur="form.password" icon="o-eye" type="password" clearable />

        <div class="flex justify-center">
            <p class="text-sm">
                Already have an account?
                <a href="/login" wire:navigate class="text-sm text-primary-content hover:text-accent hover:underline">
                    <b>Login</b>
                </a>
            </p>
        </div>

        <x-slot:actions>
            <x-button label="Register" class="btn-primary" type="submit" spinner="register" />
        </x-slot:actions>
    </x-form>
</div>
