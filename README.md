cwmh -- Chatwork Mention History
================================

`cwmh` はChatworkの通知履歴を保存・閲覧できるオープンソースなwebアプリケーションです。
「通知されたメッセージを既読にして見失ってしまう」という問題を解決します。

## 特徴

- 通知の種類（To, Reply, ToALL, DirectChat）を判別してアイコン表示します。
- Chatwork APIを利用しChatwork自体に履歴を保存するたサービスとシームレスに使えます。
- 利用設定がWebブラウザで完結します。比較的簡単に使い始めることができます。

## デモサイト

https://cwmh.herokuapp.com

## ライセンス

[GNU AGPL v3.](./LICENSE.txt)

# 使い方

## アプリを利用する

Webブラウザでデモサイト等にアクセスしてください。
使い方を各ページ内に記載しています。

## アプリを設置する

確認した動作環境は次の通りです。

- php 7.3
- MySQL 8

動作に必要な環境変数は[.env.example](./.env.example)を参照してください。

## アプリを開発する

Dockerで開発環境が構築できます。
.envに環境変数を設定し、次のコマンドを実行してください。

    $ docker compose up -d
    $ docker compose run php composer install
    $ docker compose run php ./artisan migrate

成功すると http://localhost:80/ で開発環境が起動します。

### 開発時のSSLとサービス公開について

ChatworkのOAuthクライアントのリダイレクトエンドポイントはSSL(https)が必須になっています。
またWebhookのリクエストを受信するにはサービスを公開する必要がある点に注意してください。
[ngrok](https://ngrok.com)を利用するとその両方を手軽に用意することができるのでおすすめです。
