<x-sidebar>
<div class="board_area w-100 border m-auto d-flex">
  <div class="post_view w-75 mt-5">
    @if(request()->has('keyword')&& request('keyword') !== '')
    <p class="w-75 m-auto">検索結果:{{ request('keyword') }}</p>
    @else
<p class="w-75 m-auto">投稿一覧</p>
@endif
    @forelse($posts as $post)
    <div class="post_area border w-75 m-auto p-3">
      <p><span>{{ $post->user->over_name }}</span><span class="ml-3">{{ $post->user->under_name }}</span>さん</p>

      <p><a href="{{ route('post.detail', ['id' => $post->id]) }}">{{ $post->post_title }}</a></p>
      <div class="post_bottom_area d-flex">
        <div class="d-flex post_status">
          <div class="mr-5">
            <i class="fa fa-comment"></i><span class="">{{ $post->post_comments_count}}</span>
          </div>
          <div>
            @if(Auth::user()->is_Like($post->id))
            <p class="m-0"><i class="fas fa-heart un_like_btn" post_id="{{ $post->id }}"></i><span class="like_counts{{ $post->id }}">{{ $post->likes_count }}</span></p>
            @else
            <p class="m-0"><i class="fas fa-heart like_btn" post_id="{{ $post->id }}"></i><span class="like_counts{{ $post->id }}">{{ $post->likes_count }}</span></p>
            @endif
          </div>
        </div>
      </div>
    </div>
    @empty
   <p class="w-75 m-auto">投稿が見つかりません。</p>
    @endforelse
  </div>
  <div class="other_area border w-25">
    <div class="border m-4">
      <div class=""><a href="{{ route('post.input') }}" class="post-btn">投稿</a></div>
      <div class="post-search-box">
        <input type="text" placeholder="キーワードを検索" name="keyword" form="postSearchRequest">
        <input type="submit" value="検索" form="postSearchRequest">
        </form>

      </div>
      <div class="post-search">
      <div class="like">
      <input type="submit" name="like_posts" class="category_btn" value="いいねした投稿" form="postSearchRequest"></div>
<div class="my-post">
      <input type="submit" name="my_posts" class="category_btn" value="自分の投稿" form="postSearchRequest"></div>
</div>
      <ul>
        @foreach($categories as $category)
        <li class="main_categories" category_id="{{ $category->id }}"><span>{{ $category->main_category }}<span></li>
        @endforeach
      </ul>
    </div>
  </div>
  <form action="{{ route('post.show') }}" method="get" id="postSearchRequest"></form>
</div>

<script src="{{ asset('js/bulletin.js') }}"></script>
</x-sidebar>

<style>
  .post-btn{
    display: inline-block;
    background-color: #5bc0de;
    color: white;
    padding: 4px 70px;
    border-radius:5px;
  }

  .post-search-box{
    margin-top:10px;
    display: flex;
    align-items:center;
  }

  .post-search-box input[type="text"]{
    background-color: #f5f5f5;
    border: 1px;
  }
  .post-search-box input[type="submit"]{
    background-color: #5bc0de;
    color: white;
    border: none;
    border-radius:0 4px 4px 0;
    padding: 4px 20px;
  }

  .like input[type="submit"]{
    background-color: #FF9BAD;
    color: white;
    border: none;
     padding: 4px 20px;
  }

  .post-search{
    margin-top:10px;
    display: flex;
    align-items:center;
  }

  .my-post input[type="submit"]{
    background-color: #FFC666;
    color: white;
    border: none;
     padding: 4px 20px;
  }
</style>
