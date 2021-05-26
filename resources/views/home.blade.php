<x-layout>

<x-slot name="title">
    Home
</x-slot>

<x-slot name="header">
    {{ config('app.name') }}
</x-slot>

    <main>
        <div class="container">
            <div class="p-5 rounded">
                <div class="col-sm-8 mx-auto">
                    <h1>{{ config('app.name') }}</h1>
                    <p>cwmhはChatworkの通知履歴を保存するOSSのwebアプリです。</p>
                    <p>もう通知されたメッセージを見失いません。</p>
                    <p>
                        <a class="btn btn-primary" href="/start" role="button">使いはじめる »</a>
                        <!-- <a class="btn btn-secondary" href="/start?kcw=1" role="button">KDDI Chatworkを利用中の方はこちら »</a> -->
                    </p>
                </div>
            </div>
            <div class="bg-light p-5 rounded">
                <div class="col-sm-8 mx-auto">
                    <h2>特徴</h2>
                    <div class="row">
                        <div class="col-lg-4">
                            <svg class="bd-placeholder-img rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 140x140" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#777"/><text x="50%" y="50%" fill="#777" dy=".3em">140x140</text></svg>

                            <h3>分かりやすい表示</h3>
                            <p>通知の種類を判別してアイコン表示します。</p>
                        </div><!-- /.col-lg-4 -->
                        <div class="col-lg-4">
                            <svg class="bd-placeholder-img rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 140x140" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#777"/><text x="50%" y="50%" fill="#777" dy=".3em">140x140</text></svg>

                            <h3>スムーズな連携</h3>
                            <p>Chatwork自体に履歴を保存するため履歴をシームレスに閲覧できます。</p>
                        </div><!-- /.col-lg-4 -->
                        <div class="col-lg-4">
                            <svg class="bd-placeholder-img rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 140x140" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#777"/><text x="50%" y="50%" fill="#777" dy=".3em">140x140</text></svg>

                            <h3>3ステップで開始</h3>
                            <p>利用設定がWebブラウザで完結します。</p>
                        </div><!-- /.col-lg-4 -->
                    </div>
                </div>


            </div>

        </div>


    </main>



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
