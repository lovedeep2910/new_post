<?php
session_start();

require 'config.php';
require 'user.php';
require 'post.php';

$db = (new Config())->conn;
$user = new User($db);
$post = new Post($db);

$action = $_GET['action'] ?? 'home';

if ($action == 'login') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($user->login($_POST['username'], $_POST['password'])) {
            header('Location: index.php?action=dashboard');
        } else {
            echo 'Invalid login!';
        }
    }

    echo '<form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
          </form>';
} elseif ($action == 'register') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($user->register($_POST['username'], $_POST['password'])) {
            header('Location: index.php?action=login');
        } else {
            echo 'Registration failed!';
        }
    }

    echo '<form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
          </form>';
} elseif ($action == 'dashboard') {
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?action=login');
    }

    echo '<a href="index.php?action=create_post">Create Post</a>';
    echo '<h1>Posts</h1>';

    $posts = $post->getPosts();
    foreach ($posts as $post) {
        echo '<h2>' . $post['title'] . '</h2>';
        echo '<p>' . $post['content'] . '</p>';
        echo '<p>By ' . $post['username'] . '</p>';
        echo '<a href="index.php?action=edit_post&id=' . $post['id'] . '">Edit</a>';
        echo '<a href="index.php?action=delete_post&id=' . $post['id'] . '">Delete</a>';
    }
} elseif ($action == 'create_post') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $post->createPost($_POST['title'], $_POST['content'], $_SESSION['user_id']);
        header('Location: index.php?action=dashboard');
    }

    echo '<form method="POST">
            <input type="text" name="title" placeholder="Title" required>
            <textarea name="content" placeholder="Content" required></textarea>
            <button type="submit">Create Post</button>
          </form>';
} elseif ($action == 'edit_post') {
    $postId = $_GET['id'];
    $postDetails = $post->getPostById($postId);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $post->updatePost($postId, $_POST['title'], $_POST['content']);
        header('Location: index.php?action=dashboard');
    }

    echo '<form method="POST">
            <input type="text" name="title" value="' . $postDetails['title'] . '" required>
            <textarea name="content" required>' . $postDetails['content'] . '</textarea>
            <button type="submit">Update Post</button>
          </form>';
} elseif ($action == 'delete_post') {
    $postId = $_GET['id'];
    $post->deletePost($postId);
    header('Location: index.php?action=dashboard');
} else {
    echo '<a href="index.php?action=login">Login</a>';
    echo '<a href="index.php?action=register">Register</a>';
}
