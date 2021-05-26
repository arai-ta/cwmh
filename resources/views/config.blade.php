<x-layout>

<x-slot name="title">
    Config
</x-slot>

<x-slot name="header">
    {{ config('app.name') }}
</x-slot>

<h2>設定</h2>

    <h3>ステップ1</h3>
@if ($hook)
    <p>通知を保存するチャットルームが作成されました。</p>
    <p><a href="{{$serviceUrl->toRoomLink($hook)}}" target="_blank">Chatworkで確認する</a></p>
    <p>チャットルームにアクセスできない場合などは以下から再作成してください。</p>
@else
    <p>通知を保存する専用のチャットルームを作成します。</p>
@endif
<form action="setroom" method="POST">
    <label>チャットルームの名前：<input type="text" name="roomname" value="通知履歴"></label>
    <input type="submit" value="チャットルームを作成する">
</form>

    <h3>ステップ2</h3>
@if (is_null($hook))
    <p>先にステップ1を実施してください。</p>
@else
    @if ($hook->webhook_id)
        <p>Webhook設定済みです。</p>
        <p>変更する場合は以下を再度実施してください。</p>
    @else
        <p>Chatworkのサービス連携でWebhookを新規作成してください。</p>
    @endif
    <a href="{{$serviceUrl->webhookCreate()}}" target="_blank">Chatworkのサービス連携設定画面を開く</a>
    <p>設定値は次の通りにしてください。</p>
    <dl>
        <dt>Webhook名</dt>
        <dd>任意の分かりやすい名前を設定してください。<br>例：通知履歴用</dd>
        <dt>Webhook URL</dt>
        <dd>次のURLを設定してください。<br>
            <label>URL：
                <input type="text" readonly="readonly" value="{{url("/hook/{$hook->key}", [], true)}}"
                style="width:40em" onclick="this.select()">
            </label>
        </dd>
        <dt>イベント</dt>
        <dd>「アカウントイベント」を選択してください。</dd>
    </dl>
@endif

    <h3>ステップ3</h3>
@if (is_null($hook))
    <p>先にステップ1を実施してください。</p>
@else
    @if ($hook->webhook_id)
        <p>Webhook設定済みです。</p>
        <a href="{{$serviceUrl->webhookModify($hook)}}" target="_blank">Chatworkで設定を確認する</a>
        <p>変更する場合はステップ2から再実施してください。</p>
    @else
        <p>Webhookの作成完了画面に表示される設定をここに記入してください。</p>
    @endif
    <form action="setwebhook" method="POST">
        <ul>
            <li><label>Webhook設定ID：<input type="text" name="webhookid" value="{{$hook->webhook_id ?: ''}}"></label></li>
            <li><label>トークン：<input type="text" name="webhooktoken" value="{{$hook->token}}"></label></li>
        </ul>
        <input type="submit" value="Webhook設定を保存する">
    </form>
@endif

<h2>状態</h2>
    <h3>設定状況</h3>
    <ul>
        <li>通知先チャットルーム：{{isset($hook) ? "作成済" : "未作成"}}</li>
        <li>Webhook設定：{{isset($hook->token) ? "設定済" : "未設定"}}</li>
    </ul>

    <h3>利用状況</h3>
    <ul>
        <li>通知記録回数：{{$totalKicks}}</li>
        <li>最後の通知日時：{{$lastKick->created_at ?? "未実行"}}</li>
        <li>最後の通知結果：{{$lastKick->result ?? "未実行"}}</li>
    </ul>

<h2>利用停止</h2>
    <p><a href="logout">ログアウトはこちらから。</a></p>
    <p>このアプリの利用を止めるには、次の手順で連携を解除してください。</p>

    <ol>
        @if (isset($hook->webhook_id))
        <li>Webhookの設定を削除してください。（<a href="{{$serviceUrl->webhookDelete($hook)}}" target="_blank">Chatworkで開く</a>）</li>
        @else
        <li>Webhookの設定があれば削除してください。（<a href="{{$serviceUrl->webhookList()}}" target="_blank">Chatworkで開く</a>）</li>
        @endif
        <li>OAuth認証サービスの一覧からこのアプリの権限を削除してください。（<a href="{{$serviceUrl->oauthGrantedApps()}}" target="_blank">Chatworkで開く</a>）</li>
        @if (isset($hook))
        <li>通知先のチャットルームを削除してください。(<a href="{{$serviceUrl->toRoomLink($hook)}}" target="_blank">Chatworkで確認する</a>)</li>
        @endif
    </ol>

</x-layout>
