import React, { useState, useRef, useEffect } from 'react';

interface Option {
    value: string | number;
    label: string;
}

interface SearchableSelectProps {
    options: Option[];
    value?: string | number;
    onChange: (value: string | number) => void;
    placeholder?: string;
    searchable?: boolean;
}

const SearchableSelect: React.FC<SearchableSelectProps> = ({
    options,
    value,
    onChange,
    placeholder = 'Pilih...',
    searchable = true,
}) => {
    const [isOpen, setIsOpen] = useState(false);
    const [search, setSearch] = useState('');
    const ref = useRef<HTMLDivElement>(null);

    const selected = options.find((o) => o.value === value);

    const filtered = options.filter((o) =>
        o.label.toLowerCase().includes(search.toLowerCase())
    );

    useEffect(() => {
        const handleClickOutside = (e: MouseEvent) => {
            if (ref.current && !ref.current.contains(e.target as Node)) setIsOpen(false);
        };
        document.addEventListener('mousedown', handleClickOutside);
        return () => document.removeEventListener('mousedown', handleClickOutside);
    }, []);

    return (
        <div className="relative" ref={ref}>
            <button
                type="button"
                onClick={() => setIsOpen(!isOpen)}
                className="input-field text-left flex items-center justify-between"
            >
                <span className={selected ? 'text-gray-900' : 'text-gray-400'}>
                    {selected ? selected.label : placeholder}
                </span>
                <svg className={`w-4 h-4 text-gray-400 transition-transform ${isOpen ? 'rotate-180' : ''}`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            {isOpen && (
                <div className="absolute z-50 w-full mt-1 bg-white rounded-xl shadow-xl border border-gray-200 max-h-60 overflow-auto animate-fade-in-down">
                    {searchable && (
                        <div className="p-2 border-b border-gray-100">
                            <input
                                type="text"
                                placeholder="Cari..."
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                className="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/30"
                                autoFocus
                            />
                        </div>
                    )}
                    {filtered.map((opt) => (
                        <button
                            key={opt.value}
                            type="button"
                            onClick={() => { onChange(opt.value); setIsOpen(false); setSearch(''); }}
                            className={`w-full px-4 py-2.5 text-sm text-left hover:bg-indigo-50 transition-colors ${
                                opt.value === value ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-700'
                            }`}
                        >
                            {opt.label}
                        </button>
                    ))}
                    {filtered.length === 0 && (
                        <div className="p-4 text-center text-sm text-gray-500">Tidak ditemukan</div>
                    )}
                </div>
            )}
        </div>
    );
};

export default SearchableSelect;
