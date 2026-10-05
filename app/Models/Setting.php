<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Récupérer une valeur de configuration.
     */
    public static function getValue(
        string $key,
        mixed $default = null
    ): mixed {
        $setting = static::query()
            ->where('key', $key)
            ->first();

        return $setting?->value ?? $default;
    }

    /**
     * Enregistrer ou modifier une configuration.
     */
    public static function setValue(
        string $key,
        mixed $value
    ): void {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => (string) $value]
        );
    }

    /**
     * Récupérer plusieurs paramètres.
     */
    public static function getValues(array $keys): array
    {
        return static::query()
            ->whereIn('key', $keys)
            ->pluck('value', 'key')
            ->toArray();
    }
}