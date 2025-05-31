<?php
$questionSet = [];
$json = file_get_contents('.\questions.json');
$questArr = (json_decode($json, true));
foreach ($questArr['general'] as $q) {
    $questionSet[] = [
        'id' => $q['id'],
        'text' => $q['question'],
    ];
}
//var_dump($questionSet);
echo $questionSet[4]['text'];
