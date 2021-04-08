<!DOCTYPE html>
<html>
<head>
    <title>将棋アプリ</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script type="text/javascript" src="{{ asset('js/shogi.js') }}"></script>
</head>
<body>
    <div class="board">
        <form action="/shogi" method="POST" name="moveform">
            {{ csrf_field() }}
            <?php
            $bPiece = $wPiece = array();
            if ($selectPiece['turn'] == 0) {
                for ($pieceId = 0; $pieceId < 10; $pieceId++) {
                    if ($selectPiece['piece'] == $pieceId) {
                        $bPiece[$pieceId] = 'selected';
                    } else {
                        $bPiece[$pieceId] = 'disabled';
                    }
                    $wPiece[$pieceId] = 'disabled';
                }
            } else {
                for ($pieceId = 0; $pieceId < 10; $pieceId++) {
                    if ($selectPiece['piece'] == $pieceId) {
                        $wPiece[$pieceId] = 'selected';
                    } else {
                        $wPiece[$pieceId] = 'disabled';
                    }
                    $bPiece[$pieceId] = 'disabled';
                }
            }
            for ($c = 1; $c < 10; $c++) {
                echo '<div class="column column' . $c . '" id="column' . $c . '">';
                for ($r = 9; $r > 0; $r--) {
                    $square = $r . $c;
                    if (in_array($square, $way)) {
                        if (($selectPiece['turn'] == 0) && !empty($wPawn[1][$r]) &&($square == $wPawn[1][$r]['square'])) {
                            // 後手の歩をとる
                            echo '<input type="hidden" value="' . $square . '" name="square" id="data' . $square . '"></input>';
                            echo '<input type="hidden" value="' . $selectPiece['turn'] . '" name="turn"></input>';
                            echo '<input type="hidden" value="' . $selectPiece['piece'] . '" name="piece"></input>';
                            echo '<input type="hidden" value="' . $wPawn[1][$r]['piece'] . '" name="piece"></input>';
                            echo '<p class="row square' . $square . ' way" id="square' . $square . '"><input type="button" value="go" onclick="takePiece(' . $square . ')"/></p>';
                        } elseif (($selectPiece['turn'] == 1) && !empty($bPawn[0][$r]) && ($square == $bPawn[0][$r]['square'])) {
                            // 先手の歩をとる
                            echo '<input type="hidden" value="' . $square . '" name="square" id="data' . $square . '"></input>';
                            echo '<input type="hidden" value="' . $selectPiece['turn'] . '" name="turn"></input>';
                            echo '<input type="hidden" value="' . $selectPiece['piece'] . '" name="piece"></input>';
                            echo '<input type="hidden" value="' . $wPawn[1][$r]['piece'] . '" name="piece"></input>';
                            echo '<p class="row square' . $square . ' way" id="square' . $square . '"><input type="button" value="go" onclick="takePiece(' . $square . ')"/></p>';
                        } elseif (($selectPiece['turn'] == 0) && ($square == $wKing['square'])) {
                            // 玉を取る
                            echo '<input type="hidden" value="' . $square . '" name="square" id="data' . $square . '"></input>';
                            echo '<input type="hidden" value="' . $selectPiece['turn'] . '" name="turn"></input>';
                            echo '<input type="hidden" value="' . $selectPiece['piece'] . '" name="piece"></input>';
                            echo '<input type="hidden" value="' . $wKing['piece'] . '" name="piece"></input>';
                            echo '<p class="row square' . $square . ' way" id="square' . $square . '"><input type="button" value="go" onclick="takePiece(' . $square . ')"/></p>';
                        } elseif (($selectPiece['turn'] == 1) && ($square == $bKing['square'])) {
                            // 王を取る
                            echo '<input type="hidden" value="' . $square . '" name="square" id="data' . $square . '"></input>';
                            echo '<input type="hidden" value="' . $selectPiece['turn'] . '" name="turn"></input>';
                            echo '<input type="hidden" value="' . $selectPiece['piece'] . '" name="piece"></input>';
                            echo '<input type="hidden" value="' . $bKing['piece'] . '" name="piece"></input>';
                            echo '<p class="row square' . $square . ' way" id="square' . $square . '"><input type="button" value="go" onclick="takePiece(' . $square . ')"/></p>';
                        } else {
                            // 移動可能マス
                            echo '<input type="hidden" value="' . $square . '" name="square" id="data' . $square . '"></input>';
                            echo '<input type="hidden" value="' . $selectPiece['turn'] . '" name="turn"></input>';
                            echo '<input type="hidden" value="' . $selectPiece['piece'] . '" name="piece"></input>';
                            echo '<p class="row square' . $square . ' way" id="square' . $square . '"><input type="button" value="go" onclick="movePiece(' . $square . ')"/></p>';
                        }
                    } elseif ($square == $bKing['square']) {
                        // 駒マス「王将」
                        echo '<p class="piece row turnb square' . $square . ' ' . $bPiece[$bKing['piece']] . '" id="square' . $square . '">王</p>';
                    } elseif ($square == $wKing['square']) {
                        // 駒マス「玉将」
                        echo '<p class="piece turnw row square' . $square . ' ' . $wPiece[$wKing['piece']] . '" id="square' . $square . '">玉</p>';
                    } elseif (!empty($bPawn[0][$r]) && ($square == $bPawn[0][$r]['square'])) {
                        // 駒マス「歩」（先手）
                        echo '<p class="piece row turnb square' . $square . ' ' . $bPiece[$bPawn[0][$r]['piece']] . '" id="square' . $square . '">歩</p>';
                    } elseif (!empty($wPawn[1][$r]) && ($square == $wPawn[1][$r]['square'])) {
                        // 駒マス「歩」（後手）
                        echo '<p class="piece row turnw square' . $square . ' ' . $wPiece[$wPawn[1][$r]['piece']] . '" id="square' . $square . '">歩</p>';
                    } else {
                        // それ以外のマス
                        echo '<p class="row square' . $square . '" id="square' . $square . '">' . $square . '</p>';
                    }
                }
                echo '</div>';
            }
            ?>
        </form>
    </div>
</body>
</html>
