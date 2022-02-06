@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
    <h5 class="card-header">新規メモ入力</h5>
        <form class="card-body" action="store" method="POST">
            @csrf
            <div class="mb-3">
                <textarea class="form-control" name="content" placeholder="ここにメモを入力" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">登録</button>
        </form>
    </div>
</div>
@endsection
