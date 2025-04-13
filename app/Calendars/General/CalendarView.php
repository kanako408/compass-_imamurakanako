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
    $html[] = '<th>月</th>';
    $html[] = '<th>火</th>';
    $html[] = '<th>水</th>';
    $html[] = '<th>木</th>';
    $html[] = '<th>金</th>';
    $html[] = '<th>土</th>';
    $html[] = '<th>日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';
    $weeks = $this->getWeeks();
    foreach ($weeks as $week) {
      $html[] = '<tr class="' . $week->getClassName() . '">';

      $days = $week->getDays();
      foreach ($days as $day) {
        $dayDate = $day->everyDay();
        $today = Carbon::today()->format('Y-m-d');

        // グレー背景適用条件
        $tdClass = 'calendar-td ' . $day->getClassName();
        if ($dayDate < $today) {
          $tdClass .= ' bg-secondary text-white';
        }
        $html[] = '<td class="' . $tdClass . '">';
        $html[] = $day->render();

        // 日付送信用 hidden（予約時のズレ防止）
        $html[] = '<input type="hidden" name="getData[]" value="' . $dayDate . '" form="reserveParts">';

        $reserveDays = array_map(function ($d) {
          return \Carbon\Carbon::parse($d)->format('Y-m-d');
        }, $day->authReserveDay());

        if (in_array($dayDate, $day->authReserveDay())) {
          // 予約あり
          $reservePart = $day->authReserveDate($dayDate)->first()->setting_part;

          if ($reservePart == 1) {
            $reserveLabel = "リモ1部";
          } else if ($reservePart == 2) {
            $reserveLabel = "リモ2部";
          } else if ($reservePart == 3) {
            $reserveLabel = "リモ3部";
          }
          if ($dayDate < $today) {
            // 過去日の場合はボタンでなく「〇部参加」表記
            $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px">' . $reservePart . '部参加</p>';
            $html[] = '<input type="hidden" name="getPart[]" value="' . $reservePart . '" form="reserveParts">';
            // 未来予約 → キャンセルボタン
          } else {
            $html[] = '<button type="button" class="btn btn-danger p-0 w-75 js-cancel-button" name="delete_date" style="font-size:12px" value="' . $day->authReserveDate($dayDate)->first()->setting_reserve . '" data-date="' . $dayDate . '" data-part="' . $reservePart . '" data-label="' . $reserveLabel . '">' . $reserveLabel . '</button>';
            $html[] = '<input type="hidden" name="getPart[]" value="' . $reservePart . '" form="reserveParts">';
          }
        } else {
          // 予約していない
          if ($dayDate < $today) {
            $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px">受付終了</p>';
            $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
          } else {
            $html[] = $day->selectPart($dayDate);
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
