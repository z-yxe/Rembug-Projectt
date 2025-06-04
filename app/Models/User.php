<?php

namespace App\Models;

use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // <-- TAMBAHKAN INI

class User extends Authenticatable
{
    use Notifiable;

    protected $connection = 'mongodb';
    protected $table = 'users';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'profile_image',
        'bio',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    // Relasi untuk mendapatkan daftar pengguna yang mengikuti user ini (followers)
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_follower', 'following_user_id', 'user_id')->withTimestamps();
    }

    // Relasi untuk mendapatkan daftar pengguna yang diikuti oleh user ini (following)
    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_follower', 'user_id', 'following_user_id')->withTimestamps();
    }

    // Metode untuk memeriksa apakah pengguna yang sedang login mengikuti $user lain
    public function isFollowing(User $userToFollow): bool
    {
        // Pastikan user_id yang digunakan adalah ID pengguna yang sedang login
        // Relasi 'following()' sudah merepresentasikan pengguna yang di-follow oleh 'this' user.
        return $this->following()->where('_id', $userToFollow->id)->exists();
    }
}
