<div>
    <x-form wire:submit="login">
        <div class="flex flex-col p-4 space-y-1 text-center">
            <h2 class="whitespace-nowrap tracking-tight text-2xl font-bold">Login to your account</h2>
            <p class="text-sm">Enter your credentials to access your account.</p>
        </div>

        <x-input label="Email" wire:model.blur="form.email" placeholder="test@example.com" icon="o-user" type="email"
            clearable />
        <x-input label="Password" wire:model.blur="form.password" icon="o-eye" type="password" clearable />

        <div class="flex justify-center">
            <p class="text-sm">
                Don't have an account?
                <a href="/register" wire:navigate class="text-sm text-primary hover:text-accent hover:underline">
                    <b>Register</b>
                </a>
            </p>
        </div>
        <x-slot:actions>
            <x-button label="Login" class="btn-primary" type="submit" spinner="login" />
        </x-slot:actions>
    </x-form>
</div>
