<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $fillable = [
        'main_category'
    ];

    public function subCategories(){
        // リレーションの定義
        return $this->hasMany(SubCategory::class,'main_category_id');
    }

     public function mainCategoryCreate(Request $request)
{
    $request->validate([
        'main_category_name'=>['required','max:100','string','unique:main_categories,main_category']
    ]);
    SubCategory::create([
        'main_category_id' => $request->main_category_id,

    ]);
    return redirect()->back();

}
}
