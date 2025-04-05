<x-sidebar>
  <div class="vh-100 pt-5" style="background:#ECF1F6;">
    <div class="border w-75 m-auto pt-5 pb-5" style="border-radius:5px; background:#FFF;">
      <div class="w-75 m-auto border" style="border-radius:5px;">

        <p class="text-center">{{ $calendar->getTitle() }}</p>
        <div class="">
          {!! $calendar->render() !!}
        </div>
      </div>
      <div class="text-right w-75 m-auto">
        <input type="submit" class="btn btn-primary" value="予約する" form="reserveParts">
      </div>
    </div>
  </div>
  <!-- キャンセル確認モーダル -->
  <div class="modal js-modal">
    <div class="modal__bg js-modal-close"></div>
    <div class="modal__content">
      <form action="{{ route('deleteParts') }}" method="post">
        <div class="w-100">
          <div class="modal-inner-title w-50 m-auto">
            <p class="text-center font-weight-bold">本当にこの予約をキャンセルしますか？</p>
          </div>

          <div class="modal-inner-body w-50 m-auto pt-3 pb-3">
            <p id="modalInfo" class="text-center"></p>
          </div>

          <div class="w-50 m-auto edit-modal-btn d-flex justify-content-between">
            <a class="js-modal-close btn btn-danger d-inline-block">閉じる</a>

            {{-- 日付と部情報はJSで埋める --}}
            <input type="hidden" id="modalDate" name="getData[]" value="">
            <input type="hidden" id="modalPart" name="getPart[]" value="">

            <input type="submit" class="btn btn-primary d-block" value="キャンセルする">
          </div>
        </div>
        @csrf
      </form>
    </div>
  </div>
</x-sidebar>
