import React, { useState, useEffect, useRef } from 'react';
import { createRoot } from 'react-dom/client';

interface Notification {
    id: number;
    title: string;
    message: string;
    icon: string;
    color: string;
    url: string;
    time: string;
    read: boolean;
}

interface NotificationBellProps {
    initialCount?: number;
}

const NotificationBell: React.FC<NotificationBellProps> = ({ initialCount = 0 }) => {
    const [isOpen, setIsOpen] = useState(false);
    const [notifications, setNotifications] = useState<Notification[]>([]);
    const [unreadCount, setUnreadCount] = useState(initialCount);
    const dropdownRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        const handleClickOutside = (event: MouseEvent) => {
            if (dropdownRef.current && !dropdownRef.current.contains(event.target as Node)) {
                setIsOpen(false);
            }
        };
        document.addEventListener('mousedown', handleClickOutside);
        return () => document.removeEventListener('mousedown', handleClickOutside);
    }, []);

    const fetchNotifications = async () => {
        try {
            const response = await fetch('/notifications');
            const data = await response.json();
            setNotifications(data.notifications || []);
            setUnreadCount(data.unread_count || 0);
        } catch (error) {
            console.error('Gagal mengambil notifikasi:', error);
        }
    };

    const markAllAsRead = async () => {
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            await fetch('/notifications/read-all', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken || '' },
            });
            setUnreadCount(0);
            setIsOpen(false);
        } catch (error) {
            console.error('Gagal menandai semua dibaca:', error);
        }
    };

    return (
        <div className="relative" ref={dropdownRef}>
            <button
                onClick={() => setIsOpen(!isOpen)}
                className="relative p-2 text-gray-400 hover:text-indigo-600 transition-colors bg-white rounded-full shadow-sm border border-gray-100 hover:border-indigo-100"
            >
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                {unreadCount > 0 && (
                    <span className="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center border-2 border-white animate-pulse">
                        {unreadCount}
                    </span>
                )}
            </button>

            {isOpen && (
                <div className="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-100 z-50 animate-fade-in-down">
                    <div className="p-4 border-b border-gray-100">
                        <h3 className="font-semibold text-gray-900">Notifikasi</h3>
                    </div>
                    <div className="max-h-96 overflow-y-auto">
                        {notifications.length === 0 ? (
                            <div className="p-4 text-center text-gray-500">
                                <p>Tidak ada notifikasi baru</p>
                            </div>
                        ) : (
                            notifications.map((n) => (
                                <a key={n.id} href={n.url} className="block p-4 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-b-0">
                                    <div className="flex items-start">
                                        <div className={`p-2 rounded-lg bg-${n.color}-100 mr-3`}>
                                            <svg className={`w-4 h-4 text-${n.color}-600`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div className="flex-1">
                                            <p className="text-sm font-medium text-gray-900">{n.title}</p>
                                            <p className="text-sm text-gray-500 mt-1">{n.message}</p>
                                            <p className="text-xs text-gray-400 mt-1">{n.time}</p>
                                        </div>
                                    </div>
                                </a>
                            ))
                        )}
                    </div>
                    {notifications.length > 0 && (
                        <div className="p-3 border-t border-gray-100">
                            <button onClick={markAllAsRead} className="w-full text-center text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                                Tandai semua sudah dibaca
                            </button>
                        </div>
                    )}
                </div>
            )}
        </div>
    );
};

export default NotificationBell;
