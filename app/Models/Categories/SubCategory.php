<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $fillable = [
        'main_category_id',
        'sub_category',
    ];
    public function mainCategory(){
        // リレーションの定義
        return $this->belongsTo(MainCategory::class,'main_category');
    }

    public function posts(){
        // リレーションの定義
        return $this->hasMany('App\Models\Posts\Post','sub_category_id');
    }

    public function subCategoryCreate(Request $request)
{
    $request->validate([
        'sub_category_name'=>['required','max:100','string','unique:sub_categories,sub_category']
    ]);
    SubCategory::create([
        'sub_category_id' => $request->sub_category_id,

    ]);
    return redirect()->back();
}
}
