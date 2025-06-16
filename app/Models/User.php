<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public $timestamps = false;

    // Explicitly define the table (optional, for clarity)
    protected $table = 'users';
    protected $primaryKey = 'id';         // ✅ ensures ID is the primary key
    protected $keyType = 'int';           // ✅ ensures it's treated as an integer
    // public $incrementing = true;  

    // Fillable fields in your custom table
    protected $fillable = [
        'username',
        'password',
        'role',
    ];

    // Hide sensitive data
    protected $hidden = [
        'password',
    ];

    // Casts (if needed)
    protected function casts(): array
    {
        return [
            'password' => 'hashed', // Use this only if passwords are hashed using Laravel's hasher (bcrypt)
        ];
    }

    /**
     * Override to use 'username' instead of 'email' for login
     */
    // public function getAuthIdentifierName()
    // {
    //     return 'username';
    // }

    // public function getAuthIdentifier()
    // {
    //     return $this->username;
    //     // return $this->id;
    // }
}
