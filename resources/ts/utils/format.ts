/**
 * Format tanggal ke format Indonesia
 */
export function formatDate(date: string | Date): string {
    const d = new Date(date);
    return d.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    });
}

/**
 * Format tanggal singkat
 */
export function formatShortDate(date: string | Date): string {
    const d = new Date(date);
    return d.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
}

/**
 * Format mata uang Rupiah
 */
export function formatRupiah(amount: number): string {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
}

/**
 * Format angka dengan pemisah ribuan
 */
export function formatNumber(num: number): string {
    return new Intl.NumberFormat('id-ID').format(num);
}

/**
 * Format waktu dari timestamp
 */
export function formatTime(date: string | Date): string {
    const d = new Date(date);
    return d.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit',
    });
}

/**
 * Format ukuran file
 */
export function formatFileSize(bytes: number): string {
    if (bytes === 0) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
}

/**
 * Ambil inisial dari nama
 */
export function getInitials(name: string): string {
    return name
        .split(' ')
        .map((n) => n[0])
        .join('')
        .toUpperCase()
        .slice(0, 2);
}

/**
 * Potong teks dengan ellipsis
 */
export function truncate(text: string, length: number): string {
    if (text.length <= length) return text;
    return text.substring(0, length) + '...';
}
