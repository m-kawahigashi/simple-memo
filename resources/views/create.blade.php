@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
    <h5 class="card-header">新規メモ入力</h5>
        <form class="card-body" action={{ route('store') }} method="POST">
            @csrf
            <div class="mb-3">
                <textarea class="form-control" name="content" placeholder="ここにメモを入力" rows="3"></textarea>
            </div>
        <!-- タグ一覧 -->
        @foreach ($tags as $tag)
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="tags[]" id={{ $tag['id'] }} value={{ $tag['id'] }}>
                <label class="form-check-label" for={{ $tag['id'] }}>{{ $tag['name'] }}</label>
            </div>
        @endforeach
            <input type="text" class="form-control w-50 mb-3" name="new_tag" placeholder="新しいタグを入力" />
            <button type="submit" class="btn btn-primary">登録</button>
        </form>
    </div>
</div>
@endsection
