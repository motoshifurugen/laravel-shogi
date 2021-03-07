<?php

$start = [
    'start' => [
        0 => [
            0 => [
                'turn' => 0,
                'piece' => 0,
                'square' => 59,
            ]
        ],
        1 => [
            0 => [
                'turn' => 1,
                'piece' => 0,
                'square' => 51,
            ]
        ],
    ]
];

for ($i=1; $i<10; $i++) {
    $start['start'][0][$i] = [
        'turn' => 0,
        'piece' => $i,
        'square' => $i . 7
    ];
    $start['start'][1][$i] = [
        'turn' => 1,
        'piece' => $i,
        'square' => $i . 3
    ];
}

return $start;


