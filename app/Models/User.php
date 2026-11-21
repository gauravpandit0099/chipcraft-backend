<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable {
    use HasApiTokens, Notifiable;
    /* mass assignable user attributes */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
    ];

    /* hidden attributes for json */
    protected $hidden = [
        'password',
    ];

    /* attribute type casting */
    protected function casts(): array {
        return [
            'password' => 'hashed',
        ];
    }
}