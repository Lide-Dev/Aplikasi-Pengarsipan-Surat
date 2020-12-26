<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SuratMasuk extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'suratmasuk';
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function scopeConfigTable($query, $request)
    {
        $sort = $request->query('sortColumn');
        $search = $request->query('search');
        if (!empty($sort)) {
            // dd($column);
            $sort = Str::snake($sort);
            $query->orderBy($sort, $request->query('sortBy'));
        }
        if (!empty($search)) {
            $column_search = ['no_surat', 'asal_surat'];
            foreach ($column_search as $key => $value) {
                $query->orWhere($value, 'like', "%$search%");
            }
        }
        return $query;
    }
}
