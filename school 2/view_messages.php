<?php
session_start();
require 'config.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT id, position FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    echo "Access denied.";
    exit();
}

$sender_id = $user['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $receiver_id = intval($_POST['receiver_id']);
    $message = trim($_POST['message']);
    if (!empty($receiver_id) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message, status) VALUES (?, ?, ?, 'unread')");
        $stmt->bind_param("iis", $sender_id, $receiver_id, $message);
        $stmt->execute();
        $stmt->close();
    }
    exit();
}

// Fetch users already chatted with
$users_query = "
    SELECT DISTINCT u.id, u.username, u.position,
    (SELECT COUNT(*) FROM messages WHERE sender_id = u.id AND receiver_id = ? AND status = 'unread') AS unread_count
    FROM users u
    JOIN messages m ON (u.id = m.sender_id AND m.receiver_id = ?) OR (u.id = m.receiver_id AND m.sender_id = ?)
    WHERE u.id != ?
";
$users_stmt = $conn->prepare($users_query);
$users_stmt->bind_param("iiii", $sender_id, $sender_id, $sender_id, $sender_id);
$users_stmt->execute();
$users = $users_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$users_stmt->close();

// Fetch all users except current user, grouped by position
$available_users = [];
$query = "SELECT id, username, position FROM users WHERE id != ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $sender_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $available_users[$row['position']][] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Chat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #e5ddd5; }
        .chat-container { max-width: 900px; margin: auto; }
        .chat-box { height: 500px; overflow-y: scroll; background: #fff; padding: 15px; border-radius: 8px; }
        .sent, .received { margin: 8px 0; padding: 10px 15px; border-radius: 20px; max-width: 70%; display: inline-block; }
        .sent { background: #dcf8c6; float: right; clear: both; }
        .received { background: #f1f0f0; float: left; clear: both; }
        .sender-info { font-size: 0.75em; color: #888; }
        .user-select { height: 500px; overflow-y: auto; }
        .chat-sidebar { background: #fff; border-radius: 8px; padding: 10px; }
        .chat-sidebar .user { padding: 10px; border-bottom: 1px solid #ddd; cursor: pointer; }
        .chat-sidebar .user:hover { background: #f7f7f7; }
        .unread-badge { background: red; color: white; border-radius: 10px; padding: 2px 6px; font-size: 0.7em; margin-left: 5px; }
        textarea { resize: none; }
    </style>
</head>
<body>
<div class="container-fluid py-4">
    <div class="row chat-container">
        <div class="col-md-4 chat-sidebar">
            <h5>Contacts</h5>

            <!-- 🔍 Search Contacts -->
            <input type="text" class="form-control mb-2" id="searchUser" placeholder="Search by name..." onkeyup="filterUsers()">

            <!-- ➕ Start New Chat: Select user grouped by position -->
            <select class="form-select mb-2" id="userSelect" onchange="startChatWithUser(this.value)">
                <option value="">➕ Start New Chat</option>
                <?php foreach ($available_users as $position => $group): ?>
                    <optgroup label="<?= htmlspecialchars($position) ?>">
                        <?php foreach ($group as $user): ?>
                            <option value="<?= $user['id'] ?>"
                                data-username="<?= htmlspecialchars($user['username']) ?>"
                                data-position="<?= htmlspecialchars($user['position']) ?>">
                                <?= htmlspecialchars($user['username']) ?>
                            </option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endforeach; ?>
            </select>

            <!-- 🧑‍🤝‍🧑 User List -->
            <div class="user-select" id="userList">
                <?php if (empty($users)): ?>
                    <p class="text-muted">No conversations yet.</p>
                <?php else: ?>
                    <?php foreach ($users as $u): ?>
                        <div class="user">
                            <div onclick="loadChat(<?= $u['id'] ?>, '<?= htmlspecialchars($u['username']) ?>', '<?= htmlspecialchars($u['position']) ?>')">
                                <?= htmlspecialchars($u['username']) ?> (<?= htmlspecialchars($u['position']) ?>)
                                <?php if ($u['unread_count'] > 0): ?>
                                    <span class="unread-badge"><?= $u['unread_count'] ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-8">
            <div id="chat-header" class="mb-2 d-none">
                <h5 id="chat-user"></h5>
                <hr>
            </div>

            <div id="chat-box" class="chat-box d-none"></div>

            <form id="chat-form" class="d-none mt-3">
                <div class="input-group">
                    <textarea name="message" id="message" class="form-control" placeholder="Type your message..." required></textarea>
                    <button type="submit" class="btn btn-success">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let receiverId = null;

function loadChat(id, username, position) {
    receiverId = id;
    document.getElementById('chat-user').innerText = `${username} (${position})`;
    document.getElementById('chat-box').classList.remove('d-none');
    document.getElementById('chat-form').classList.remove('d-none');
    document.getElementById('chat-header').classList.remove('d-none');
    fetchMessages();
}

function fetchMessages() {
    if (!receiverId) return;
    fetch(`load_messages.php?receiver_id=${receiverId}`)
        .then(res => res.text())
        .then(data => {
            const chatBox = document.getElementById('chat-box');
            chatBox.innerHTML = data;
            chatBox.scrollTop = chatBox.scrollHeight;
        });
}

document.getElementById('chat-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const message = document.getElementById('message').value.trim();
    if (!message) return;
    const formData = new FormData();
    formData.append('receiver_id', receiverId);
    formData.append('message', message);
    formData.append('send_message', 1);

    fetch('', {
        method: 'POST',
        body: formData
    }).then(() => {
        document.getElementById('message').value = '';
        fetchMessages();
    });
});

setInterval(() => {
    if (receiverId) fetchMessages();
}, 5000);

// 🔍 Filter Users by name in contact list
function filterUsers() {
    const searchInput = document.getElementById("searchUser").value.toLowerCase();
    const users = document.querySelectorAll("#userList .user");

    users.forEach(user => {
        const name = user.innerText.toLowerCase();
        user.style.display = name.includes(searchInput) ? '' : 'none';
    });
}

// ➕ Start new chat by selecting user from dropdown
function startChatWithUser(id) {
    if (!id) return;
    const select = document.getElementById("userSelect");
    const option = select.options[select.selectedIndex];
    const username = option.getAttribute('data-username');
    const position = option.getAttribute('data-position');
    loadChat(id, username, position);
}
</script>
</body>
</html>
