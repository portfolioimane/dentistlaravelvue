<?php
// app/Models/GeneralCustomize.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralCustomize extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    public static function getValue($key)
    {
        $generalCustomize = self::where('key', $key)->first();
        return $generalCustomize ? $generalCustomize->value : null;
    }

    public static function setValue($key, $value)
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
