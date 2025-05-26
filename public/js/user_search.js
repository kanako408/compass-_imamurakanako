$(function () {
  $('.search_conditions_header').click(function () {
    $('.search_conditions_inner').slideToggle();
    // 矢印回転用のクラス切替
    $(this).toggleClass('open');
  });

  $('.subject_edit_btn').click(function () {
    $('.subject_inner').slideToggle();
    // 矢印回転用のクラス切替
    $(this).toggleClass('open');

  });
});
