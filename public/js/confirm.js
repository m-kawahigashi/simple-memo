function deleteHandle(event) {
    console.log(event);
    // submitを一旦停止
    event.preventDefault();
    if(window.confirm('本当に削除してもよろしいですか？')) {
        document.getElementById('delete-form').submit();
    }else{
        alert('キャンセルしました');
    }
}