<?php

$start = [
    'start' => [
        'bKing' => [
            'turn' => 0,
            'piece' => 0,
            'square' => 59,
        ],
        'wKing' => [
            'turn' => 1,
            'piece' => 0,
            'square' => 51,
        ],
    ]
];

for ($i=1; $i<10; $i++) {
    $start['start']['bPawn'][$i] = [
        'turn' => 0,
        'piece' => $i,
        'square' => $i . 7
    ];
    $start['start']['wPawn'][$i] = [
        'turn' => 1,
        'piece' => $i,
        'square' => $i . 3
    ];
}

return $start;


