<?php
require_once __DIR__ . '/db.php';

function getAllKvitter() {
    $db = getDB();
    return $db->query("SELECT k.*, u.username, u.role, 
        (SELECT COUNT(*) FROM likes WHERE kvitter_id = k.id) as like_count
        FROM kvitter k 
        JOIN users u ON k.user_id = u.id 
        ORDER BY k.created_at DESC")->fetchAll();
}

function getUserKvitter($userId) {
    $db = getDB();
    $stmt = $db->prepare("SELECT k.*, 
        (SELECT COUNT(*) FROM likes WHERE kvitter_id = k.id) as like_count
            FROM kvitter k 
            WHERE k.user_id = ? 
            ORDER BY k.created_at DESC");
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

function getAllKvitterWithLikes($userId = null) {
    $db = getDB();
    $query = "SELECT k.*, u.username, u.role,
              (SELECT COUNT(*) FROM likes WHERE kvitter_id = k.id) as likes_count";
    
    if ($userId) {
        $query .= ", (SELECT COUNT(*) FROM likes WHERE kvitter_id = k.id AND user_id = :user_id) as user_liked";
    } else {
        $query .= ", 0 as user_liked";
    }
    
    $query .= " FROM kvitter k 
                JOIN users u ON k.user_id = u.id 
                ORDER BY k.created_at DESC";
    
    $stmt = $db->prepare($query);
    if ($userId) {
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    }
    $stmt->execute();
    return $stmt->fetchAll();
}


function hasUserLiked($userId, $kvitterId) {
    $db = getDB();
    $stmt = $db->prepare("SELECT COUNT(*) FROM likes WHERE user_id = ? AND kvitter_id = ?");
    $stmt->execute([$userId, $kvitterId]);
    return $stmt->fetchColumn() > 0;
}


function addLike($userId, $kvitterId) {
    $db = getDB();
    try {
        $stmt = $db->prepare("INSERT INTO likes (user_id, kvitter_id) VALUES (?, ?)");
        return $stmt->execute([$userId, $kvitterId]);
    } catch (PDOException $e) {
        return false;
    }
}


function removeLike($userId, $kvitterId) {
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM likes WHERE user_id = ? AND kvitter_id = ?");
    return $stmt->execute([$userId, $kvitterId]);
}


function getUserLikedKvitter($userId) {
    $db = getDB();
    $stmt = $db->prepare("SELECT kvitter_id FROM likes WHERE user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}


function getLikeCount($kvitterId) {
    $db = getDB();
    $stmt = $db->prepare("SELECT COUNT(*) FROM likes WHERE kvitter_id = ?");
    $stmt->execute([$kvitterId]);
    return $stmt->fetchColumn();
}
?>