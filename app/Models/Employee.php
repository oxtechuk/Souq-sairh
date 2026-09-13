<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Employee extends Authenticatable
{
    use HasRoles, Notifiable;

    protected $guard_name = 'web';

    protected $fillable = ['name', 'username', 'email', 'password', 'phone', 'role', 'is_active', 'can_receive_orders', 'avatar'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_active' => 'boolean',
        'can_receive_orders' => 'boolean',
        'password' => 'hashed',
    ];

    public function scopeReceivingOrders($query)
    {
        return $query->where('is_active', true)->where('can_receive_orders', true);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'assigned_to');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(BookingNote::class);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin') || $this->role === 'admin';
    }
}
