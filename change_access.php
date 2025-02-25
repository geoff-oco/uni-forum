<?php
require 'db_connect.php';

if (isset($_SESSION['uname'])) {
    if ($_SESSION['level'] != 1) {
        echo 'You\'re not an admin';
        header('Location: list_threads.php');
        exit;
    } else {
        echo 'Welcome ' . htmlentities($_SESSION['uname'], ENT_QUOTES, 'UTF-8') . ', your access level is ' . htmlentities($_SESSION['level'], ENT_QUOTES, 'UTF-8') . ' ';
        echo '<a href="logout.php">Logout</a>';
    }
} else {
    echo 'No session';
    header('Location: list_threads.php');
    exit;
}

if (!isset($_POST['username'])) { 
    echo 'No username provided';
    header('Location: list_threads.php');
    exit;
}

$stmt = $db->prepare("SELECT access_level FROM user WHERE username = ?");
if (!$stmt) {
    $errorInfo = $db->errorInfo();
    echo "SQL Error: " . htmlentities($errorInfo[2], ENT_QUOTES, 'UTF-8');
    exit;
}

$stmt->execute([htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8')]);
$access = $stmt->fetch(PDO::FETCH_ASSOC);

if ($access) {
    $new_level = ($access['access_level'] == 0) ? 1 : 0; // Toggle between promote and demote

    $stmt_update = $db->prepare("UPDATE user SET access_level = ? WHERE username = ?");
    if (!$stmt_update) {
        $errorInfo = $db->errorInfo();
        echo "SQL Error: " . htmlentities($errorInfo[2], ENT_QUOTES, 'UTF-8');
        exit;
    }

    $result = $stmt_update->execute([$new_level, htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8')]);

    if ($result) {
        echo '<p>Operation Successful! User has been ' . ($new_level == 1 ? 'promoted' : 'demoted') . '.</p>';
    } else {
        echo '<p>Something went wrong during the update.</p>';
    }
} else {
    echo '<p>Something went wrong. No user found.</p>';
}
echo '<p><a href="list_threads.php">List Threads</a></p>';
?>
