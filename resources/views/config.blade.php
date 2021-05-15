<x-layout>

<x-slot name="title">
    Config
</x-slot>

<x-slot name="header">
    {{ env('APP_NAME') }}
</x-slot>

<h2>設定</h2>

    <h3>ステップ1</h3>
@if ($hook)
    <p>通知を保存するチャットルームが作成されました。</p>
    <p><a href="{{$hook->getTargetRoomUrl()}}" target="_blank">Chatworkで確認する</a></p>
    <p>変更する場合は以下を再度実施してください。</p>
@else
    <p>通知を保存する専用のチャットルームを作成します。</p>
@endif
<form action="setroom" method="POST">
    <label>チャットルームの名前：<input type="text" name="roomname" value="通知履歴"></label>
    <input type="submit" value="チャットルームを作成する">
</form>

    <h3>ステップ2</h3>
@if (is_null($hook))
    <p>ステップ1を実施してください。</p>
@else
    @if ($hook->webhook_id)
        <p>Webhook設定済みです。</p>
        <p>変更する場合は以下を再度実施してください。</p>
    @else
        <p>Chatworkのサービス連携でWebhookを新規作成してください。</p>
    @endif
    <a href="https://www.chatwork.com/service/packages/chatwork/subpackages/webhook/create.php" target="_blank">Chatworkのサービス連携設定画面を開く</a>
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
    <p>ステップ1を実施してください。</p>
@else
    @if ($hook->webhook_id)
        <p>Webhook設定済みです。</p>
        <a href="https://www.chatwork.com/service/packages/chatwork/subpackages/webhook/modify.php?id={{$hook->webhook_id}}" target="_blank">Chatworkで設定を確認する</a>
        <p>変更する場合は以下を再度実施してください。</p>
    @else
        <p>作成したWebhookの設定をここに記入してください。</p>
    @endif
    <form action="setwebhook" method="POST">
        <ul>
            <li><label>Webhook設定ID：<input type="text" name="webhookid" value="{{$hook->webhook_id ?: ''}}"></label></li>
            <li><label>トークン：<input type="text" name="webhooktoken" value="{{$hook->token}}"></label></li>
        </ul>
        <input type="submit" value="Webhook設定を保存する">
    </form>
@endif

</x-layout>
