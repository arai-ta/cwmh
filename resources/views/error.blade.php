<x-layout>

<x-slot name="title">
    Error
</x-slot>

<x-slot name="header">
    {{ env('APP_NAME') }}
</x-slot>

<h2>エラーが起きました</h2>

<h3>エラー内容</h3>
<p>{{ $message }}</p>

<p><a href="./">初めからやり直してください。</a></p>

</x-layout>
