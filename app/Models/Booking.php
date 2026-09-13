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
        'total_price', 'final_price', 'commission', 'notes', 'status', 'source', 'last_contacted_at',
        'city', 'salary_range', 'obligations_range', 'contact_type', 'tax_number',
        'company_name', 'financing_period', 'num_cars',
        'utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'click_id', 'referrer_url',
    ];

    protected $casts = [
        'last_contacted_at' => 'datetime',
        'final_price' => 'decimal:2',
        'commission' => 'decimal:2',
        'interest_rate' => 'decimal:2',
    ];

    const SOURCES = [
        'facebook' => [
            'label' => 'فيسبوك',
            'badge_bg' => '#EBF3FF',
            'badge_text' => '#1877F2',
            'badge_border' => '#C4DCFF',
            'icon' => 'bi bi-facebook',
        ],
        'google' => [
            'label' => 'جوجل',
            'badge_bg' => '#E6F4EA',
            'badge_text' => '#137333',
            'badge_border' => '#CEEAD6',
            'icon' => 'bi bi-google',
        ],
        'snapchat' => [
            'label' => 'سناب شات',
            'badge_bg' => '#FFFDE6',
            'badge_text' => '#8D7700',
            'badge_border' => '#FFF176',
            'icon' => 'bi bi-snapchat',
        ],
        'tiktok' => [
            'label' => 'تيك توك',
            'badge_bg' => '#F1F1F2',
            'badge_text' => '#000000',
            'badge_border' => '#CCCCCC',
            'icon' => 'bi bi-tiktok',
        ],
        'instagram' => [
            'label' => 'إنستغرام',
            'badge_bg' => '#FCE8F3',
            'badge_text' => '#D81B60',
            'badge_border' => '#F8BBD0',
            'icon' => 'bi bi-instagram',
        ],
        'haraj' => [
            'label' => 'حراج',
            'badge_bg' => '#E8F5E9',
            'badge_text' => '#2E7D32',
            'badge_border' => '#C8E6C9',
            'icon' => 'bi bi-tag',
        ],
        'twitter' => [
            'label' => 'إكس / تويتر',
            'badge_bg' => '#F3F4F6',
            'badge_text' => '#111827',
            'badge_border' => '#E5E7EB',
            'icon' => 'bi bi-twitter-x',
        ],
        'internal' => [
            'label' => 'داخلي (CRM)',
            'badge_bg' => '#F4EFF0',
            'badge_text' => '#1a3163',
            'badge_border' => '#D5D5D5',
            'icon' => 'bi bi-building',
        ],
        'website' => [
            'label' => 'مباشر / الموقع',
            'badge_bg' => '#F0F4F8',
            'badge_text' => '#475569',
            'badge_border' => '#CBD5E1',
            'icon' => 'bi bi-globe2',
        ],
    ];

    const STATUSES = [
        'new' => ['label' => 'جديد', 'color' => 'primary'],
        'contacted' => ['label' => 'تم التواصل', 'color' => 'info'],
        'interested' => ['label' => 'مهتم', 'color' => 'warning'],
        'pending_closure' => ['label' => 'في انتظار مراجعة الأدمن', 'color' => 'secondary'],
        'closed' => ['label' => 'مغلق', 'color' => 'dark'],
        'sold' => ['label' => 'تم الاستلام ✓', 'color' => 'success'],
        'rejected' => ['label' => 'مرفوض', 'color' => 'danger'],
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

    public function getNormalizedSourceAttribute(): string
    {
        $raw = strtolower(trim((string) $this->source));
        $utm = strtolower(trim((string) $this->utm_source));
        $click = strtolower(trim((string) $this->click_id));
        $ref = strtolower(trim((string) $this->referrer_url));

        // Direct exact match
        if (isset(self::SOURCES[$raw])) {
            return $raw;
        }

        // Snapchat
        if (str_contains($raw, 'snap') || str_contains($utm, 'snap') || str_contains($ref, 'snapchat.com')) {
            return 'snapchat';
        }

        // Instagram
        if (str_contains($raw, 'instagram') || str_contains($raw, 'ig') || str_contains($utm, 'instagram') || str_contains($utm, 'ig') || str_contains($ref, 'instagram.com')) {
            return 'instagram';
        }

        // Facebook
        if (str_contains($raw, 'facebook') || str_contains($raw, 'fb') || str_contains($utm, 'facebook') || str_contains($utm, 'fb') || str_contains($ref, 'facebook.com') || str_contains($ref, 'fb.com')) {
            return 'facebook';
        }

        // Google
        if (str_contains($raw, 'google') || str_contains($utm, 'google') || str_contains($ref, 'google.')) {
            return 'google';
        }

        // TikTok
        if (str_contains($raw, 'tiktok') || str_contains($utm, 'tiktok') || str_contains($ref, 'tiktok.com')) {
            return 'tiktok';
        }

        // Twitter / X
        if (str_contains($raw, 'twitter') || str_contains($raw, 'x.com') || str_contains($utm, 'twitter') || str_contains($utm, 'x.com') || str_contains($ref, 'twitter.com') || str_contains($ref, 't.co')) {
            return 'twitter';
        }

        // Haraj
        if (str_contains($raw, 'haraj') || str_contains($raw, 'حراج') || str_contains($utm, 'haraj') || str_contains($ref, 'haraj.com.sa')) {
            return 'haraj';
        }

        // Internal / CRM
        if (str_contains($raw, 'internal') || str_contains($raw, 'crm') || str_contains($raw, 'يدوي') || str_contains($raw, 'داخلي')) {
            return 'internal';
        }

        return 'website';
    }

    public function getSourceMetaAttribute(): array
    {
        $key = $this->normalized_source;
        return self::SOURCES[$key] ?? self::SOURCES['website'];
    }

    public function getSourceLabelAttribute(): string
    {
        return $this->source_meta['label'] ?? 'مباشر';
    }

    public function getSourceIconAttribute(): string
    {
        return $this->source_meta['icon'] ?? 'bi bi-globe2';
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'sold');
    }
}

