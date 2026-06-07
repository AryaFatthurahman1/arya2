import React, { useState, useCallback } from 'react';

interface FileUploaderProps {
    accept?: string;
    maxSize?: number;
    onFileSelect: (file: File) => void;
    previewStyle?: 'default' | 'circle' | 'avatar';
}

const FileUploader: React.FC<FileUploaderProps> = ({
    accept = 'image/*,.pdf,.doc,.docx,.xls,.xlsx',
    maxSize = 5 * 1024 * 1024,
    onFileSelect,
    previewStyle = 'default',
}) => {
    const [preview, setPreview] = useState<string | null>(null);
    const [fileName, setFileName] = useState('');
    const [error, setError] = useState('');
    const [isDragging, setIsDragging] = useState(false);

    const handleFile = useCallback((file: File) => {
        setError('');
        if (file.size > maxSize) {
            setError(`Ukuran file maksimal ${Math.round(maxSize / 1024 / 1024)} MB`);
            return;
        }
        setFileName(file.name);
        onFileSelect(file);

        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => setPreview(e.target?.result as string);
            reader.readAsDataURL(file);
        } else {
            setPreview(null);
        }
    }, [maxSize, onFileSelect]);

    const handleDrop = (e: React.DragEvent) => {
        e.preventDefault();
        setIsDragging(false);
        const file = e.dataTransfer.files[0];
        if (file) handleFile(file);
    };

    return (
        <div
            onDragOver={(e) => { e.preventDefault(); setIsDragging(true); }}
            onDragLeave={() => setIsDragging(false)}
            onDrop={handleDrop}
            className={`relative border-2 border-dashed rounded-2xl p-6 text-center transition-all duration-300 ${
                isDragging
                    ? 'border-indigo-500 bg-indigo-50/50'
                    : 'border-gray-300 hover:border-indigo-300 hover:bg-gray-50'
            }`}
        >
            {preview ? (
                <div className="flex flex-col items-center gap-3">
                    {previewStyle === 'circle' ? (
                        <img src={preview} alt="Preview" className="w-24 h-24 rounded-full object-cover ring-4 ring-indigo-100 shadow-md" />
                    ) : previewStyle === 'avatar' ? (
                        <img src={preview} alt="Preview" className="w-28 h-28 rounded-2xl object-cover ring-4 ring-indigo-100 shadow-lg" />
                    ) : (
                        <img src={preview} alt="Preview" className="max-h-48 rounded-xl shadow-md border border-gray-200" />
                    )}
                    <p className="text-sm text-gray-600">{fileName}</p>
                </div>
            ) : (
                <div className="flex flex-col items-center gap-2">
                    <div className="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                        <svg className="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                    </div>
                    <p className="text-sm text-gray-600">Seret file ke sini atau <span className="text-indigo-600 font-medium">klik untuk memilih</span></p>
                    <p className="text-xs text-gray-400">Maks. {Math.round(maxSize / 1024 / 1024)} MB</p>
                </div>
            )}
            <input
                type="file"
                accept={accept}
                onChange={(e) => e.target.files?.[0] && handleFile(e.target.files[0])}
                className="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
            />
            {error && <p className="mt-2 text-sm text-red-500">{error}</p>}
        </div>
    );
};

export default FileUploader;
