<x-sidebar>
  <div class="vh-100 pt-5" style="align-items:center; justify-content:center;">
    <div class="w-75 m-auto pt-5 pb-5 frame-shadow">
      <div class="w-100 m-auto" style="border-radius:5px;max-width: 830px;">
        <p class="text-center">{{ $calendar->getTitle() }}</p>
        <div class="">
          {!! $calendar->render() !!}
        </div>
        <div class="adjust-table-btn m-auto text-right" style="max-width: 830px;padding-top: 10px;">
          <input type="submit" class="btn btn-primary" value="登録" form="reserveSetting" onclick="return confirm('登録してよろしいですか？')">
        </div>
      </div>
    </div>
</x-sidebar>
