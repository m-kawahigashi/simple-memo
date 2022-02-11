@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
    <h5 class="card-header">メモ編集</h5>
        <form class="card-body" action={{ route('update') }} method="POST">
            @csrf
            <input type="hidden" name="memo_id" value="{{ $edit_memo['id'] }}" />
            <div class="mb-3">
                <textarea class="form-control" name="content" placeholder="変更内容を入力" rows="3">{{ $edit_memo['content'] }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">編集</button>
        </form>
    </div>
</div>
@endsection
