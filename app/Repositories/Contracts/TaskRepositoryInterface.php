<?php

namespace App\Repositories\Contracts;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

interface TaskRepositoryInterface
{
    public function all(array $filters = []): Collection;
    public function find(int $id): ?Task;
    public function create(array $data): Task;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getByUserId(int $userId, array $filters = []): Collection;
    public function getStats(): array;
}
