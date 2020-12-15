<a href="{{ config('app.url') }}">{{ config('app.name') }}</a>
<p>
    {{ __('PDR-memo運営者のharukaristです。いつもご利用いただきありがとうございます。') }}<br>
</p>
<p>
    {{ __('下記のURLをクリックして新しいメールアドレスを確定してください。') }}<br>
</p>
<p>
    {{ $actionText }}: <a href="{{ $actionUrl }}">{{ $actionUrl }}</a>
</p>

<p>
    {{ __('※URLの有効期限は1時間以内です。有効期限が切れた場合は、お手数ですがもう一度最初からお手続きを行ってください。') }}<br>
</p>
