<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;

class Subjects extends Model
{
    const UPDATED_AT = null;


    protected $fillable = [
        'subject'
    ];

    public function users()
    {
        // 'user_id'がSubjectsテーブルの外部キー, 'id'がUsersテーブルの主キー
        return $this->belongsToMany(User::class, 'subject_user', 'subject_id', 'user_id'); // リレーションの定義
    }
}
