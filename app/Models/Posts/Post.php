<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;
use App\Models\Categories\SubCategory;

class Post extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $fillable = [
        'user_id',
        'post_title',
        'post',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\Users\User');
    }

    public function postComments()
    {
        return $this->hasMany('App\Models\Posts\PostComment');
    }

    //     belongsToMany() の設定
    // 第一引数: 関連するモデル (\App\Models\SubCategory::class)
    // 第二引数: 中間テーブル名 (post_sub_categories)
    // 第三引数: post_id (投稿ID)
    // 第四引数: sub_category_id (サブカテゴリID)
    public function subCategories()
    {
        return $this->belongsToMany(SubCategory::class, 'post_sub_categories', 'post_id', 'sub_category_id'); // リレーションの定義
    }

    // コメント数
    public function commentCounts($post_id)
    {
        return Post::with('postComments')->find($post_id)->postComments();
    }
}
