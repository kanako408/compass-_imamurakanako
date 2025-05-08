<?php

namespace App\Http\Controllers\Authenticated\Calendar\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\General\CalendarView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\USers\User;
use Auth;
use DB;

class CalendarController extends Controller
{
    public function show()
    {
        $calendar = new CalendarView(time());
        return view('authenticated.calendar.general.calendar', compact('calendar'));
    }

    public function reserve(Request $request)
    {
        DB::beginTransaction();
        try {
            $getPart = $request->getPart; // 各日付に対してユーザーが選択した部（1〜3部）
            $getDate = $request->getData; // 各日付（YYYY-MM-DD形式）
            dd($getPart, $getDate); // デバッグ用：選択された部と日付を表示して止まる（本番では削除）

            // 日付と部の配列を結合し、空でない組み合わせだけ抽出（予約されている日付のみ）
            $reserveDays = array_filter(array_combine($getDate, $getPart));

            // [];
            // foreach ($getDate as $index => $date) {
            //     if (!empty($getPart[$index])) {
            //         $reserveDays[$date] = $getPart[$index];
            //     }
            // }

            foreach ($reserveDays as $key => $value) {
                // 対象日付($key)と部($value)の予約枠を1件取得
                $reserve_settings = ReserveSettings::where('setting_reserve', $key)->where('setting_part', $value)->first();

                // $reserve_settings = ReserveSettings::where('setting_reserve', $key)
                //     ->where('setting_part', $value)
                //     ->first();

                // if ($reserve_settings) {
                // 該当の予約枠が見つかったら、
                $reserve_settings->decrement('limit_users'); // 残り定員を1減らす
                $reserve_settings->users()->attach(Auth::id()); // 中間テーブルに予約情報（user_id）を追加
            }
            DB::commit(); // トランザクション成功 → 確定
        } catch (\Exception $e) {
            DB::rollback(); // エラー発生時 → すべて巻き戻す
        } // 処理後、カレンダー画面にリダイレクト
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }

    // キャンセル機能
    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {
            $getPart = $request->getPart;
            $getDate = $request->getData;
            $reserveDays = array_filter(array_combine($getDate, $getPart));

            // foreach ($getDate as $index => $date) {
            //     if (!empty($getPart[$index])) {
            //         $reserveDays[$date] = $getPart[$index];
            //     }
            // }

            foreach ($reserveDays as $key => $value) {
                $reserve_settings = ReserveSettings::where('setting_reserve', $key)
                    ->where('setting_part', $value)
                    ->first();

                // if ($reserve_settings) {
                $reserve_settings->increment('limit_users');
                $reserve_settings->users()->detach(Auth::id());
                // }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }
}
