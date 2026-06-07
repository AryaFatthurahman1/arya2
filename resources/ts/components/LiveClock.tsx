import React, { useState, useEffect } from 'react';
import { createRoot } from 'react-dom/client';

interface ClockProps {
    format?: '12h' | '24h';
}

const LiveClock: React.FC<ClockProps> = ({ format = '24h' }) => {
    const [time, setTime] = useState(new Date());

    useEffect(() => {
        const interval = setInterval(() => setTime(new Date()), 1000);
        return () => clearInterval(interval);
    }, []);

    const options: Intl.DateTimeFormatOptions = {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: format === '12h',
    };

    const dateOptions: Intl.DateTimeFormatOptions = {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    };

    return (
        <div className="flex items-center gap-3">
            <div className="text-right">
                <div className="text-sm font-medium text-gray-600">
                    {time.toLocaleDateString('id-ID', dateOptions)}
                </div>
                <div className="text-lg font-bold gradient-text">
                    {time.toLocaleTimeString('id-ID', options)}
                </div>
            </div>
        </div>
    );
};

export default LiveClock;
