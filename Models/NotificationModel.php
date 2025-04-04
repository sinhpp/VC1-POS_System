<?php
class NotificationModel {
    private $db;
    
    public function __construct() {
        require_once "Config/Database.php";
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function addNotification($type, $message, $link, $userId = null) {
        try {
            $query = "INSERT INTO notifications (type, message, link, user_id, created_at) 
                      VALUES (:type, :message, :link, :user_id, NOW())";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':message', $message);
            $stmt->bindParam(':link', $link);
            $stmt->bindParam(':user_id', $userId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error adding notification: " . $e->getMessage());
            return false;
        }
    }
    
    public function getNotifications($userId = null, $limit = 10) {
        try {
            $query = "SELECT * FROM notifications 
                      WHERE user_id IS NULL OR user_id = :user_id 
                      ORDER BY created_at DESC LIMIT :limit";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching notifications: " . $e->getMessage());
            return [];
        }
    }
    
    public function markAsRead($notificationId) {
        try {
            $query = "UPDATE notifications SET read_at = NOW() WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $notificationId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error marking notification as read: " . $e->getMessage());
            return false;
        }
    }
}
