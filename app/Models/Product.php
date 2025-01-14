<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'detail',
        'price',
        'quantity',
        'category_id',
        'image',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    /**
     * Scope tìm kiếm theo tên sản phẩm
     */
    public function scopeSearchByName($query, $searchTerm)
    {
        if (!empty($searchTerm)) {
            return $query->where('name', 'like', '%' . $searchTerm . '%');
        }

        return $query;
    }

    /**
     * Scope tìm kiếm theo giá sản phẩm
     */
    public function scopeSearchByPrice($query, $minPrice, $maxPrice)
    {
        if ($minPrice && $maxPrice) {
            return $query->whereBetween('price', [$minPrice, $maxPrice]);
        }

        return $query;
    }

    /**
     * Scope tìm kiếm theo tên danh mục (sử dụng whereHas)
     */
    public function scopeSearchByCategory($query, $categoryName)
    {
        if (!empty($categoryName)) {
            return $query->whereHas('category', function ($query) use ($categoryName) {
                $query->where('name', 'like',  $categoryName );
            });
        }

        return $query;
    }
}
