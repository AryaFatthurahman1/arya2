import React, { useEffect, useRef } from 'react';
import { createRoot } from 'react-dom/client';
import { formatRupiah, formatNumber } from '../utils/format';

interface StatItem {
    label: string;
    value: number;
    prefix?: string;
    suffix?: string;
    color: string;
    icon: string;
}

interface QuickStatsProps {
    stats: StatItem[];
}

const QuickStats: React.FC<QuickStatsProps> = ({ stats }) => {
    return (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            {stats.map((stat, idx) => (
                <div key={idx} className={`stat-card-${stat.color}`}>
                    <div className="absolute top-0 right-0 -mr-6 -mt-6 w-24 h-24 rounded-full bg-white/10"></div>
                    <div className="absolute bottom-0 left-0 -ml-4 -mb-4 w-16 h-16 rounded-full bg-white/5"></div>
                    <div className="relative">
                        <p className="text-sm font-medium text-white/70">{stat.label}</p>
                        <p className="text-3xl font-extrabold mt-2">
                            {stat.prefix}{formatNumber(stat.value)}{stat.suffix}
                        </p>
                    </div>
                </div>
            ))}
        </div>
    );
};

export default QuickStats;
