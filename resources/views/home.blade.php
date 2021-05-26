<x-layout>

<x-slot name="title">
    Home
</x-slot>

<x-slot name="header">
    {{ config('app.name') }}
</x-slot>

<h2>これは何？</h2>

<p>Chatworkの通知履歴を保存・閲覧できるオープンソースなwebアプリケーションです。</p>
<p>「通知されたメッセージを既読にして見失ってしまう」という問題を解決します。</p>

<h2>特徴</h2>

<ul>
<li>通知の種類（To, Reply, ToALL, DirectChat）を判別してアイコン表示します。</li>
<li>Chatwork APIを利用しChatwork自体に履歴を保存するためシームレスに使えます。</li>
<li>利用設定がWebブラウザで完結します。</li>
</ul>


<h2>利用するには</h2>

<p>OAuth2.0とWebhookの機能を使ってChatworkと連携します。</p>
<p>必要な権限は次のとおりです。</p>
<dl>
    <dt>永続的なAPIアクセスの許可 (<code>offline_access</code>)</dt>
    <dd>このアプリを永続的に動かすために必要です。</dd>
    <dt>自分のプロフィール情報の取得 (<code>users.profile.me:read</code>)</dt>
    <dd>個別のアカウント情報を識別するために必要です。</dd>
    <dt>自分が参加しているチャットルーム一覧の取得 (<code>rooms.info:read</code>)</dt>
    <dd>通知されたチャットルーム名を取得するために必要です。</dd>
    <dt>チャットルームの作成と参加しているチャットルームの削除 (<code>rooms:write</code>)</dt>
    <dd>通知先のチャットルーム作成のために必要です。削除はしません。</dd>
    <dt>自分が参加しているチャットルームへのメッセージ投稿 (<code>rooms.messages:write</code>)</dt>
    <dd>通知履歴の保存のために必要です。</dd>
</dl>
<p><a href="./start">こちらからサービス連携を有効にしてください。</a></p>

<h2>注意事項</h2>

<ul>
<li>このソフトウェアは無料で使用できます。</li>
<li>このソフトウェアは無保証です。 使用により損害などが発生しても作成者は責任を負いません。</li>
<li>プログラムのソース及び正式なライセンスはこちらで公開されています。 (<a href="{{$sourceUrl}}">{{$sourceUrl}}</a>)</li>
</ul>

</x-layout>
