<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static function get(string $key, $default = null, $companyId = null)
    {
        $query = static::where('key', $key);
        if ($companyId) {
            $query->where('company_id', $companyId);
        }
        return $query->value('value') ?? $default;
    }
}

