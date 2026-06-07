import React, { useState, useMemo } from 'react';
import { createRoot } from 'react-dom/client';

interface Column<T> {
    key: string;
    label: string;
    sortable?: boolean;
    render?: (item: T) => React.ReactNode;
}

interface DataTableProps<T> {
    columns: Column<T>[];
    data: T[];
    searchable?: boolean;
    pageSize?: number;
    onRowClick?: (item: T) => void;
}

function DataTable<T extends Record<string, unknown>>({
    columns,
    data,
    searchable = true,
    pageSize = 10,
    onRowClick,
}: DataTableProps<T>) {
    const [search, setSearch] = useState('');
    const [sortKey, setSortKey] = useState<string>('');
    const [sortDir, setSortDir] = useState<'asc' | 'desc'>('asc');
    const [page, setPage] = useState(1);

    const filtered = useMemo(() => {
        let result = [...data];
        if (search) {
            const q = search.toLowerCase();
            result = result.filter((item) =>
                columns.some((col) => String(item[col.key] || '').toLowerCase().includes(q))
            );
        }
        if (sortKey) {
            result.sort((a, b) => {
                const aVal = a[sortKey];
                const bVal = b[sortKey];
                const cmp = String(aVal).localeCompare(String(bVal), 'id');
                return sortDir === 'asc' ? cmp : -cmp;
            });
        }
        return result;
    }, [data, search, sortKey, sortDir, columns]);

    const totalPages = Math.ceil(filtered.length / pageSize);
    const paged = filtered.slice((page - 1) * pageSize, page * pageSize);

    const handleSort = (key: string) => {
        if (sortKey === key) {
            setSortDir(sortDir === 'asc' ? 'desc' : 'asc');
        } else {
            setSortKey(key);
            setSortDir('asc');
        }
    };

    return (
        <div>
            {searchable && (
                <div className="mb-4">
                    <div className="relative">
                        <svg className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input
                            type="text"
                            placeholder="Cari..."
                            value={search}
                            onChange={(e) => { setSearch(e.target.value); setPage(1); }}
                            className="input-field pl-10"
                        />
                    </div>
                </div>
            )}
            <div className="overflow-x-auto rounded-xl border border-gray-200">
                <table className="min-w-full divide-y divide-gray-200">
                    <thead className="bg-gray-50">
                        <tr>
                            {columns.map((col) => (
                                <th
                                    key={col.key}
                                    onClick={() => col.sortable !== false && handleSort(col.key)}
                                    className={`px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase ${col.sortable !== false ? 'cursor-pointer hover:text-gray-700' : ''}`}
                                >
                                    <div className="flex items-center gap-1">
                                        {col.label}
                                        {sortKey === col.key && (
                                            <span className="text-indigo-600">{sortDir === 'asc' ? '↑' : '↓'}</span>
                                        )}
                                    </div>
                                </th>
                            ))}
                        </tr>
                    </thead>
                    <tbody className="bg-white divide-y divide-gray-200">
                        {paged.map((item, idx) => (
                            <tr
                                key={idx}
                                onClick={() => onRowClick?.(item)}
                                className={onRowClick ? 'cursor-pointer hover:bg-indigo-50/50 transition-colors' : ''}
                            >
                                {columns.map((col) => (
                                    <td key={col.key} className="px-4 py-3 text-sm text-gray-900">
                                        {col.render ? col.render(item) : String(item[col.key] || '-')}
                                    </td>
                                ))}
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
            {totalPages > 1 && (
                <div className="mt-4 flex items-center justify-between">
                    <p className="text-sm text-gray-500">
                        Menampilkan {((page - 1) * pageSize) + 1}-{Math.min(page * pageSize, filtered.length)} dari {filtered.length}
                    </p>
                    <div className="flex gap-1">
                        {Array.from({ length: totalPages }, (_, i) => i + 1).map((p) => (
                            <button
                                key={p}
                                onClick={() => setPage(p)}
                                className={`w-8 h-8 rounded-lg text-sm font-medium transition-all ${
                                    p === page
                                        ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-md'
                                        : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50'
                                }`}
                            >
                                {p}
                            </button>
                        ))}
                    </div>
                </div>
            )}
        </div>
    );
}

export default DataTable;
