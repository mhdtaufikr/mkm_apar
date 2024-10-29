<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AparInformations extends Model
{
    use HasFactory;
    public function checks()
    {
        return $this->hasMany(PmFormHead::class, 'apar_information_id');
    }
}
