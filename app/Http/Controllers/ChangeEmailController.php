<?php

namespace App\Http\Controllers;

use App\EmailReset;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ChangeEmailController extends Controller
{
    public function showChangeEmailForm()
    {
        return view('profile.reset');
    }

    public function sendChangeEmailLink(Request $request)
    {
        // フォームに入力された新しいメールアドレスを格納
        $new_email = $request->new_email;

        // hash_hmac()でトークンとなるハッシュ値を生成
        $token = hash_hmac(
            'sha256',
            Str::random(40) . $new_email,
            config('app.key')
        );

        // 新しいメールアドレスとトークンをDBに保存
        DB::beginTransaction();
        try {
            $param = [];
            $param['user_id'] = Auth::id();
            $param['new_email'] = $new_email;
            $param['token'] = $token;
            $email_reset = EmailReset::create($param);

            DB::commit();

            // EmailResetのメソッドでメールリセット通知を送信
            $email_reset->sendEmailResetNotification($token);

            return redirect()->route('home')->with('flash_message', '確認メールを送信しました。');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('home')->with('flash_message', 'メール更新に失敗しました。');
        }
    }
}
