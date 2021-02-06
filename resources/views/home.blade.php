<x-layout>

<x-slot name="title">
    Home
</x-slot>

<x-slot name="header">
    {{ env('APP_NAME') }}
</x-slot>

<h2>これは何？</h2>

<p>Chatworkの通知履歴を保存・閲覧できるオープンソースなwebアプリケーションです。</p>
<p>「通知されたメッセージを既読にして見失ってしまう」という問題を解決します。</p>
<p>ソースはこちらで公開されています。</p>
<p><a href="https://github.com/arai-ta/cwmh">https://github.com/arai-ta/cwmh</a></p>


<h2>利用するには</h2>

<p>OAuth2.0とWebhookの機能を使ってChatworkと連携します。</p>
<p><a href="./start">こちらからサービス連携を有効にしてください。</a></p>

<h2>注意事項</h2>

<ul>
<li>このソフトウェアは無保証です。
使用により損害などが発生しても作成者は責任を負いません。</li>
</ul>

</x-layout>
