<x-sidebar>
  <!-- <p>ユーザー検索</p> -->
  <div class="search_content w-100 d-flex">
    <div class="reserve_users_area">
      @foreach($users as $user)
      <div class="border one_person">
        <div>
          <span>ID : </span><span>{{ $user->id }}</span>
        </div>
        <div><span>名前 : </span>
          <a href="{{ route('user.profile', ['id' => $user->id]) }}">
            <span>{{ $user->over_name }}</span>
            <span>{{ $user->under_name }}</span>
          </a>
        </div>
        <div>
          <span>カナ : </span>
          <span>({{ $user->over_name_kana }}</span>
          <span>{{ $user->under_name_kana }})</span>
        </div>
        <div>
          @if($user->sex == 1)
          <span>性別 : </span><span>男</span>
          @elseif($user->sex == 2)
          <span>性別 : </span><span>女</span>
          @else
          <span>性別 : </span><span>その他</span>
          @endif
        </div>
        <div>
          <span>生年月日 : </span><span>{{ $user->birth_day }}</span>
        </div>
        <div>
          @if($user->role == 1)
          <span>権限 : </span><span>教師(国語)</span>
          @elseif($user->role == 2)
          <span>権限 : </span><span>教師(数学)</span>
          @elseif($user->role == 3)
          <span>権限 : </span><span>講師(英語)</span>
          @else
          <span>権限 : </span><span>生徒</span>
          @endif
        </div>
        <div>
          @if($user->role == 4)
          <span>選択科目 :</span>
          @if($user->subjects->isNotEmpty())
          @foreach($user->subjects as $subject)
          <span>{{ $subject->subject }}</span>
          @endforeach
          @else
          <span>未選択</span>
          @endif
          @endif
        </div>
      </div>
      @endforeach
    </div>
    <div class="search_area w-25 ">
      <div class="search-widget">
        <h5>検索</h5>
        <div class="tool-container">
          <div>
            <input type="text" class="free_word" name="keyword" placeholder="キーワードを検索" form="userSearchRequest">
          </div>
          <div class="search-form-group">
            <label for="category">カテゴリ</label>
            <select form="userSearchRequest" name="category" id="category">
              <option value="name">名前</option>
              <option value="id">社員ID</option>
            </select>
          </div>
          <div class="search-form-group">
            <label for="updown">並び替え</label>
            <select name="updown" form="userSearchRequest" id="updown">
              <option value="ASC">昇順</option>
              <option value="DESC">降順</option>
            </select>
          </div>
        </div>
        <div class="search_conditions_wrap">
          <div class="search_conditions_header" style="display: flex;justify-content: space-between;border-bottom: 1px solid #ccc;">
            <span class=" search_conditions_label">検索条件</span>
            <span class="toggle-arrow search_conditions_icon"></span>

          </div>

          <div class="search_conditions_inner">
            <div style="margin-top: 10px;margin-bottom: 10px;">
              <label style="display: flex;flex-direction: column;">性別</label>
              <span style="color: black;">男</span><input type="radio" name="sex" value="1" form="userSearchRequest">
              <span style="color: black;">女</span><input type="radio" name="sex" value="2" form="userSearchRequest">
              <span style="color: black;">その他</span><input type="radio" name="sex" value="3" form="userSearchRequest">
            </div>
            <div style="margin-top: 10px;margin-bottom: 10px;">
              <label style="display: flex;flex-direction: column;">権限</label>
              <select name="role" form="userSearchRequest" class="engineer">
                <option selected disabled>----</option>
                <option value="1">教師(国語)</option>
                <option value="2">教師(数学)</option>
                <option value="3">教師(英語)</option>
                <option value="4" class="">生徒</option>
              </select>
            </div>
            <div class="selected_engineer" style="margin-top: 10px;margin-bottom: 10px;">
              <label style="display: flex;flex-direction: column;">選択科目</label>
              @foreach($subjects as $subject)
              <label style="color: black;">
                {{ $subject->subject }}
                <input type="checkbox" name="subjects[]" value="{{ $subject->id }}" form="userSearchRequest">
              </label>
              @endforeach
            </div>
          </div>
        </div>
        <div class="select-form">
          <div>
            <input type="submit" name="search_btn" value="検索" form="userSearchRequest" class="search_btn" style=" padding: 6px; margin-bottom: 20px;">
          </div>
          <div style="display: flex;justify-content: center;">
            <input type="reset" value="リセット" form="userSearchRequest" style="color: #03aad2;background-color: #ecf1f6;border: none;">
          </div>
        </div>
        <form action="{{ route('user.show') }}" method="get" id="userSearchRequest"></form>
      </div>
    </div>
</x-sidebar>
