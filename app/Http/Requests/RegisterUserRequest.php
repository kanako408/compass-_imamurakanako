<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
// 追加
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class RegisterUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * 認可を行う場合は true を返す
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * バリデーションルール
     */
    public function rules(): array
    {
        $today = Carbon::today()->toDateString(); // 今日の日付
        $minDate = '2000-01-01'; // 最小日付（2000年1月1日
        return [ // 氏名
            'over_name' => ['required', 'string', 'max:10'],
            'under_name' => ['required', 'string', 'max:10'],

            // カナ氏名（カタカナのみ）
            'over_name_kana' => ['required', 'string', 'regex:/^[ァ-ヶー]+$/u', 'max:30'],
            'under_name_kana' => ['required', 'string', 'regex:/^[ァ-ヶー]+$/u', 'max:30'],

            // メールアドレス
            'mail_address' => ['required', 'string', 'email', 'max:100', 'unique:users,mail_address'],

            // 性別（1:男性, 2:女性, 3:その他）
            'sex' => ['required', 'integer', 'in:1,2,3'],

            // 誕生日（有効な日付 & 2000年1月1日以降 & 今日まで）
            'old_year' => ['required', 'integer', 'between:2000,' . date('Y')],
            'old_month' => ['required', 'integer', 'between:1,12'],
            'old_day' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $date = sprintf('%04d-%02d-%02d', request('old_year'), request('old_month'), $value);
                    if (!strtotime($date) || $date < '2000-01-01' || $date > Carbon::today()->toDateString()) {
                        $fail('正しい日付を入力してください。（2000年1月1日～今日まで）');
                    }
                }
            ],

            // 役割（1:講師(国語), 2:講師(数学), 3:講師(英語), 4:生徒）
            'role' => ['required', 'integer', 'in:1,2,3,4'],

            // パスワード（8～30文字 & 確認と一致）
            'password' => ['required', 'string', 'min:8', 'max:30', 'confirmed'],
            //
        ];
    }
    /**
     * エラーメッセージのカスタマイズ
     */
    public function messages(): array
    {
        return [
            // 氏名
            'over_name.required' => '姓を入力してください。',
            'over_name.max' => '姓は10文字以下で入力してください。',
            'under_name.required' => '名を入力してください。',
            'under_name.max' => '名は10文字以下で入力してください。',

            // カナ氏名
            'over_name_kana.required' => '姓（カナ）を入力してください。',
            'over_name_kana.regex' => '姓（カナ）はカタカナのみで入力してください。',
            'over_name_kana.max' => '姓（カナ）は30文字以下で入力してください。',
            'under_name_kana.required' => '名（カナ）を入力してください。',
            'under_name_kana.regex' => '名（カナ）はカタカナのみで入力してください。',
            'under_name_kana.max' => '名（カナ）は30文字以下で入力してください。',

            // メールアドレス
            'mail_address.required' => 'メールアドレスを入力してください。',
            'mail_address.email' => 'メールアドレスの形式が正しくありません。',
            'mail_address.max' => 'メールアドレスは100文字以下で入力してください。',
            'mail_address.unique' => 'このメールアドレスは既に使用されています。',

            // 性別
            'sex.required' => '性別を選択してください。',
            'sex.in' => '選択した性別が無効です。',

            // 誕生日
            'old_year.required' => '生年を入力してください。',
            'old_year.between' => '生年は2000年から今年までの間で入力してください。',
            'old_month.required' => '生月を入力してください。',
            'old_month.between' => '生月は1から12の間で入力してください。',
            'old_day.required' => '生日を入力してください。',

            // 役割
            'role.required' => '権限を選択してください。',
            'role.in' => '選択した権限が無効です。',

            // パスワード
            'password.required' => 'パスワードを入力してください。',
            'password.min' => 'パスワードは8文字以上で入力してください。',
            'password.max' => 'パスワードは30文字以下で入力してください。',
            'password.confirmed' => 'パスワードが一致しません。',
        ];
    }
}
