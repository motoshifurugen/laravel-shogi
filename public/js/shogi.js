var movePiece = function (square) {
    // クリックされたボタンのマスを取得
    const data = document.getElementById('data'+square);
    // そのマスを「move」と名付ける
    data.name = 'move';
    // フォームのデータを送信する。
    document.moveform.submit();
}
