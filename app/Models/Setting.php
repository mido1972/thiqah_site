<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'company_name_en',
        'logo_path',
        'phone',
        'whatsapp',
        'email',
        'address',
        'address_en',

        // السوشيال
        'facebook',
        'linkedin',
        'twitter',
        'instagram',
        'youtube',
        'tiktok',

        'website',
        'about_short',
    ];
}
