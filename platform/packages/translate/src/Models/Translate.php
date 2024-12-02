<?php

namespace Workable\Translate\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translate extends Model
{
    use HasFactory;

    protected $table = 'translates';
    protected $fillable = [
        'key',
        'translation_en',
        'translation_pt',
        'translation_it',
        'translation_es',
    ];

    public function checkFieldable($name): bool
    {
       return in_array($name, $this->getFillable());
    }
}
