<x-layout>

<x-slot name="title">
    Error
</x-slot>

<x-slot name="header">
    {{ env('APP_NAME') }}
</x-slot>

<h2>設定</h2>
@if (is_null($hook))
    <p>まだ初期設定されていません。</p>
        <form action="config" method="POST">
            <label>通知を受け取る部屋の名前<input type="text" name="roomname" value="メンション履歴"></label>
            <input type="submit" value="送信">
        </form>
@else
    <p>設定されています</p>
    <pre>{{var_dump($hook->toArray())}}</pre>
@endif
</x-layout>
