<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IT 202 Project 3 – AJAX Chat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 20px auto;
        }
        h1 {
            text-align: center;
            margin-bottom: 10px;
        }
        .section {
            border: 1px solid #999;
            padding: 10px;
            margin-bottom: 20px;
            background-color: #fdf7ef;
        }
        .section-title {
            background-color: #f0d4a5;
            padding: 5px 8px;
            font-weight: bold;
            margin: -10px -10px 10px -10px;
        }
        label {
            display: block;
            margin-top: 6px;
        }
        input[type="text"], input[type="password"], textarea {
            width: 100%;
            padding: 5px;
            margin-top: 3px;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
        }
        #chat_box {
            height: 160px;
        }
        #received_chat {
            height: 120px;
        }
        #listenBtn {
            padding: 6px 14px;
            margin-top: 8px;
        }
        #updateStatus {
            margin-top: 6px;
            font-size: 0.9em;
            height: 18px;
        }
        #updateStatus.ok {
            color: green;
        }
        #updateStatus.error {
            color: red;
        }
    </style>
</head>
<body>

<h1>IT 202 Project 3 – AJAX Chat</h1>

<div class="section">
    <div class="section-title">LIST CURRENT NAMES IN DATABASE CHAT TABLE</div>
    <div id="nameList">
        Names will appear here (no live list yet).
    </div>
</div>

<div class="section">
    <div class="section-title">ENTER YOUR NAME / PASSWORD – CONTENT TRANSMITTED AS TYPED</div>

    <label for="username">Name</label>
    <input type="text" id="username" name="username">

    <label for="user_password">Password</label>
    <input type="password" id="user_password" name="user_password">

    <label for="chat_box">Content transmitted as typed</label>
    <textarea id="chat_box" name="chat_box" placeholder="Content entered here sent to your chat table entry in database"></textarea>

    <div id="updateStatus"></div>
</div>

<div class="section">
    <div class="section-title">ENTER VALID NAME &amp; RETRIEVE CHAT ON LISTEN CLICK</div>

    <label for="listen_name">Name</label>
    <input type="text" id="listen_name" name="listen_name">

    <button id="listenBtn" type="button">listen</button>

    <label for="received_chat" style="margin-top:10px;">Chat retrieved from named person goes here</label>
    <textarea id="received_chat" readonly></textarea>
</div>

<script>
function sendChat() {
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("user_password").value.trim();
    const message  = document.getElementById("chat_box").value;

    const s = document.getElementById("updateStatus");

    if (!username || !password) {
        s.textContent = "Enter name and password to update chat.";
        s.className = "error";
        return;
    }

    const formData = new FormData();
    formData.append("username", username);
    formData.append("user_password", password);
    formData.append("message", message);

    fetch("save_message.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(text => {
        if (text === "OK" || text === "OK_NEW") {
            s.textContent = "Chat updated.";
            s.className = "ok";
        } else if (text === "WRONG") {
            s.textContent = "Chat not updated – wrong name or password.";
            s.className = "error";
        } else if (text === "MISSING") {
            s.textContent = "Missing name or password.";
            s.className = "error";
        } else {
            s.textContent = "Error: " + text;
            s.className = "error";
        }
    })
    .catch(err => {
        s.textContent = "Error sending chat.";
        s.className = "error";
        console.error(err);
    });
}

document.getElementById("chat_box").addEventListener("keyup", function() {
    sendChat();
});

function loadChat() {
    const name = document.getElementById("listen_name").value.trim();
    if (!name) {
        return;
    }
    const url = "load_messages.php?name=" + encodeURIComponent(name);
    fetch(url)
        .then(response => response.text())
        .then(text => {
            document.getElementById("received_chat").value = text;
        })
        .catch(err => {
            console.error(err);
        });
}

document.getElementById("listenBtn").addEventListener("click", function() {
    loadChat();
});

setInterval(loadChat, 3000);
</script>

</body>
</html>
