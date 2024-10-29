<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PmFormHead extends Model
{
    use HasFactory;
    protected $dates = ['date'];

    // Define the table if not following the Laravel naming convention
    protected $table = 'pm_form_heads';

    // Specify the fillable attributes
    protected $fillable = [
        'apar_information_id',
        'signature',
        'date',
        'pic',
        'img',
    ];

    /**
     * Define the relationship with AparInformations
     * Each PmFormHead belongs to one AparInformations.
     */
    public function aparInformation()
    {
        return $this->belongsTo(AparInformations::class, 'apar_information_id');
    }

    public function details()
    {
        return $this->hasMany(PmFormDetail::class, 'id_header');
    }

}
