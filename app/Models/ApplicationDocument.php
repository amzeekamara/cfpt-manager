<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'resource_type',
        'file_path',
        'original_filename',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
