<?php
session_start();

$errors = [
  'login' => $_SESSION['login_error'] ?? '',
  'register' => $_SESSION['register_error'] ?? ''
];

$activeForm = $_SESSION['active_form'] ?? 'login';

unset($_SESSION['login_error']);
unset($_SESSION['register_error']);

function showError($error)
{
  return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}

function isActiveForm($formName, $activeForm)
{
  return $formName === $activeForm ? 'active' : '';
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="style.css" />
  <title>Auth System</title>
</head>

<body>

<div class="container">

  <!-- LOGIN -->
  <div class="form-box <?= isActiveForm('login', $activeForm) ?>" id="login-form">
    <form action="login_register.php" method="POST">
      <h2>Login</h2>

      <?= showError($errors['login']); ?>

      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>

      <button type="submit" name="login">Login</button>

      <p>
        Don't have account?
        <a href="#" onclick="showForm('register-form')">Register</a>
      </p>
    </form>
  </div>

  <!-- REGISTER -->
  <div class="form-box <?= isActiveForm('register', $activeForm) ?>" id="register-form">
    <form action="login_register.php" method="POST">
      <h2>Register</h2>

      <?= showError($errors['register']); ?>

      <input type="text" name="name" placeholder="Name" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>

      <select name="role" required>
        <option value="">Select Role</option>
        <option value="user">User</option>
        <option value="admin">Admin</option>
      </select>

      <button type="submit" name="register">Register</button>

      <p>
        Already have account?
        <a href="#" onclick="showForm('login-form')">Login</a>
      </p>
    </form>
  </div>

</div>

<script src="script.js"></script>
</body>
</html>