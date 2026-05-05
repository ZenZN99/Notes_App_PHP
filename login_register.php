<?php
session_start();
require_once 'config.php';

/* ================= REGISTER ================= */
if (isset($_POST['register'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (empty($name) || empty($email) || empty($password)) {
        $_SESSION['register_error'] = "All fields are required";
        $_SESSION['active_form'] = 'register';
        header("Location: index.php");
        exit();
    }

    // check email
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $check = $stmt->get_result();

    if ($check->num_rows > 0) {
        $_SESSION['register_error'] = "Email already exists";
        $_SESSION['active_form'] = 'register';
        header("Location: index.php");
        exit();
    }

    // insert user
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);
    $stmt->execute();

    $_SESSION['active_form'] = 'login';
    header("Location: index.php");
    exit();
}


/* ================= LOGIN ================= */
if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['login_error'] = "Email not found";
        $_SESSION['active_form'] = 'login';
        header("Location: index.php");
        exit();
    }

    $user = $result->fetch_assoc();

    if (!password_verify($password, $user['password'])) {
        $_SESSION['login_error'] = "Wrong password";
        $_SESSION['active_form'] = 'login';
        header("Location: index.php");
        exit();
    }

    // success login
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];

    if ($user['role'] === 'admin') {
        header("Location: admin_page.php");
    } else {
        header("Location: user_page.php");
    }
    exit();
}
