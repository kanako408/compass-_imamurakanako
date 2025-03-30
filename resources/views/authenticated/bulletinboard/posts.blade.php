<x-sidebar>
  <div class="board_area w-100 border m-auto d-flex">
    <div class="post_view w-75 mt-5">
      <p class="w-75 m-auto">投稿一覧</p>
      @foreach($posts as $post)
      <div class="post_area border w-75 m-auto p-3">
        <p><span>{{ $post->user->over_name }}</span><span class="ml-3">{{ $post->user->under_name }}</span>さん</p>
        <p><a href="{{ route('post.detail', ['id' => $post->id]) }}">{{ $post->post_title }}</a></p>
        <div class="post_bottom_area d-flex">
          <div class="d-flex post_status">
            <div class="mr-5">
              <i class="fa fa-comment"></i><span class="comment-counts">{{ $post->postComments->count() }}</span>
            </div>
            <div>
              @if(Auth::user()->is_Like($post->id))
              <form action="{{ route('post.unlike', ['id' => $post->id]) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-link p-0 border-0">
                  <i class="fas fa-heart text-danger"></i>
                </button>
              </form>
              @else
              <form action="{{ route('post.like', ['id' => $post->id]) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-link p-0 border-0">
                  <i class="fas fa-heart text-secondary"></i>
                </button>
              </form>
              @endif
              <span class="like_counts">{{ $post->likeCount() }}</span>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
    <div class="other_area border w-25">
      <div class="border m-4">
        <div class=""><a href="{{ route('post.input') }}">投稿</a></div>
        <div class="">
          <input type="text" placeholder="キーワードを検索" name="keyword" form="postSearchRequest">
          <input type="submit" value="検索" form="postSearchRequest">
        </div>
        <input type="submit" name="like_posts" class="category_btn" value="いいねした投稿" form="postSearchRequest">
        <input type="submit" name="my_posts" class="category_btn" value="自分の投稿" form="postSearchRequest">
        <ul>
          @foreach($categories as $category)
          <li class="main_categories" category_id="{{ $category->id }}"><span>{{ $category->main_category }}<span>
                <ul>
                  <!-- サブカテゴリーのボタンをクリックすると、そのサブカテゴリーに属する投稿のみ表示 -->
                  @foreach($category->subCategories as $sub_category)<button type="submit" name="sub_category_id" value="{{ $sub_category->id }}" form="postSearchRequest">
                    {{ $sub_category->sub_category }}
                  </button></li>
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
