<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'label',
        'description',
    ];

    /**
     * Get value with proper type casting.
     */
    public function getValueAttribute($value)
    {
        switch ($this->type) {
            case 'boolean':
                return (bool) $value;
            case 'integer':
                return (int) $value;
            case 'json':
                return json_decode($value, true);
            case 'encrypted_string':
                try {
                    return $value ? \Illuminate\Support\Facades\Crypt::decryptString($value) : $value;
                } catch (\Exception $e) {
                    return $value; // If it fails to decrypt, it might be raw (e.g., during transition)
                }
            default:
                return $value;
        }
    }

    /**
     * Set value with proper type casting.
     */
    public function setValueAttribute($value)
    {
        if ($this->type === 'encrypted_string' && $value) {
            // Only encrypt if it's not already encrypted (crude check, but we usually pass raw strings here)
            // If the user submits stars/mask, don't re-save it
            if (str_repeat('*', strlen($value)) === $value || strpos($value, 'eyJpdiI6') === 0) {
                 $this->attributes['value'] = $value;
            } else {
                 $this->attributes['value'] = \Illuminate\Support\Facades\Crypt::encryptString($value);
            }
        } elseif ($this->type === 'json') {
            $this->attributes['value'] = is_string($value) ? $value : json_encode($value);
        } else {
            $this->attributes['value'] = $value;
        }
    }
}
