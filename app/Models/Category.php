<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    use HasFactory;
   

    protected $fillable = ['name'];

    public function scopeSearchByName($query, $searchTerm)
    {
        if (!empty($searchTerm)) {
            return $query->where('name', 'like', '%' . $searchTerm . '%');
        }

        return $query;
    }
}
