<?php
require_once __DIR__ . '/db.php';

function getAllKvitter() {
    $db = getDB();
    return $db->query("SELECT k.*, u.username, u.role 
                       FROM kvitter k 
                       JOIN users u ON k.user_id = u.id 
                       ORDER BY k.created_at DESC")->fetchAll();
}

function getUserKvitter($userId) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM kvitter WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

function createKvitter($userId, $content) {
    $content = trim($content);
    if ($content === '') return false;
    if (strlen($content) > 280) $content = substr($content, 0, 280);
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO kvitter (user_id, content) VALUES (?, ?)");
    return $stmt->execute([$userId, $content]);
}

function deleteKvitter($kvitterId, $userId, $isAdmin = false) {
    $db = getDB();
    if ($isAdmin) {
        $stmt = $db->prepare("DELETE FROM kvitter WHERE id = ?");
        return $stmt->execute([$kvitterId]);
    } else {
        $stmt = $db->prepare("DELETE FROM kvitter WHERE id = ? AND user_id = ?");
        return $stmt->execute([$kvitterId, $userId]);
    }
}

function getUserStats($userId) {
    $db = getDB();
    $stmt = $db->prepare("SELECT COUNT(*) as total, MAX(created_at) as last FROM kvitter WHERE user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch();
}
?>