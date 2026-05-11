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

// ========== LIKES FUNKTIONER ==========

function likeKvitter($userId, $kvitterId) {
    $db = getDB();
    try {
        $stmt = $db->prepare("INSERT INTO likes (user_id, kvitter_id) VALUES (?, ?)");
        return $stmt->execute([$userId, $kvitterId]);
    } catch (PDOException $e) {
        // Om redan like finns, returnera false
        return false;
    }
}

function unlikeKvitter($userId, $kvitterId) {
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM likes WHERE user_id = ? AND kvitter_id = ?");
    return $stmt->execute([$userId, $kvitterId]);
}

function hasUserLiked($userId, $kvitterId) {
    $db = getDB();
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM likes WHERE user_id = ? AND kvitter_id = ?");
    $stmt->execute([$userId, $kvitterId]);
    $result = $stmt->fetch();
    return $result['count'] > 0;
}

function getLikeCount($kvitterId) {
    $db = getDB();
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM likes WHERE kvitter_id = ?");
    $stmt->execute([$kvitterId]);
    $result = $stmt->fetch();
    return $result['count'];
}

// ========== KOMMENTAR FUNKTIONER ==========

function addComment($userId, $kvitterId, $content) {
    $content = trim($content);
    if ($content === '') return false;
    if (strlen($content) > 280) $content = substr($content, 0, 280);
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO comments (user_id, kvitter_id, content) VALUES (?, ?, ?)");
    return $stmt->execute([$userId, $kvitterId, $content]);
}

function getComments($kvitterId) {
    $db = getDB();
    $stmt = $db->prepare("SELECT c.*, u.username, u.role 
                          FROM comments c 
                          JOIN users u ON c.user_id = u.id 
                          WHERE c.kvitter_id = ? 
                          ORDER BY c.created_at ASC");
    $stmt->execute([$kvitterId]);
    return $stmt->fetchAll();
}

function deleteComment($commentId, $userId, $isAdmin = false) {
    $db = getDB();
    if ($isAdmin) {
        $stmt = $db->prepare("DELETE FROM comments WHERE id = ?");
        return $stmt->execute([$commentId]);
    } else {
        $stmt = $db->prepare("DELETE FROM comments WHERE id = ? AND user_id = ?");
        return $stmt->execute([$commentId, $userId]);
    }
}

function getCommentCount($kvitterId) {
    $db = getDB();
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM comments WHERE kvitter_id = ?");
    $stmt->execute([$kvitterId]);
    $result = $stmt->fetch();
    return $result['count'];
}

// Hämta alla kvitter med likes och comment counts
function getAllKvitterWithStats() {
    $db = getDB();
    $query = "SELECT k.*, u.username, u.role,
              (SELECT COUNT(*) FROM likes WHERE kvitter_id = k.id) as like_count,
              (SELECT COUNT(*) FROM comments WHERE kvitter_id = k.id) as comment_count
              FROM kvitter k 
              JOIN users u ON k.user_id = u.id 
              ORDER BY k.created_at DESC";
    return $db->query($query)->fetchAll();
}
?>