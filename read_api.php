<?php
$messages = [];

$dataDirectory = 'data/';
$files = glob($dataDirectory . '*.json');

if (isset($_GET['name'])) {
    $nameToSearch = $_GET['name'];

    // 检查 URL 中是否存在 'key' 参数，并且其值是否等于 '1640432'
    $allowContact = (isset($_GET['key']) && $_GET['key'] === '1640432');

    foreach ($files as $file) {
        $json = file_get_contents($file);
        $messageData = json_decode($json, true);

        // 根据名字过滤留言
        if ($messageData['name'] === $nameToSearch) {
            // 根据 'allowContact' 变量来判断是否排除 'contact' 数据
            if (!$allowContact) {
                unset($messageData['contact']); // 移除 'contact' 键
            }
            $messages[] = $messageData;
        }
    }
} else {
    // 如果未提供名字参数，返回所有留言
    foreach ($files as $file) {
        $json = file_get_contents($file);
        $messageData = json_decode($json, true);
        // 根据 'allowContact' 变量来判断是否排除 'contact' 数据
        if (!$allowContact) {
            unset($messageData['contact']); // 移除 'contact' 键
        }
        $messages[] = $messageData;
    }
}

// 返回JSON格式的留言数据
header('Content-Type: application/json');
echo json_encode($messages);
?>
