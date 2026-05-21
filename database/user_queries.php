<?php
require_once __DIR__ . '/db.php';

function getUserByEmail($email) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch();
}

function getUserByUsername($username) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    return $stmt->fetch();
}

function getUserById($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT id, username, email, role, created_at FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function createUser($username, $email, $password) {
    $db = getDB();
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    return $stmt->execute([$username, $email, $hash]);
}

function getAllUsers() {
    $db = getDB();
    return $db->query("SELECT u.*, COUNT(k.id) as kvitter_count 
                       FROM users u 
                       LEFT JOIN kvitter k ON u.id = k.user_id 
                       GROUP BY u.id 
                       ORDER BY u.created_at DESC")->fetchAll();
}

function deleteUser($id) {
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
    return $stmt->execute([$id]);
}

function validateUsername($username) {
    return preg_match('/^[a-zA-Z0-9_]{3,50}$/', trim($username));
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePassword($pwd) {
    return strlen($pwd) >= 8 && preg_match('/[A-Z]/', $pwd) && preg_match('/[0-9]/', $pwd);
}
?>