<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $fillable = [
        'car_id', 'assigned_to', 'client_name', 'client_phone', 'client_email',
        'down_payment', 'duration_years', 'interest_rate', 'monthly_installment',
        'total_price', 'notes', 'status', 'source', 'last_contacted_at',
        'city', 'salary_range', 'obligations_range', 'contact_type', 'tax_number',
        'company_name', 'financing_period',
    ];

    protected $casts = [
        'last_contacted_at' => 'datetime',
    ];

    const STATUSES = [
        'new' => ['label' => 'جديد',          'color' => 'primary'],
        'contacted' => ['label' => 'تم التواصل',     'color' => 'info'],
        'interested' => ['label' => 'مهتم',           'color' => 'warning'],
        'rejected' => ['label' => 'مرفوض',          'color' => 'danger'],
        'sold' => ['label' => 'تم البيع ✓',     'color' => 'success'],
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }

    public function notes_list(): HasMany
    {
        return $this->hasMany(BookingNote::class)->latest();
    }

    public function documents(): HasMany
    {
        return $this->hasMany(BookingDocument::class)->latest();
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status]['label'] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUSES[$this->status]['color'] ?? 'secondary';
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeInProgress($query)
    {
        return $query->whereIn('status', ['contacted', 'interested']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'sold');
    }
}
