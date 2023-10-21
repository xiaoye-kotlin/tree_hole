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
            border: 2px solid #000;
            padding: 10px;
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
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .welcome {
            text-align: center;
            font-size: 4vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: white;
            z-index: 2;
        }
        .welcomeText {
            z-index: 2;
        }
        #nameInput {
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
        #messageList {
            display: none;
            max-height: 80vh;
            overflow-y: auto;
        }
        #messages {
            list-style-type: none;
            padding: 0;
        }
        #messages li {
            margin: 20px 0;
            border-top: 1px solid #ccc;
            padding-top: 20px;
        }
        #pagination {
            text-align: center;
            margin-top: 20px;
        }
        .hidden {
            display: none;
        }
        .page-button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 5px 15px;
            cursor: pointer;
            margin: 0 5px;
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
        .read-more-button {
            color: blue;
            cursor: pointer;
            text-align: right;
            display: block; /* 防止横向扩展 */
        }
        .message-content {
            overflow: hidden; /* 隐藏内容外部的滚动条 */
        }
        .message-box {
            border: 1px solid #ccc; /* 加边框 */
            padding: 10px;
            margin: 10px 0;
            overflow: hidden;
            word-wrap: break-word;
        }
    </style>
</head>
<body>
<div class="navbar">
    <a href="post.php" class="nav-link">我要留言</a>
</div>
<div id="welcomeScreen" class="welcome">
    <div class="welcomeText">欢迎来到一双小树洞<br>违规言论将被删除,欢迎举报<br>本树洞由一双学生墙开发并运营</div>
</div>
<div id="selectionScreen" class="container">
    <h1>一双小树洞</h1>
    <input type="text" id="nameInput" placeholder="请输入您的名字（仅限中文）" required pattern="[\u4e00-\u9fa5]+">
    <button id="submitButton" onclick="submitName()">查看留言</button>
</div>
<div id="loadingOverlay" style="display: none;">
    <div id="loadingMessage">正在查询...</div>
</div>
<div id="messageList" class="container">
    <h2 id="nametip">留言列表</h2>
    <div id="nameError" style="color: red;"></div>
    <ul id="messages"></ul>
    <div id="pagination">
        <button class="page-button" id="prevPage" onclick="prevPage()">上一页</button>
        <span id="pageInfo" class="page-info">当前第 1 页 共 1 页</span>
        <button class="page-button" id="nextPage" onclick="nextPage()">下一页</button>
    </div>
</div>
<script>
    const messageList = document.getElementById('messageList');
    const welcomeScreen = document.getElementById('welcomeScreen');
    const nameInput = document.getElementById('nameInput');
    const prevPageButton = document.getElementById('prevPage');
    const nextPageButton = document.getElementById('nextPage');
    const pageInfo = document.getElementById('pageInfo');
    const nameError = document.getElementById('nameError');
    let currentPage = 0;
    let messages = [];
    const messagesPerPage = 10;

    setTimeout(function() {
        welcomeScreen.style.display = 'none';
    }, 3000);

    function submitName() {
        const name = nameInput.value;
        if (name.trim() === "") {
            alert('请确保必要项不为空');
        } else if (!/[\u4e00-\u9fa5]+/.test(name)) {
            alert('请输入中文！');
        } else {
            var loadingOverlay = document.getElementById('loadingOverlay');
            loadingOverlay.style.display = 'block';
            nameError.textContent = '';
            fetch('read_api.php?name=' + encodeURIComponent(name))
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    loadingOverlay.style.display = 'none';
                    const nametip = document.getElementById('nametip');
                    nametip.textContent = 'About " ' + name + ' "';
                    messageList.style.display = 'block';
                    messages = data;
                    showMessages();
                } else {
                    loadingOverlay.style.display = 'none';
                    const nametip = document.getElementById('nametip');
                    nametip.textContent = 'About " ' + name + ' "';
                    messageList.style.display = 'block';
                    const messagesContainer = document.getElementById('messages');
                    messagesContainer.innerHTML = `
                    <span style="font-size: 20px; font-family: 'Arial', sans-serif; color: red;">
                        这个名字还没有人留言哦~耐心等待吧！
                    </span>`;
                }
            })
            .catch(error => {
                console.error(error);
            });
        }
    }

    function showMessages() {
        const startIndex = currentPage * messagesPerPage;
        const endIndex = startIndex + messagesPerPage;

        const messagesContainer = document.getElementById('messages');

        messagesContainer.innerHTML = '';

        for (let i = startIndex; i < endIndex && i < messages.length; i++) {
            const message = messages[i];
            const listItem = document.createElement('li');
            const fullText = message.message;
            const truncatedText = fullText.slice(0, 300);
            const hasMore = fullText.length > 300;
            listItem.innerHTML = `
                <div class="message-box">
                    <span style="font-size: 20px; font-family: 'Arial', sans-serif;">
                        <span style="color: green;">留言内容: </span>
                        <span class="message-content ${hasMore ? 'truncated' : ''}">${hasMore ? truncatedText : fullText}</span>
                        ${hasMore ? `<button class="read-more-button" onclick="readMore(this)">查看全文</button>` : ''}
                        <span class="full-text hidden">${fullText}</span><br>
                        <span style="color: green;">留言时间: </span>${message.timestamp}
                    </span>
                </div>`;
            messagesContainer.appendChild(listItem);
        }

        const totalPages = Math.ceil(messages.length / messagesPerPage);
        pageInfo.textContent = `当前第 ${currentPage + 1} 页 共 ${totalPages} 页`;
        updatePageButtons();
    }

    function readMore(button) {
        const listItem = button.closest('.message-box');
        listItem.querySelector('.message-content').classList.add('hidden');
        listItem.querySelector('.full-text').classList.remove('hidden');
        button.style.display = 'none';
    }

    function prevPage() {
        if (currentPage > 0) {
            currentPage--;
            showMessages();
        }
    }

    function nextPage() {
        if ((currentPage + 1) * messagesPerPage < messages.length) {
            currentPage++;
            showMessages();
        }
    }

    function updatePageButtons() {
        prevPageButton.disabled = currentPage === 0;
        nextPageButton.disabled = (currentPage + 1) * messagesPerPage >= messages.length;
    }
</script>
</body>
<footer>
    <p style="text-align: center; font-size: 12px; color: #777;">
        By 重庆第一双语学校学生墙
    </p>
</footer>
</html>
