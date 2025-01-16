<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    public const PERMISSIONS = [
        'admin' => [
            'view.films',
            'view.people',
            'view.locations',
            'view.species',
            'view.vehicles',
            'view.users',
            'view.user',
            'store.user',
            'update.user',
            'delete.user',
        ],
        'films' => [
            'view.films',
            'view.user',
            'update.user',
            'delete.user',
        ],
        'people' => [
            'view.people',
            'view.user',
            'update.user',
            'delete.user',
        ],
        'locations' => [
            'view.locations',
            'view.user',
            'update.user',
            'delete.user',
        ],
        'species' => [
            'view.species',
            'view.user',
            'update.user',
            'delete.user',
        ],
        'vehicles' => [
            'view.vehicles',
            'view.user',
            'update.user',
            'delete.user',
        ],
    ];

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $validDomains = ['gmail.com', 'yahoo.com', 'outlook.com', 'hotmail.com'];

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->userName . '@' . fake()->randomElement($validDomains),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin()
    {
        return $this->state(fn ($attributes) => [
            'email' => fake()->unique()->userName . '@gmail.com',
        ])->afterCreating(function (User $user) {
            $this->executeInTransaction(function () use ($user) {
                $this->createPermissions('admin');
                $this->createRole('admin');
                $this->assignPermissionsToRole('admin');
                $this->assignRoleToUser($user, 'admin');
            });
        });
    }

    public function films()
    {
        return $this->state(fn ($attributes) => [
            'email' => fake()->unique()->userName . '@gmail.com',
        ])->afterCreating(function (User $user) {
            $this->executeInTransaction(function () use ($user) {
                $this->createPermissions('films');
                $this->createRole('films');
                $this->assignPermissionsToRole('films');
                $this->assignRoleToUser($user, 'films');
            });
        });
    }

    public function people()
    {
        return $this->state(fn ($attributes) => [
            'email' => fake()->unique()->userName . '@gmail.com',
        ])->afterCreating(function (User $user) {
            $this->executeInTransaction(function () use ($user) {
                $this->createPermissions('people');
                $this->createRole('people');
                $this->assignPermissionsToRole('people');
                $this->assignRoleToUser($user, 'people');
            });
        });
    }

    public function locations()
    {
        return $this->state(fn ($attributes) => [
            'email' => fake()->unique()->userName . '@gmail.com',
        ])->afterCreating(function (User $user) {
            $this->executeInTransaction(function () use ($user) {
                $this->createPermissions('locations');
                $this->createRole('locations');
                $this->assignPermissionsToRole('locations');
                $this->assignRoleToUser($user, 'locations');
            });
        });
    }

    public function species()
    {
        return $this->state(fn ($attributes) => [
            'email' => fake()->unique()->userName . '@gmail.com',
        ])->afterCreating(function (User $user) {
            $this->executeInTransaction(function () use ($user) {
                $this->createPermissions('species');
                $this->createRole('species');
                $this->assignPermissionsToRole('species');
                $this->assignRoleToUser($user, 'species');
            });
        });
    }

    public function vehicles()
    {
        return $this->state(fn ($attributes) => [
            'email' => fake()->unique()->userName . '@gmail.com',
        ])->afterCreating(function (User $user) {
            $this->executeInTransaction(function () use ($user) {
                $this->createPermissions('vehicles');
                $this->createRole('vehicles');
                $this->assignPermissionsToRole('vehicles');
                $this->assignRoleToUser($user, 'vehicles');
            });
        });
    }

    private function createPermissions(string $type): void
    {
        collect(self::PERMISSIONS[$type])->each(function ($permission) {
            Permission::firstOrCreate(['name' => $permission]);
        });
    }

    private function createRole(string $type): void
    {
        Role::firstOrCreate(['name' => $type]);
    }

    private function assignPermissionsToRole(string $type): void
    {
        $adminRole = Role::where('name', $type)->firstOrFail();
        $adminRole->givePermissionTo(self::PERMISSIONS[$type]);
    }

    private function assignRoleToUser(User $user, string $rol): void
    {
        $user->assignRole($rol);
    }

    private function executeInTransaction(callable $callback): void
    {
        DB::transaction($callback);
    }
}
