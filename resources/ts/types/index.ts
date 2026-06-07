export interface User {
    id: number;
    name: string;
    email: string;
    phone?: string;
    address?: string;
    photo?: string;
    created_at: string;
    updated_at: string;
}

export interface Karyawan {
    id: number;
    user_id: number;
    nik: string;
    nama_lengkap: string;
    email: string;
    telepon: string;
    alamat: string;
    tanggal_lahir: string;
    tanggal_gabung: string;
    status_karyawan: 'tetap' | 'kontrak' | 'percobaan';
    foto?: string;
    jabatan_id: number;
    satuan_kerja_id: number;
    jabatan?: Jabatan;
    satuan_kerja?: SatuanKerja;
    user?: User;
    created_at: string;
    updated_at: string;
}

export interface Jabatan {
    id: number;
    nama_jabatan: string;
    deskripsi?: string;
    created_at: string;
    updated_at: string;
}

export interface SatuanKerja {
    id: number;
    nama_satuan_kerja: string;
    deskripsi?: string;
    created_at: string;
    updated_at: string;
}

export interface Absensi {
    id: number;
    karyawan_id: number;
    tanggal: string;
    jam_masuk?: string;
    jam_keluar?: string;
    status: 'hadir' | 'izin' | 'sakit' | 'alpha' | 'terlambat';
    keterangan?: string;
    bukti_absen?: string;
    karyawan?: Karyawan;
    created_at: string;
    updated_at: string;
}

export interface PengajuanIzin {
    id: number;
    karyawan_id: number;
    tanggal_mulai: string;
    tanggal_selesai: string;
    kategori: 'sakit' | 'cuti' | 'izin' | 'lainnya';
    keterangan: string;
    file_bukti?: string;
    status: 'pending' | 'disetujui' | 'ditolak';
    disetujui_oleh?: number;
    catatan_approve?: string;
    karyawan?: Karyawan;
    created_at: string;
    updated_at: string;
}

export interface Tugas {
    id: number;
    judul: string;
    deskripsi?: string;
    assigned_by: number;
    assigned_to: number;
    tanggal_tenggat: string;
    status: 'baru' | 'diproses' | 'selesai' | 'terlambat';
    bukti_penyelesaian?: string;
    catatan?: string;
    selesai_at?: string;
    pemberiTugas?: User;
    penerimaTugas?: User;
    created_at: string;
    updated_at: string;
}

export interface Penilaian {
    id: number;
    karyawan_id: number;
    periode: string;
    nilai_disiplin: number;
    nilai_kualitas: number;
    nilai_tanggung_jawab: number;
    nilai_komunikasi: number;
    nilai_inisiatif: number;
    total_nilai: number;
    catatan?: string;
    dinilai_by: number;
    karyawan?: Karyawan;
    created_at: string;
    updated_at: string;
}

export interface Penggajian {
    id: number;
    karyawan_id: number;
    periode: string;
    gaji_pokok: number;
    tunjangan_jabatan: number;
    tunjangan_transport: number;
    tunjangan_makan: number;
    tunjangan_lainnya: number;
    potongan_absen: number;
    potongan_lainnya: number;
    total_gaji: number;
    status: 'pending' | 'dibayar';
    slip_gaji?: string;
    karyawan?: Karyawan;
    created_at: string;
    updated_at: string;
}

export interface Dokumen {
    id: number;
    karyawan_id: number;
    nama_dokumen: string;
    jenis_dokumen: string;
    file_path: string;
    file_size: number;
    karyawan?: Karyawan;
    created_at: string;
    updated_at: string;
}

export interface DashboardStats {
    totalKaryawan: number;
    absensiHariIni: number;
    izinPending: number;
    tugasPending: number;
}

export interface ApiResponse<T> {
    success: boolean;
    data?: T;
    message?: string;
    errors?: Record<string, string[]>;
}
