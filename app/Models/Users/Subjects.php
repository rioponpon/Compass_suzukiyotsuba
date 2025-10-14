<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Users\User;

class Subjects extends Model
{

    use HasFactory;
    const UPDATED_AT = null;


    protected $fillable = [
        'subject',
        'user_id'
    ];

    public function users(){
        return $this->belongsToMany(User::class,'subject_user','subject_id','user_id');// リレーションの定義
    }
}
