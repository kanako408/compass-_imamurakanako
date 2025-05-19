<?php

namespace App\Calendars\General;

use Carbon\Carbon;
use Auth;

class CalendarView
{

  private $carbon;
  function __construct($date)
  {
    $this->carbon = new Carbon($date);
  }

  public function getTitle()
  {
    return $this->carbon->format('Y年n月');
  }

  function render()
  {
    $html = [];
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th class="border">月</th>';
    $html[] = '<th class="border">火</th>';
    $html[] = '<th class="border">水</th>';
    $html[] = '<th class="border">木</th>';
    $html[] = '<th class="border">金</th>';
    $html[] = '<th class="border day-sat">土</th>';
    $html[] = '<th class="border day-sun">日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';
    $weeks = $this->getWeeks();
    foreach ($weeks as $week) {
      $html[] = '<tr class="' . $week->getClassName() . '">';

      $days = $week->getDays();
      foreach ($days as $day) {
        $startDay = $this->carbon->copy()->format("Y-m-01");
        $toDay = $this->carbon->copy()->format("Y-m-d");
        $isPast = $day->everyDay() <= $toDay;
        $weekDay = Carbon::parse($day->everyDay())->format('N'); // 1=月, 7=日

        /// クラスの組み立て
        $tdClass = 'calendar-td';

        if ($weekDay == 6) { // 土曜日
          $tdClass .= ' day-sat';
        }
        if ($weekDay == 7) { // 日曜日
          $tdClass .= ' day-sun';
        }
        if ($isPast) { // 過去日判定
          $tdClass .= ' past-day';
        }

        // <td> に適用
        $html[] = '<td class="' . $tdClass . '">';


        // if ($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) {
        //   // グレー背景適用条件★
        //   // $tdClass = 'calendar-td ' . $day->getClassName();
        //   $html[] = $isPast
        //     ? '<td class="calendar-td past-day ">'
        //     : '<td class="calendar-td">';
        // }
        // $html[] = '<td class="' . $tdClass . '">';
        // $html[] = $day->render();

        // 日付送信用 hidden（予約時のズレ防止）
        // $html[] = '<input type="hidden" name="getData[]" value="' . $dayDate . '" form="reserveParts">';
        // } else {
        // $html[] = '<td class="calendar-td">';

        // } else {
        //   $html[] = '<td class="calendar-td ' . $day->getClassName() . '">';
        // }
        $html[] = $day->render();
        // ↓★67行目まで
        // $reserveDays = array_map(function ($d) {
        //   return \Carbon\Carbon::parse($d)->format('Y-m-d');
        // }, $day->authReserveDay());
        // ↓★71行目まで
        if (in_array($day->everyDay(), $day->authReserveDay())) {
          $reservePart = $day->authReserveDate($day->everyDay())->first()->setting_part;
          if ($reservePart == 1) {
            $reserveLabel = "リモ1部";
          } else if ($reservePart == 2) {
            $reserveLabel = "リモ2部";
          } else if ($reservePart == 3) {
            $reserveLabel = "リモ3部";
          }
          if
          // ($isPast)
          ($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) {
            // ↓2行分★
            // if ($dayDate < $today) {
            //   // 過去日の場合はボタンでなく「〇部参加」表記
            $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px">' . $reservePart . '部参加</p>';
            $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
            // 未来予約 → キャンセルボタン
          } else {
            // ↓1行分コメントアウト
            // $html[] = '<button type="button" class="btn btn-danger p-0 w-75 js-cancel-button" name="delete_date" style="font-size:12px" value="' . $day->authReserveDate($dayDate)->first()->setting_reserve . '" data-date="' . $dayDate . '" data-part="' . $reservePart . '" data-label="' . $reserveLabel . '">' . $reserveLabel . '</button>';
            $html[] = '<button type="button" class="btn btn-danger p-0 w-75 js-cancel-button" name="delete_date" style="font-size:12px" value="' . $day->authReserveDate($day->everyDay())->first()->setting_reserve . '" data-date="' . $day->everyDay() . '" data-part="' . $day->authReserveDate($day->everyDay())->first()->setting_part . '" data-label="' . $reservePart . '">' . $reserveLabel . '</button>';
            $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
          }
        } else {
          // 予約していない
          // 予約対象の日付（Y-m-d形式）を getDate[] に格納して送っている場所
          // everyDay() はそのセルの日付（例：2025-05-02など）を返す
          // ↓9行分コメントアウト★
          if
          // ($isPast) {
          ($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) {
            // $html[] = '<input type="hidden" name="getDate[]" value="' . $day->everyDay() . '" form="reserveParts">';
            $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px; color: #212529;">受付終了</p>';
            $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
          } else {
            // $html[] = '<input type="hidden" name="getDate[]" value="' . $day->everyDay() . '" form="reserveParts">';
            $html[] = $day->selectPart($day->everyDay());
            //   // <select name="getPart[]"> を出力しているので、ユーザーが選択した「リモ1部」「リモ2部」などが送信される
          }
        }
        $html[] = $day->getDate();
        $html[] = '</td>';
      }
      $html[] = '</tr>';
    }
    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';
    $html[] = '<form action="/reserve/calendar" method="post" id="reserveParts">' . csrf_field() . '</form>';
    // フォームIDが reserveParts で、POST先は /reserve/calendar（＝CalendarController@reserve）
    $html[] = '<form action="/delete/calendar" method="post" id="deleteParts">' . csrf_field() . '</form>';
    return implode('', $html);
  }
  protected function getWeeks()
  {
    $weeks = [];
    $firstDay = $this->carbon->copy()->firstOfMonth();
    $lastDay = $this->carbon->copy()->lastOfMonth();
    $week = new CalendarWeek($firstDay->copy());
    $weeks[] = $week;
    $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();
    while ($tmpDay->lte($lastDay)) {
      $week = new CalendarWeek($tmpDay, count($weeks));
      $weeks[] = $week;
      $tmpDay->addDay(7);
    }
    return $weeks;
  }
}
