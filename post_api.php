<?php
// 检查是否是POST请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取POST数据
    $data = json_decode(file_get_contents('php://input'), true);

    // 确保数据有效，包括message和name
    if (isset($data['message']) && isset($data['name'])) {
        $message = $data['message'];
        $name = $data['name'];
        $contact = isset($data['contact']) ? $data['contact'] : 'null'; // 如果联系方式不存在，默认为空字符串
        $timestamp = date('Y-m-d H:i:s');

        // 创建一个关联数组
        $messageData = [
            'message' => $message,
            'name' => $name, // 新增name字段
            'contact' => $contact,
            'timestamp' => $timestamp,
        ];

        // 将数据保存为JSON文件
        $filename = 'data/' . strtotime($timestamp) . '.json'; // 使用时间戳作为文件名
        file_put_contents($filename, json_encode($messageData));

        // 返回成功响应
        http_response_code(200);
        echo json_encode(['message' => '您的留言提交成功，一旦提交不能自行删除，请悉知！']);
    } else {
        // 返回错误响应
        http_response_code(400);
        echo json_encode(['error' => '您的留言提交失败，请尝试重新提交！']);
    }
} else {
    // 返回错误响应
    http_response_code(405);
    echo json_encode(['error' => '您的浏览器好像不支持，请使用更高版本的浏览器或更换浏览器！']);
}
?>
