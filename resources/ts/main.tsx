import React from 'react';
import { createRoot } from 'react-dom/client';
import LiveClock from './components/LiveClock';
import NotificationBell from './components/NotificationBell';
import QuickStats from './components/QuickStats';

// Live Clock
const clockRoot = document.getElementById('live-clock-root');
if (clockRoot) {
    const root = createRoot(clockRoot);
    root.render(<LiveClock />);
}

// Notification Bell
const bellRoot = document.getElementById('notification-bell-root');
if (bellRoot) {
    const root = createRoot(bellRoot);
    const initialCount = parseInt(bellRoot.dataset.unreadCount || '0');
    root.render(<NotificationBell initialCount={initialCount} />);
}

// Quick Stats
const statsRoot = document.getElementById('quick-stats-root');
if (statsRoot) {
    const root = createRoot(statsRoot);
    try {
        const statsData = JSON.parse(statsRoot.dataset.stats || '[]');
        root.render(<QuickStats stats={statsData} />);
    } catch (e) {
        console.error('Invalid stats data:', e);
    }
}
