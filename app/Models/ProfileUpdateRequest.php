<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileUpdateRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'requested_changes',
        'status',
        'admin_feedback',
    ];

    protected $casts = [
        'requested_changes' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
