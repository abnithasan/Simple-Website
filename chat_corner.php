<?php
// Save a new message when the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["send"])) {
    $name = trim($_POST["uname"] ?? "");
    $message = trim($_POST["msg"] ?? "");

    if ($name !== "" && $message !== "") {
        // Keep each message on one line
        $name = str_replace(["\r", "\n"], " ", $name);
        $message = str_replace(["\r", "\n"], " ", $message);

        $entry = $name . ": " . $message . PHP_EOL;
        file_put_contents("chat.txt", $entry, FILE_APPEND | LOCK_EX);
    }

    // Prevent the browser from resending the message on refresh
    header("Location: chat.php");
    exit;
}

// Load all saved messages
$messages = [];
if (file_exists("chat.txt")) {
    $messages = file("chat.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Corner</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background: #eef2f7;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .chat-container {
            width: 100%;
            max-width: 620px;
            background: white;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 8px 28px rgba(30, 50, 80, 0.12);
        }

        .chat-header {
            background: #34495e;
            color: white;
            padding: 22px;
            text-align: center;
        }

        .chat-header h2 {
            margin: 0 0 6px;
        }

        .chat-header p {
            margin: 0;
            color: #dce5ee;
            font-size: 14px;
        }

        .messages {
            height: 350px;
            overflow-y: auto;
            padding: 20px;
            background: #f8fafc;
        }

        .message {
            background: #e5edf5;
            border-radius: 8px;
            padding: 11px 14px;
            margin-bottom: 10px;
            color: #263746;
            overflow-wrap: anywhere;
        }

        .empty-message {
            color: #7b8794;
            text-align: center;
            margin-top: 120px;
        }

        .chat-form {
            padding: 18px 20px 20px;
            border-top: 1px solid #e5eaf0;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 12px;
            border: 1px solid #cbd5df;
            border-radius: 7px;
            font-size: 15px;
            outline: none;
        }

        input[type="text"]:focus {
            border-color: #34495e;
        }

        .button-row {
            display: flex;
            gap: 10px;
        }

        button {
            border: none;
            border-radius: 7px;
            padding: 12px 18px;
            font-size: 14px;
            cursor: pointer;
        }

        .send-button {
            flex: 1;
            background: #34495e;
            color: white;
        }

        .refresh-button {
            background: #e6edf4;
            color: #34495e;
        }

        button:hover {
            opacity: 0.88;
        }
    </style>
</head>
<body>

<div class="chat-container">
    <div class="chat-header">
        <h2>Chat Corner</h2>
        <p>Welcome to the group chat</p>
    </div>

    <div class="messages">
        <?php if (count($messages) > 0): ?>
            <?php foreach ($messages as $line): ?>
                <div class="message">
                    <?php echo htmlspecialchars($line, ENT_QUOTES, "UTF-8"); ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty-message">No messages yet. Start the conversation!</p>
        <?php endif; ?>
    </div>

    <form class="chat-form" method="post" action="chat.php">
        <input type="text" name="uname" placeholder="Enter your name" maxlength="40" required>
        <input type="text" name="msg" placeholder="Write a message..." maxlength="500" required>

        <div class="button-row">
            <button class="send-button" type="submit" name="send">Send Message</button>
            <button class="refresh-button" type="button" onclick="window.location.href='chat.php'">
                Refresh
            </button>
        </div>
    </form>
</div>

</body>
</html>
