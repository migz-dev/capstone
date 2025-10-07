<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = 'chartings_patients';
    protected $fillable = ['hospital_no','full_name','sex','dob','notes'];
    protected $casts = ['dob' => 'date'];
}
