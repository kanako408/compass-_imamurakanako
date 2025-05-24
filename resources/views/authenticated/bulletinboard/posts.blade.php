<x-sidebar>
  <div class="board_area w-100 border m-auto d-flex">
    <div class="post_view w-75 mt-5">
      <p class="w-75 m-auto"></p>
      @foreach($posts as $post)
      <div class="post_area border w-75 m-auto p-3">
        <p class="contributor"><span>{{ $post->user->over_name }}</span><span class="ml-3 ">{{ $post->user->under_name }}</span>さん</p>
        <p><a class="specific-link" href="{{ route('post.detail', ['id' => $post->id]) }}">{{ $post->post_title }}</a></p>
        <div class="post_bottom_area d-flex">
          <div class="sub-categories" @if($post->subCategories->isEmpty()) style="display: none;" @endif>
            @foreach($post->subCategories as $subCategory)
            @if(!empty($subCategory->sub_category))
            <span>{{ $subCategory->sub_category }}</span>
            @endif
            @endforeach
          </div>
          <div class="d-flex post_status " style="color: #999;">

            <div class="mr-5">
              <i class="fa fa-comment"></i><span class="comment-counts">{{ $post->postComments->count() }}</span>
            </div>
            <div>
              @if(Auth::user()->is_Like($post->id))
              <p class="m-0">
                <i class="fas fa-heart un_like_btn" post_id="{{ $post->id }}"></i>
                <span class="like_counts{{ $post->id }}">{{ $like->likeCounts($post->id) }}</span>
              </p>
              @else
              <p class="m-0">
                <i class="fas fa-heart like_btn" post_id="{{ $post->id }}"></i>
                <span class="like_counts{{ $post->id }}">{{ $like->likeCounts($post->id) }}</span>
              </p>
              @endif
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
    <div class="other_area w-25">
      <div class="m-4">
        <div class="btn post"><a href="{{ route('post.input') }}">投稿</a></div>
        <div class="search-area">
          <input type=" text" placeholder="キーワードを検索" name="keyword" form="postSearchRequest" class="search-field">
          <input type="submit" value="検索" form="postSearchRequest" class="search-button">
        </div>
        <div class="filter-btn_area">
          <input type="submit" name="like_posts" class="filter-btn" value="いいねした投稿" form="postSearchRequest">
          <input type="submit" name="my_posts" class="more-btn" value="自分の投稿" form="postSearchRequest">
        </div>
        <ul class="category-area">
          <p>カテゴリー検索</p>
          @foreach($categories as $category)
          <li class="main_category_wrap">
            <div class="main_categories" category_id="{{ $category->id }}">
              <span class="main_category_name">{{ $category->main_category }}</span>
              <span class="toggle-arrow" category_id="{{ $category->id }}"></span>
            </div>
            <ul class="sub_categories category_num{{ $category->id }}" style="display: none;">
              @foreach($category->subCategories as $sub_category)
              <li>
                <button type="submit" name="sub_category_id" value="{{ $sub_category->id }}" form="postSearchRequest" class="sub_category_btn">
                  {{ $sub_category->sub_category }}
                </button>
              </li>
              @endforeach
            </ul>
          </li>
          @endforeach
        </ul>
      </div>
    </div>
    <form action="{{ route('post.show') }}" method="get" id="postSearchRequest"></form>
  </div>
</x-sidebar>
