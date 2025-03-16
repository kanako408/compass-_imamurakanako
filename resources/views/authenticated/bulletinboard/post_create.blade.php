<x-sidebar>
  <div class="post_create_container d-flex">
    <div class="post_create_area border w-50 m-5 p-5">
      <div class="">
        <p class="mb-0">カテゴリー</p>
        <select class="w-100" form="postCreate" name="post_category_id">
          @foreach($mainCategories as $main_category)
          <optgroup label="{{ $main_category->main_category }}">
            <!-- サブカテゴリー表示 -->
          </optgroup>
          @endforeach
        </select>
      </div>
      <div class="mt-3">
        @if($errors->first('post_title'))
        <span class="error_message">{{ $errors->first('post_title') }}</span>
        @endif
        <p class="mb-0">タイトル</p>
        <input type="text" class="w-100" form="postCreate" name="post_title" value="{{ old('post_title') }}">
      </div>
      <div class="mt-3">
        @if($errors->first('post_body'))
        <span class="error_message">{{ $errors->first('post_body') }}</span>
        @endif
        <p class="mb-0">投稿内容</p>
        <textarea class="w-100" form="postCreate" name="post_body">{{ old('post_body') }}</textarea>
      </div>
      <div class="mt-3 text-right">
        <input type="submit" class="btn btn-primary" value="投稿" form="postCreate">
      </div>
      <form action="{{ route('post.create') }}" method="post" id="postCreate">{{ csrf_field() }}</form>
    </div>
    <!-- ユーザーに特定の権限があるかどうかを確認↓ -->
    @can('admin')
    <div class="w-25 ml-auto mr-auto">
      <div class="category_area mt-5 p-5">
        <div class="">
          <p class="m-0">メインカテゴリー</p>
          <input type="text" class="w-100" name="main_category_name" form="mainCategoryRequest">
          <input type="submit" value="追加" class="w-100 btn btn-primary p-0" form="mainCategoryRequest">
        </div>
        <!-- サブカテゴリー追加 -->
        <div>
          <p class="m-0">サブカテゴリー</p>
          <div>
            <select name="main_category_id" form="subCategoryRequest" required>
              <option value="">選択してください</option>
              @foreach($mainCategories as $category)
              <option value="{{ $category->id }}">{{ $category->main_category }}</option>
              @endforeach
            </select>
            <!-- @error('main_category_id')
            <p class="error">{{ $message }}</p>
            @enderror -->
          </div>
          <input type="text" class="w-100" name="sub_category_name" form="subCategoryRequest" required>
          <!-- @error('sub_category_name')
          <p class="error">{{ $message }}</p>
          @enderror -->
        </div>
        <input type="submit" value="追加" class="w-100 btn btn-primary p-0" form="subCategoryRequest">
      </div>
      <form action="{{ route('main.category.create') }}" method="post" id="mainCategoryRequest">{{ csrf_field() }}</form>
      <form action="{{ route('sub.category.create') }}" method="post" id="subCategoryRequest">{{ csrf_field() }}</form>
    </div>
  </div>
  @endcan
  </div>
</x-sidebar>
