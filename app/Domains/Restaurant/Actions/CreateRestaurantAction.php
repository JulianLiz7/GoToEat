<?php

namespace App\Domains\Restaurant\Actions;

use App\Domains\Auth\Models\User;
use App\Domains\Restaurant\Models\Restaurant;
use Illuminate\Support\Str;

class CreateRestaurantAction
{
    /**
     * Create a new restaurant and assign the user as the owner.
     */
    public function execute(User $user, array $data): Restaurant
    {
        $restaurant = Restaurant::create([
            'owner_id' => $user->id,
            'name' => $data['name'],
            'slug' => Str::slug($data['name']) . '-' . uniqid(),
            'description' => $data['description'] ?? null,
            'category' => $data['category'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'status' => 'active',
        ]);

        // Automatically add the owner to the restaurant_user pivot table as 'owner'
        $restaurant->users()->attach($user->id, [
            'role' => 'owner',
            'status' => 'active',
            'joined_at' => now(),
        ]);

        return $restaurant;
    }
}
