@extends('layouts.app')

@section('javascript')
<script src="/js/confirm.js"></script>
@endsection

@section('content')
<div class="container">
    <div class="card">
    <h5 class="card-header">メモ編集</h5>
        <form class="card-body" action={{ route('update') }} method="POST">
            @csrf
            <input type="hidden" name="memo_id" value="{{ $edit_memo[0]['id'] }}" />
            <div class="mb-3">
                <textarea class="form-control" name="content" placeholder="変更内容を入力" rows="3">{{ $edit_memo[0]['content'] }}</textarea>
            </div>
            @error('content')
                <div class="alert alert-danger">メモを入力してください</div>
            @enderror
            <!-- タグ一覧 -->
            @foreach ($tags as $tag)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="tags[]" id={{ $tag['id'] }} value={{ $tag['id'] }} {{ in_array($tag['id'], $include_tags) ? 'checked' : '' }}>
                    <label class="form-check-label" for={{ $tag['id'] }}>{{ $tag['name'] }}</label>
                </div>
            @endforeach
            <input type="text" class="form-control w-50 mb-3" name="new_tag" placeholder="新しいタグを入力" />
            <button type="submit" class="btn btn-primary">編集</button>
        </form>
        <form class="card-body" id="delete-form" action={{ route('destroy') }} method="POST">
            @csrf
            <input type="hidden" name="memo_id" value="{{ $edit_memo[0]['id'] }}" />
            <button type="submit" class="btn btn-primary" onclick="deleteHandle(event);">削除</button>
        </form>
    </div>
</div>
@endsection
