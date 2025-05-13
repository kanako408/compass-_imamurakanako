<x-sidebar>
  <div class="vh-100 pt-5" style="align-items:center; justify-content:center;">
    <div class="w-75 m-auto pt-5 pb-5" style="border-radius:5px; background:#FFF;">
      <p class="text-center">{{ $calendar->getTitle() }}</p>
      {!! $calendar->render() !!}
      <div class="adjust-table-btn m-auto text-right">
        <input type="submit" class="btn btn-primary" value="登録" form="reserveSetting" onclick="return confirm('登録してよろしいですか？')">
      </div>
    </div>
  </div>
</x-sidebar>
