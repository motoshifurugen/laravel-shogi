<!DOCTYPE html>
<html>
<head>
    <title>将棋アプリ</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="result">
        <?php echo '<p>' . $win . 'の勝ちです</p>'; ?>
        <p class="delete"><a href="shogi/reset">最初から始める</a></p>
    </div>
</body>
</html>
