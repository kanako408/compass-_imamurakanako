<x-guest-layout>

  <body class="all_content">
    <img src=" {{ asset('image/atlas-black.png') }} " style="margin-top: 90px;">
    <form action="{{ route('loginPost') }}" method="POST">
      <div class="w-100 d-flex justify-content-center" style="margin-top: 30px;">
        <!-- <div class="w-100 vh-100 d-flex " style="align-items:center; justify-content:center;"> -->
        <div class="border vh-50 w-25 form-group">
          <div class="w-75 m-auto pt-5">
            <label class="d-block m-0" style="font-size:13px;">メールアドレス</label>
            <div class="border-bottom border-primary w-100">
              <input type="text" class="w-100 border-0" name="mail_address">
            </div>
          </div>
          <div class="w-75 m-auto pt-5">
            <label class="d-block m-0" style="font-size:13px;">パスワード</label>
            <div class="border-bottom border-primary w-100">
              <input type="password" class="w-100 border-0" name="password">
            </div>
          </div>
          <div class="text-right m-3">
            <input type="submit" class="btn btn-primary" value="ログイン">
          </div>
          <div class="text-center"
            style="margin-bottom: 20px;">
            <a href=" {{ route('registerView') }}">新規登録はこちら</a>
          </div>
        </div>
        {{ csrf_field() }}
      </div>
    </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/register.js') }}" rel="stylesheet"></script>
  </body>
</x-guest-layout>
