<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use App\EmailReset;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateEmailChange;

// メールアドレスの変更
class ChangeEmailController extends Controller
{
    public function showChangeEmailForm()
    {
        return view('profile.change');
    }

    public function sendChangeEmailLink(CreateEmailChange $request)
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
    /**
     * メールアドレスの再設定処理
     *
     * @param Request $request
     * @param [type] $token
     */
    public function reset(Request $request, $token)
    {
        // トークンが一致するレコードを取得
        $email_resets = DB::table('email_resets')
            ->where('token', $token)
            ->first();

        // トークンが存在している、かつ、有効期限が切れていないかチェック
        if ($email_resets && !$this->tokenExpired($email_resets->created_at)) {

            // ユーザーのメールアドレスを更新
            $user = User::find($email_resets->user_id);
            $user->email = $email_resets->new_email;
            $user->save();

            // レコードを削除
            DB::table('email_resets')
                ->where('token', $token)
                ->delete();

            return redirect('/home')->with('flash_message', 'メールアドレスを更新しました');
        } else {
            // レコードが存在していた場合は削除
            if ($email_resets) {
                DB::table('email_resets')
                    ->where('token', $token)
                    ->delete();
            }
            return redirect('/home')->with('flash_message', 'メールアドレスの更新に失敗しました。時間を置いて再度お試しください。');
        }
    }


    /**
     * トークンが有効期限切れかどうかチェック
     *
     * @param  string  $createdAt
     * @return bool
     */
    protected function tokenExpired($createdAt)
    {
        // トークンの有効期限は60分に設定
        $expires = 60 * 60;
        return Carbon::parse($createdAt)->addSeconds($expires)->isPast();
    }
}
