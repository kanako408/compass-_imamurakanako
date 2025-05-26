<x-sidebar>
  <div class="vh-100 d-flex" style="align-items:center; justify-content:center;">
    <div class="m-auto h-75" style="width: 60% !important;">
      {{-- 日付と部の表示 --}}
      <span>{{ $date }}日</span><span class="ml-3">{{ $part }}部</span>

      {{-- ユーザー一覧表示 --}}
      <div class="p-1 border frame-shadow">
        <table class="admin-reserve-detail">
          <tr class="text-center" style="background-color: #03aad2;color: white;">
            <th style="padding: 5px;">ID</th>
            <th style="padding: 5px;">名前</th>
            <th style="padding: 5px;">場所</th>
          </tr>
          <tbody>
            @foreach ($reservePersons as $reserve)
            @foreach ($reserve->users as $user)
            <tr class="text-center">
              <td style="padding: 8px;">{{ $user->id }}</td>
              <td style="padding: 8px;">{{ $user->over_name }}{{ $user->under_name }}</td>
              <td style="padding: 8px;">リモート</td>
            </tr>
            @endforeach
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</x-sidebar>
