<?php

namespace App\Domains\Reservations\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\Auth\Models\User;
use App\Domains\Restaurant\Models\Restaurant;

class RestaurantReview extends Model
{
    protected $table = 'restaurant_reviews';

    protected $fillable = ['user_id', 'restaurant_id', 'rating', 'title', 'body'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
}
