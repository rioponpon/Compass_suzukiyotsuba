<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreUserRequests extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
   public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'over_name' => ['required','string','max:10'],
            'under_name' => ['required','string','max:10'],
            'over_name_kana' =>
            ['required','string','regex:/^[ア-ン゛゜ァ-ォ ャ-ョー]+$/u','max:30'],
            'under_name_kana' =>
            ['required','string','regex:/^[ア-ン゛゜ァ-ォ ャ-ョー]+$/u','max:30'],

            'mail_address' =>
            ['required',//入力必須
            'mail_address' => 'email',//メールアドレス形式
            'max:100',
            'unique:users,mail_address'],

            'old_year' =>['required'],
            'old_month' =>['required'],
            'old_day' => ['required'],

            'sex' => ['required'],//'in:男,女,その他'],
            'role' =>['required'],//'in:講師(国語),講師(英語),講師(数学),生徒'],
            'password' => [
                'required',
                'min:8',
                'max:30',
                'password' => 'confirmed:password'],  // password_confirmation と一致しているか
            ];
        }
    public function messages()
    {
        return[
        'over_name.required' => 'ユーザー名は必須です。',
        'over_name.max' => 'ユーザー名は10文字以内で入力してください。',
        'under_name.required' => 'ユーザー名は必須です。',
        'under_name.max' => 'ユーザー名は10文字以内で入力してください。',
        'over_name_kana.required' => 'ユーザー名は必須です。',
        'over_name_kana.regex:/^[ア-ン゛゜ァ-ォ ャ-ョー]+$/u' => 'パスワードはカタカナのみで入力してください。',
        'over_name_kana.max' => 'ユーザー名は30文字以内で入力してください。',
        'under_name_kana.required' => 'ユーザー名は必須です。',
        'under_name_kana.regex:/^[ア-ン゛゜ァ-ォ ャ-ョー]+$/u' => 'パスワードはカタカナのみで入力してください。',
        'under_name_kana_kana.max' => 'ユーザー名は30文字以内で入力してください。',

        'mail_address.required' => 'メールアドレスは必須です。',
        'mail_address.email' => '正しいメールアドレス形式で入力してください。',
        'mail_address.max' => 'メールアドレスは100文字以内で入力してください。',
        'mail_address.unique' => 'このメールアドレスはすでに登録されています。',

        'old_year.required' =>'生年月日は入力必須です。',
        'old_month.required' =>'生年月日は入力必須です。',
        'old_day.required' =>'生年月日は入力必須です。',
        'sex.required' =>'性別は入力必須です。',
        'sex.in:男,女,その他'=>'性別は男、女、その他で入力してください。',
        'role.required'=>'入力必須です。',
        'role.in:講師(国語),講師(英語),講師(数学),生徒'=>'項目以外選択禁止',
        'password.required' => 'パスワードは必須です。',
        'password.min' => 'パスワードは8文字以上で入力してください。',
        'password.max' => 'パスワードは30文字以内で入力してください。',
        'password.confirmed' => 'パスワード確認が一致しません。',
        ];
    }

public function withValidator($validator)
{
    $validator->after(function ($validator) {
        $year = $this->input('old_year');
        $month = $this->input('old_month');
        $day = $this->input('old_day');

        // 年月日が揃っているか
        if (!$year || !$month || !$day) {
            $validator->errors()->add('old_year', '生年月日をすべて入力してください。');
            return;
        }

        // 正しい日付かチェック
        if (!checkdate((int)$month, (int)$day, (int)$year)) {
            $validator->errors()->add('old_year', '存在しない日付です。');
            return;
        }

        // 日付の範囲チェック
        $date = Carbon::createFromDate($year, $month, $day);
        $min = Carbon::create(2000, 1, 1);
        $max = Carbon::today();

        if ($date->lt($min) || $date->gt($max)) {
            $validator->errors()->add('old_year', '日付は2000年1月1日から本日までにしてください。');
        }
    });
}
}
