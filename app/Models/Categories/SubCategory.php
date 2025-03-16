<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubCategory extends Model
{
    use HasFactory; // 追加: Factory使用のため
    const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $fillable = [
        'main_category_id',
        'sub_category',
    ];

    // MainCategoryとのリレーション
    public function mainCategory()
    {
        // リレーションの定義
        return $this->belongsTo(MainCategory::class);
    }

    public function posts()
    {
        // リレーションの定義
    }
}
