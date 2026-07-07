<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FormTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'slug',
        'share_token',
        'is_active',
        'seminar_id',
        'fields_json',
        'created_by',
    ];

    protected $casts = [
        'fields_json' => 'array',
        'is_active'   => 'boolean',
    ];

    // Auto-generate token before creating
    protected static function booted(): void
    {
        static::creating(function (FormTemplate $form) {
            if (empty($form->share_token)) {
                $form->share_token = Str::random(12);
            }
            if (empty($form->slug)) {
                $form->slug = Str::slug($form->title) . '-' . Str::random(5);
            }
        });
    }

    public function seminar()
    {
        return $this->belongsTo(Seminar::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function submissions()
    {
        return $this->hasMany(FormSubmission::class);
    }

    public function getShareUrlAttribute(): string
    {
        return route('public.form.show', $this->share_token);
    }
}
