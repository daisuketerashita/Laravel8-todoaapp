@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">新規メモ作成</div>
    <form class="card-body" action="{{ route('store') }}" method="post">
    @csrf
        <div class="form-group">
            <textarea name="content" rows="3" class="form-control" placeholder="ここにメモを入力"></textarea>
        </div>
        <input type="text" class="form-control w-50 mb-3" name="new_tag" placeholder="新しいタグを入力">
        <button type="submit" class="btn btn-primary">保存</button>
    </form>
</div>
@endsection
