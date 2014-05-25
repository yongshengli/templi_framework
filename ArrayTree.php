<?php
function tree($source)
{
    $result = [];
    //定义索引数组，用于记录节点在目标数组的位置，类似指针
    $points = [];

    foreach ($source as $region) {
        if ($region['parentId'] == 0) {
            $i = count($result);
            $points[$region['id']] = $result[$i] = isset($points[$region['id']]) ?
                array_merge($region, $points[$region['id']]) : $region;
            $points[$region['id']] = & $result[$i];

        } else {
            if (!isset($points[$region['parentId']]['children'])) {
                $points[$region['parentId']]['children'] = [];
            }
            $i = count($points[$region['parentId']]['children']);
            $points[$region['parentId']]['children'][$i] = $region;
            $points[$region['id']] = & $points[$region['parentId']]['children'][$i];
        }
    }
    return $result;
}
/*$a = [
    [
        'id' => 1,
        'name' => '一',
        'parentId' => 0
    ],
    [
        'id' => 2,
        'name' => '二',
        'parentId' => 0
    ],
    [
        'id' => 3,
        'name' => '三',
        'parentId' => 1
    ],
    [
        'id' => 4,
        'name' => '四',
        'parentId'=> 1
    ],
    [
        'id' => 5,
        'name' => '五',
        'parentId' => 3
    ],
    [
        'id' =>6,
        'name' => '六',
        'parentId' =>3
    ],
    [
        'id' => 7,
        'name' => '七',
        'parentId' => 6
    ],
    [
        'id' => 8,
        'name' => '八',
        'parentId' => 7
    ]
];

print_r(tree($a));
*/