<?php

namespace App\Livewire;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Url;
use Livewire\Component;
use Mary\Traits\Toast;

class ListUsers extends Component
{
    use Toast;

    #[Url]
    public string $search = '';

    /** @var array{column: string, direction: string} */
    public array $sortBy = ['column' => 'uid', 'direction' => 'asc'];

    /**
     * Table headers
     *
     * @return array<int, array{key: string, label: string, class?: string, sortable?: bool}>
     */
    public function headers(): array
    {
        return [
            ['key' => 'uid', 'label' => 'User', 'class' => 'w-64'],
            ['key' => 'name', 'label' => 'Title', 'class' => 'w-48'],
            ['key' => 'email', 'label' => 'Title', 'class' => 'w-36'],
        ];
    }

    /**
     * Get users
     *
     * @return Collection<int, User>
     */
    public function users(): Collection
    {
        $users = null;

        $user = auth()->user();
        if (!$user) {
            return collect();
        }

        $users = User::orderBy($this->sortBy['column'], $this->sortBy['direction'])
                    ->when($this->search, function ($query) {
                        return $query->where('name', 'like', "%{$this->search}%")
                            ->orWhere('email', 'like', "%{$this->search}%");
                    })
                    ->get();

        return $users;
    }

    /**
     * Delete user handler
     *
     * @param string $uid
     * @return void
     */
    public function delete(string $uid): void
    {
        $auth = auth()->user();
        if (!$auth) {
            throw new \Exception('Unauthenticated', 401);
        }

        if ($auth->role !== UserRole::ADMIN) {
            throw new \Exception('Forbidden', 403);
        }

        $user = User::where('uid', $uid)->firstOrFail();
        if ($user->id === $auth->id) {
            throw new \Exception('Can not delete yourself', 403);
        }

        $user->delete();

        $this->success('User deleted successfully!');
    }

    /**
     * Exception hook
     *
     * @param \Exception $e
     * @param mixed $stopPropagation
     */
    public function exception(\Exception $e, mixed $stopPropagation): void
    {
        $this->error($e->getMessage());
        $stopPropagation();
    }

    /**
     * Render the Livewire component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('livewire.list-users', [
            'users' => $this->users(),
            'headers' => $this->headers(),
        ]);
    }
}
