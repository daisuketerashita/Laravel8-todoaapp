@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        メモ編集
        <form action="{{ route('destroy') }}" method="post">
            @csrf
            <input type="hidden" name="memo_id" value="{{ $edit_memo[0]['id'] }}">
            <button type="submit">削除</button>
        </form>
    </div>
    <form class="card-body" action="{{ route('update') }}" method="post">
    @csrf
    <input type="hidden" value="{{ $edit_memo[0]['id'] }}" name="memo_id">
        <div class="form-group">
            <textarea name="content" rows="3" class="form-control" placeholder="ここにメモを入力">{{ $edit_memo[0]['content'] }}</textarea>
        </div>
        @foreach($tags as $t)
        <div class="form-check form-check-inline mb-3">
            <input type="checkbox" name="tags[]" id="{{ $t['id'] }}" value="{{ $t['id'] }}" class="form-check-input" checked>
            <label for="{{ $t['id'] }}" class="form-check-label">{{ $t['name'] }}</label>
        </div>
        @endforeach
        <br>
        <button type="submit" class="btn btn-primary">更新</button>
    </form>
</div>
@endsection
