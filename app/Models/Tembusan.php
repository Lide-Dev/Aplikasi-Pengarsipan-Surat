<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tembusan extends Model
{
    use HasFactory;

    protected $table = 'tembusan';
    public $timestamps = false;
    protected $guarded =['id'];

}
