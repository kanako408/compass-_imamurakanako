<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MainCategory extends Model
{
    use HasFactory; // 追加: Factory使用のため
    const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $fillable = [
        'main_category'
    ];

    public function subCategories()
    {
        // リレーションの定義
        return $this->hasMany(SubCategory::class);
    }
}
