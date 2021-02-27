<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;
    
    protected $appends = ['entry_date'];
    public function getEntryDateAttribute()
    {
        return $this->attributes['entry_date'] =  Carbon::parse($this->created_at)->format('m/d/Y');;
    }

    public function educations()
    {
        return $this->hasMany(Education::class);
    }
    
    public function trainings()
    {
        return $this->hasMany(Training::class);
    }
}
