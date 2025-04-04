<!-- Add this to your dashboard -->
<div class="notifications-panel">
    <h3>Notifications</h3>
    <div class="notification-list">
        <?php 
        require_once "Models/NotificationModel.php";
        $notificationModel = new NotificationModel();
        $notifications = $notificationModel->getNotifications();
        
        if (empty($notifications)): 
        ?>
            <p>No new notifications</p>
        <?php else: ?>
            <?php foreach ($notifications as $notification): ?>
                <div class="notification-item <?= $notification['read_at'] ? 'read' : 'unread' ?>">
                    <div class="notification-icon">
                        <?php if ($notification['type'] === 'low_stock'): ?>
                            <i class="fas fa-exclamation-triangle text-warning"></i>
                        <?php else: ?>
                            <i class="fas fa-bell"></i>
                        <?php endif; ?>
                    </div>
                    <div class="notification-content">
                        <p><?= htmlspecialchars($notification['message']) ?></p>
                        <small><?= date('M d, Y H:i', strtotime($notification['created_at'])) ?></small>
                    </div>
                    <div class="notification-actions">
                        <a href="<?= $notification['link'] ?>" class="btn btn-sm btn-primary">View</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
