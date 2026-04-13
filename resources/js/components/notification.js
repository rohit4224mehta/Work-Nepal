// resources/js/components/notification.js

class NotificationManager {
    constructor() {
        this.pollingInterval = null;
        this.unreadCount = 0;
        this.notifications = [];
        this.isLoading = false;
    }
    
    init() {
        this.fetchNotifications();
        this.startPolling();
        this.setupEventListeners();
    }
    
    startPolling() {
        this.pollingInterval = setInterval(() => this.fetchNotifications(), 30000);
    }
    
    stopPolling() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
            this.pollingInterval = null;
        }
    }
    
    async fetchNotifications() {
        if (this.isLoading) return;
        this.isLoading = true;
        
        try {
            const response = await fetch('/notifications/recent', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            const data = await response.json();
            
            if (data.success) {
                this.notifications = data.notifications;
                this.unreadCount = data.unread_count;
                this.updateUI();
            }
        } catch (error) {
            console.error('Error fetching notifications:', error);
        } finally {
            this.isLoading = false;
        }
    }
    
    async markAsRead(notificationId) {
        try {
            const response = await fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            const data = await response.json();
            
            if (data.success) {
                const notification = this.notifications.find(n => n.id === notificationId);
                if (notification && !notification.is_read) {
                    notification.is_read = true;
                    this.unreadCount--;
                    this.updateUI();
                }
            }
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }
    
    async markAllAsRead() {
        try {
            const response = await fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            const data = await response.json();
            
            if (data.success) {
                this.notifications.forEach(n => n.is_read = true);
                this.unreadCount = 0;
                this.updateUI();
            }
        } catch (error) {
            console.error('Error marking all as read:', error);
        }
    }
    
    updateUI() {
        // Update badge count
        const badge = document.querySelector('.notification-badge');
        if (badge) {
            if (this.unreadCount > 0) {
                badge.textContent = this.unreadCount > 99 ? '99+' : this.unreadCount;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }
        
        // Dispatch custom event for other components
        window.dispatchEvent(new CustomEvent('notifications-updated', {
            detail: {
                unreadCount: this.unreadCount,
                notifications: this.notifications
            }
        }));
    }
    
    setupEventListeners() {
        // Listen for page visibility changes
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                this.fetchNotifications();
            }
        });
        
        // Clean up on page unload
        window.addEventListener('beforeunload', () => {
            this.stopPolling();
        });
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    window.notificationManager = new NotificationManager();
    window.notificationManager.init();
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = NotificationManager;
}