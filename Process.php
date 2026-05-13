<?php

session_start();
require_once 'Config.php';

// ─── REGISTER ────────────────────────────────────────────────────────────────
if (isset($_POST['register'])) {

    $name     = trim($_POST['name']     ?? '');
    $email    = trim($_POST['email']    ?? '');
    $password =       $_POST['password'] ?? '';
    $confirm  =       $_POST['confirm_password'] ?? '';
    $role     =       $_POST['role']    ?? '';
    $terms    =       $_POST['terms']   ?? '';

    // 1. Required fields
    if (empty($name) || empty($email) || empty($password) || empty($confirm) || empty($role)) {
        $_SESSION['register_error'] = 'Please fill out all fields.';
        header('Location: Login.php');
        exit();
    }

    // 2. Terms & conditions
    if ($terms !== 'on') {
        $_SESSION['register_error'] = 'You must agree to the Terms and Conditions to register.';
        header('Location: Login.php');
        exit();
    }

    // 3. Valid role
    if (!in_array($role, ['Student', 'Vendor'])) {
        $_SESSION['register_error'] = 'Please select a valid role.';
        header('Location: Login.php');
        exit();
    }

    // 4. Valid email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['register_error'] = 'Please enter a valid email address.';
        header('Location: Login.php');
        exit();
    }

    // 5. Name: letters/spaces only, 2–60 chars
    if (!preg_match('/^[a-zA-ZÀ-ÿ\s\'\-\.]{2,60}$/', $name)) {
        $_SESSION['register_error'] = 'Name must be 2–60 characters and contain only letters, spaces, hyphens, or periods.';
        header('Location: Login.php');
        exit();
    }

    // 6. Password strength
    if (strlen($password) < 8 || strlen($password) > 32) {
        $_SESSION['register_error'] = 'Password must be between 8 and 32 characters.';
        header('Location: Login.php');
        exit();
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $_SESSION['register_error'] = 'Password must contain at least one uppercase letter.';
        header('Location: Login.php');
        exit();
    }
    if (!preg_match('/[a-z]/', $password)) {
        $_SESSION['register_error'] = 'Password must contain at least one lowercase letter.';
        header('Location: Login.php');
        exit();
    }
    if (!preg_match('/[0-9]/', $password)) {
        $_SESSION['register_error'] = 'Password must contain at least one number.';
        header('Location: Login.php');
        exit();
    }

    // 7. Passwords match
    if ($password !== $confirm) {
        $_SESSION['register_error'] = 'Passwords do not match.';
        header('Location: Login.php');
        exit();
    }

    // 8. Duplicate email + role
    $check = $conn->prepare("SELECT id FROM users WHERE email = ? AND role = ?");
    $check->bind_param('ss', $email, $role);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        $check->close();
        $_SESSION['register_error'] = 'An account with that email and role already exists.';
        header('Location: Login.php');
        exit();
    }
    $check->close();

    // 9. Insert
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt   = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $name, $email, $hashed, $role);
    if (!$stmt->execute()) {
        $stmt->close();
        $_SESSION['register_error'] = 'Registration failed. Please try again.';
        header('Location: Login.php');
        exit();
    }
    $stmt->close();

    $_SESSION['register_success'] = 'Registration successful! Please log in.';
    header('Location: Login.php');
    exit();
}

// ─── LOGIN ────────────────────────────────────────────────────────────────────
if (isset($_POST['login'])) {

    $email    = trim($_POST['email']    ?? '');
    $password =       $_POST['password'] ?? '';
    $role     =       $_POST['role']    ?? '';

    if (empty($email) || empty($password)) {
        $_SESSION['login_error'] = 'Please fill out all the fields.';
        header('Location: Login.php');
        exit();
    }

    if (empty($role)) {
        $_SESSION['popup'] = 'Please select a role.';
        header('Location: Login.php');
        exit();
    }

    if (!in_array($role, ['Student', 'Vendor'])) {
        $_SESSION['popup'] = 'Please select a valid role.';
        header('Location: Login.php');
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['login_error'] = 'Please enter a valid email address.';
        header('Location: Login.php');
        exit();
    }

    $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ? AND role = ?");
    $stmt->bind_param('ss', $email, $role);
    $stmt->execute();
    $stmt->bind_result($id, $name, $hashed, $db_role);

    if ($stmt->fetch() && password_verify($password, $hashed)) {
        $stmt->close();
        session_regenerate_id(true);
        $_SESSION['user_id'] = $id;
        $_SESSION['name']    = $name;
        $_SESSION['role']    = $db_role;

        header('Location: ' . ($role === 'Student' ? 'S_Home.php' : 'V_Home.php'));
        exit();
    }

    $stmt->close();
    $_SESSION['login_error'] = 'Incorrect email, password, or role.';
    header('Location: Login.php');
    exit();
}
?>