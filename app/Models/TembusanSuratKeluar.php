<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TembusanSuratKeluar extends Model
{
    use HasFactory;

    protected $table = 'tembusan_sk';
    public $timestamps = false;
    protected $guarded =['id'];

}
