<!DOCTYPE html>
<html>
<head>
    <title>将棋アプリ</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="board">
        <?php
        for ($c = 1; $c < 10; $c++) {
            echo '<div class="column column' . $c . '" id="column' . $c . '">';
            for ($r = 9; $r > 0; $r--) {
                $square = $r . $c;
                if ($square == $MyKing) {
                    echo '<p class="piece row square' . $square . '" id="square' . $square . '"><a style="text-decoration:none" href="' . action('App\Http\Controllers\ShogiController@select', 'myKing:' . $r . ':' . $c) . '">王</a></p>';
                } else {
                    echo '<p class="row square' . $square . '" id="square' . $square . '">' . $square . '</p>';
                }
            }
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>
