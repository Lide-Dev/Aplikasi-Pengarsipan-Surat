<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TembusanSuratMasuk extends Model
{
    use HasFactory;

    protected $table = 'tembusan_sm';
    public $timestamps = false;
    protected $guarded = ['id'];
}
