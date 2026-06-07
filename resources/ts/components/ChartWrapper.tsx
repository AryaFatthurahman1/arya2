import React, { useRef, useEffect } from 'react';
import { createRoot } from 'react-dom/client';
import Chart from 'chart.js/auto';

interface ChartWrapperProps {
    type: 'line' | 'bar' | 'doughnut' | 'pie';
    labels: string[];
    datasets: Array<{
        label: string;
        data: number[];
        backgroundColor?: string | string[];
        borderColor?: string | string[];
        fill?: boolean;
        tension?: number;
    }>;
    height?: number;
}

const ChartWrapper: React.FC<ChartWrapperProps> = ({ type, labels, datasets, height = 200 }) => {
    const canvasRef = useRef<HTMLCanvasElement>(null);
    const chartRef = useRef<Chart | null>(null);

    useEffect(() => {
        if (chartRef.current) chartRef.current.destroy();
        if (!canvasRef.current) return;

        chartRef.current = new Chart(canvasRef.current, {
            type,
            data: { labels, datasets },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: type === 'doughnut' || type === 'pie',
                        position: 'bottom',
                        labels: { padding: 20, usePointStyle: true },
                    },
                },
                ...(type === 'doughnut' || type === 'pie' ? { cutout: '60%' } : {}),
            },
        });

        return () => { if (chartRef.current) chartRef.current.destroy(); };
    }, [type, labels, datasets]);

    return <canvas ref={canvasRef} height={height}></canvas>;
};

export default ChartWrapper;
