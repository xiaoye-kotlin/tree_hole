<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        @font-face {
            font-family: BearFont;
            src: url('ttf/bear.ttf') format('truetype');
        }
        h1 {
            font-family: BearFont, Arial, sans-serif;
            text-align: center;
            color: #333;
            font-size: 5vh;
        }
        h2 {
            font-family: BearFont, Arial, sans-serif;
            text-align: center;
            color: #333;
            font-size: 3vh;
        }
               .navbar {
    background-color: #007BFF;
    overflow: hidden;
}
.nav-link {
    border: 2px solid #000; /* 宽度、样式、颜色 */
    padding: 10px; /* 为了在内容和边框之间增加空间，你可以添加内边距 */
    float: left;
    display: block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}

.nav-link:hover {
    background-color: #0056b3;
}
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
        }
        #nameInput {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        #messageInput {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        #contactInput {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        #submitButton {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }
        #loadingOverlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #loadingMessage {
            background: white;
            padding: 20px;
        }
    </style>
</head>
<body>
<div class="navbar">
    <a href="index.php" class="nav-link">我要查询</a>
</div>
<div class="container">
    <h1>一双小树洞</h1>
    <form id="messageForm" action="post_api.php" method="post">
        <input type="text" id="nameInput" name="name" placeholder="输入ta的名字（仅限中文）" required pattern="[\u4e00-\u9fa5]+">
        <textarea id="messageInput" name="message" placeholder="输入您的留言" rows="4" required></textarea>
        <input type="text" id="contactInput" name="contact" placeholder="选填：输入您的联系方式">
        <button type="submit" id="submitButton">提交留言</button>
    </form>
<div id="loadingOverlay" style="display: none;">
    <div id="loadingMessage">正在提交...</div>
</div>
</div>
</body>
<footer>
    <p style="text-align: center; font-size: 12px; color: #777;">
        By 重庆第一双语学校学生墙
    </p>
</footer>
<script>
    // 定义变量以存储上一次提交的留言内容
    var lastSubmittedMessage = '';

    document.getElementById('messageForm').addEventListener('submit', function (e) {
    e.preventDefault(); // 防止表单默认提交行为

    // 获取名字、留言和联系方式
    var name = document.getElementById('nameInput').value;
    var message = document.getElementById('messageInput').value;
    var contact = document.getElementById('contactInput').value;

    // 检查留言内容是否与上次相同
    if (message === lastSubmittedMessage) {
        alert('请不要提交相同的留言！');
        return; // 阻止提交
    }

    // 创建一个包含数据的对象
    var data = {
        name: name, // 新增name字段
        message: message,
        contact: contact
    };

    // 显示加载提示框
    var loadingOverlay = document.getElementById('loadingOverlay');
    loadingOverlay.style.display = 'block';

    if (name !== "" && message !== "") {
        const keywords = ["操", "逼", "滚"];
        let containsKeyword = false;

        for (const keyword of keywords) {
            if (name.indexOf(keyword) !== -1 || message.indexOf(keyword) !== -1) {
                containsKeyword = true;
                break; // 如果找到一个关键字就中断循环
            }
        }

        if (!containsKeyword) {
            fetch('post_api.php', {
                method: 'POST',
                body: JSON.stringify(data),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // 处理API响应，例如显示成功消息
                alert(data.message);
                lastSubmittedMessage = message; // 更新上一次提交的留言内容
                document.getElementById('nameInput').value = '';
                document.getElementById('messageInput').value = '';
                document.getElementById('contactInput').value = '';
            })
            .catch(error => {
                // 处理错误，例如显示错误消息
                console.error(error);
                alert('提交失败');
            })
            .finally(() => {
                // 隐藏加载提示框
                loadingOverlay.style.display = 'none';
            });
        } else {
            alert('留言中包含违禁词，请重新编辑');
            loadingOverlay.style.display = 'none'; // 隐藏加载提示框
        }
    } else {
        alert('请确保必要项不为空');
        loadingOverlay.style.display = 'none'; // 隐藏加载提示框
    }
});
</script>
</html>
