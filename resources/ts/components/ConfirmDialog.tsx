import React, { useEffect, useRef } from 'react';
import { createRoot } from 'react-dom/client';

interface ConfirmDialogProps {
    isOpen: boolean;
    title: string;
    message: string;
    confirmText?: string;
    cancelText?: string;
    variant?: 'danger' | 'warning' | 'info';
    onConfirm: () => void;
    onCancel: () => void;
}

const ConfirmDialog: React.FC<ConfirmDialogProps> = ({
    isOpen,
    title,
    message,
    confirmText = 'Ya, Lanjutkan',
    cancelText = 'Batal',
    variant = 'danger',
    onConfirm,
    onCancel,
}) => {
    if (!isOpen) return null;

    const variantStyles = {
        danger: 'bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600',
        warning: 'bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600',
        info: 'bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600',
    };

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm animate-fade-in-down">
            <div className="bg-white rounded-2xl shadow-2xl p-6 max-w-md w-full mx-4 transform transition-all">
                <div className="text-center">
                    <div className={`inline-flex items-center justify-center w-14 h-14 rounded-full mb-4 ${
                        variant === 'danger' ? 'bg-red-100' : variant === 'warning' ? 'bg-amber-100' : 'bg-blue-100'
                    }`}>
                        <svg className={`w-7 h-7 ${
                            variant === 'danger' ? 'text-red-600' : variant === 'warning' ? 'text-amber-600' : 'text-blue-600'
                        }`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <h3 className="text-lg font-semibold text-gray-900 mb-2">{title}</h3>
                    <p className="text-sm text-gray-500 mb-6">{message}</p>
                </div>
                <div className="flex gap-3">
                    <button onClick={onCancel} className="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-50 transition-all">
                        {cancelText}
                    </button>
                    <button onClick={onConfirm} className={`flex-1 px-4 py-2.5 rounded-xl text-white text-sm font-semibold shadow-lg transition-all ${variantStyles[variant]}`}>
                        {confirmText}
                    </button>
                </div>
            </div>
        </div>
    );
};

export default ConfirmDialog;
