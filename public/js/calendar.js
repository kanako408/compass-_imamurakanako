$(function () {
  // キャンセルボタンにクリックイベントを設定
  $('.js-cancel-button').on('click', function () {
    // 各属性値を取得
    const date = $(this).attr('data-date'); // 例: "2025-04-02"
    const part = $(this).attr('data-part'); // 例: "2"
    const partLabel = $(this).attr('data-label'); // 例: "リモ2部"

    // 表示用のテキスト設定
    const message = `予約日：${date}<br>時間：リモ${partLabel}部<br>上記の予約をキャンセルしてもよろしいですか？`;
    $('#modalInfo').html(message);

    // フォームに値を挿入
    $('#modalDate').val(date);
    $('#modalPart').val(part);

    // モーダルをフェードイン表示
    $('#cancelModal').fadeIn();
    return false; // デフォルトのイベント動作をキャンセル
  });

  // モーダルを閉じる処理
  $('.js-modal-close').on('click', function () {
    $('#cancelModal').fadeOut();
    return false; // デフォルトのイベント動作をキャンセル
  });
});
