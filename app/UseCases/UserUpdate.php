<?php

namespace App\UseCases;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserUpdate
{
    public function update($validatedData, $userId): void
    {
        $user = User::findOrFail($userId);

        $user->fill($this->setArray($validatedData));

        $this->assignRoleIfPresent($validatedData, $user);

        $user->save();
    }

    protected function setArray($validatedData): array
    {
        return collect($validatedData)
            ->only(['name', 'email'])
            ->when($this->passwordExists($validatedData), function ($collection) use ($validatedData) {
                return $collection->put('password', Hash::make($validatedData['password']));
            })
            ->toArray();
    }

    protected function passwordExists($validatedData): ?bool
    {
        return $validatedData['password'] ?? null;
    }

    protected function assignRoleIfPresent($validatedData, $user): bool
    {
        return optional($validatedData)['role'] && $user->syncRoles($validatedData['role']);
    }
}
