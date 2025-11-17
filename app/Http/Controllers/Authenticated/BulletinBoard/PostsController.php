<?php

namespace App\Http\Controllers\Authenticated\BulletinBoard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories\MainCategory;
use App\Models\Categories\SubCategory;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\Posts\Like;
use App\Models\Users\User;
use App\Http\Requests\BulletinBoard\PostFormRequest;
use Auth;

class PostsController extends Controller
{
    public function show(Request $request){
        $posts = Post::with('user', 'postComments')
        ->withCount('likes','postComments')
        ->get();
        $categories = MainCategory::get();
        $like = new Like;
        $post_comment = new Post;
        $keyword = $request->keyword;
        if(!empty($request->keyword)){
            $subCategory =SubCategory::where('sub_category', $request->keyword)->first();

        if ($subCategory) {
$posts =$subCategory->posts()->with('user','postComments','likes','subCategories')
// dd($posts);
 ->withCount('likes','postComments')
            // ->where('post_title', 'like', '%'.$request->keyword.'%')
            // ->orWhere('post', 'like', '%'.$request->keyword.'%')
             ->get();
        }else{
            $posts = Post::with('user', 'postComments')
            ->withCount('likes','postComments')
            ->where('post_title', 'like', '%'.$request->keyword.'%')
            ->orWhere('post', 'like', '%'.$request->keyword.'%')->get();}
        }else if($request->category_word){
            $sub_category = $request->category_word;
            $posts = Post::with('user', 'postComments')->get();
        }else if($request->like_posts){
            $likes = Auth::user()->likePostId()->get('like_post_id');
            $posts = Post::with('user', 'postComments')
            ->withCount('likes','postComments')
            ->whereIn('id', $likes)->get();
        }else if($request->my_posts){
            $posts = Post::with('user', 'postComments')
            ->withCount('likes','postComments')
            ->where('user_id', Auth::id())->get();
        }
        return view('authenticated.bulletinboard.posts', compact('posts', 'categories', 'like', 'post_comment'));
    }

    public function postDetail($post_id){
        $post = Post::with('user', 'postComments')->findOrFail($post_id);
        return view('authenticated.bulletinboard.post_detail', compact('post'));
    }

    public function postInput(){
        $main_categories = MainCategory::with('subCategories')->get();
        return view('authenticated.bulletinboard.post_create', compact('main_categories'));
    }

    public function postCreate(PostFormRequest $request){
        $post = Post::create([
            'user_id' => Auth::id(),
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);
        $post->subCategories()->attach($request->post_category_id);
        return redirect()->route('post.show');
    }

    public function postEdit(PostFormRequest $request){
        Post::where('id', $request->post_id)->update([
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function postDelete($id){
        Post::findOrFail($id)->delete();
        return redirect()->route('post.show');
    }
    public function mainCategoryCreate(Request $request){
$request->validate([
        'main_category_name'=>['required','max:100','string','unique:main_categories,main_category'],],[
       'main_category_name.required' => 'メインカテゴリー名は必須項目です。',
        'main_category_name.max'      => 'メインカテゴリー名は100文字以内で入力してください。',
        'main_category_name.string'   => 'メインカテゴリー名は文字列で入力してください。',
        'main_category_name.unique'   => '同じ名前のメインカテゴリーは登録できません。',

    ]);

        MainCategory::create(['main_category' => $request->main_category_name]);
        return redirect()->route('post.input');
    }

    public function commentCreate(Request $request){

        $request->validate([
            'comment' =>['required','string','max:250'],],[

            'comment.required' => 'コメントは必ず入力してください。',
            'comment.max'      => 'コメントは250文字以内で入力してください。',
        ]);
        PostComment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function myBulletinBoard(){
        $posts = Auth::user()->posts()->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_myself', compact('posts', 'like'));
    }

    public function likeBulletinBoard(){
        $like_post_id = Like::with('users')->where('like_user_id', Auth::id())->get('like_post_id')->toArray();
        $posts = Post::with('user')->whereIn('id', $like_post_id)->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_like', compact('posts', 'like'));
    }

    public function postLike(Request $request){
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->like_user_id = $user_id;
        $like->like_post_id = $post_id;
        $like->save();

        return response()->json();
    }

    public function postUnLike(Request $request){
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->where('like_user_id', $user_id)
             ->where('like_post_id', $post_id)
             ->delete();

        return response()->json();
    }


 public function subCategoryCreate(Request $request)
{
    $request->validate([
        'sub_category_name'=>['required','max:100','string','unique:sub_categories,sub_category',],
    ],[
        'sub_category_name.required' => 'サブカテゴリー名は必須項目です。',
        'sub_category_name.max'      => 'サブカテゴリー名は100文字以内で入力してください。',
        'sub_category_name.string'   => 'サブカテゴリー名は文字列で入力してください。',
        'sub_category_name.unique'   => '同じ名前のサブカテゴリーは登録できません。',

    ]);
    SubCategory::create([
        'sub_category' => $request->sub_category_name,
'main_category_id' =>$request->main_category_id,
    ]);
    return redirect()->route('post.input');
}

// public function searchSubCategory(Request $request){
//     $keyword = $request->input('keyword');

//     $subs = SubCategory::where('sub_category', $keyword)
//     ->with('mainCategory')
//     ->get();

//     $grouped = $subs->groupBy(fn($sub) => $sub->mainCategory->name);

//     return view('posts.search', compact('grouped','keyword'));
// }

}
