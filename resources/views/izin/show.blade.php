@extends('layouts.app')

@section('title', 'Detail Izin')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Detail Pengajuan Izin</h1>
    <p class="text-gray-600 mt-1">Informasi lengkap pengajuan izin</p>
</div>

<div class="card max-w-2xl">
    <div class="p-6">
        <dl class="grid grid-cols-1 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">Karyawan</dt>
                <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $izin->karyawan?->nama_lengkap }} ({{ $izin->karyawan?->nik }})</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Jenis Izin</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $izin->jenis_izin)) }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Periode</dt>
                <dd class="mt-1 text-sm text-gray-900">
                    {{ \Carbon\Carbon::parse($izin->tanggal_mulai)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($izin->tanggal_selesai)->format('d F Y') }}
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Alasan</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $izin->alasan }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Status</dt>
                <dd class="mt-1">
                    @php
                        $statusClass = ['pending'=>'bg-yellow-100 text-yellow-800','disetujui'=>'bg-green-100 text-green-800','ditolak'=>'bg-red-100 text-red-800'];
                        $statusLabel = ['pending'=>'Menunggu','disetujui'=>'Disetujui','ditolak'=>'Ditolak'];
                    @endphp
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass[$izin->status] }}">
                        {{ $statusLabel[$izin->status] }}
                    </span>
                </dd>
            </div>
            @if($izin->catatan_verifikasi)
            <div>
                <dt class="text-sm font-medium text-gray-500">Catatan Verifikasi</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $izin->catatan_verifikasi }}</dd>
            </div>
            @endif
            @if($izin->bukti_dokumen)
            <div>
                <dt class="text-sm font-medium text-gray-500">Bukti Dokumen</dt>
                <dd class="mt-1"><a href="{{ asset('storage/' . $izin->bukti_dokumen) }}" target="_blank" class="text-blue-600 hover:text-blue-900">Lihat Dokumen</a></dd>
            </div>
            @endif
        </dl>
    </div>
    <div class="p-6 border-t border-gray-200 flex gap-3">
        <a href="{{ route('leaves.index') }}" class="btn-secondary">Kembali</a>
        @if($izin->status == 'pending')
            <button onclick="showApproveModal({{ $izin->id }})" class="btn-primary">Setujui</button>
            <button onclick="showRejectModal({{ $izin->id }})" class="btn-danger">Tolak</button>
        @endif
    </div>
</div>

<!-- Modal Approve -->
<div id="approveModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity" onclick="hideAllModals()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form method="POST" action="" id="approveForm" class="p-6 space-y-4">
                @csrf @method('PATCH')
                <h3 class="text-lg font-bold text-gray-900">Setujui Pengajuan Izin</h3>
                <textarea name="catatan_verifikasi" rows="2" class="input-field" placeholder="Tambahkan catatan..."></textarea>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="hideAllModals()" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary">Setujui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity" onclick="hideAllModals()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form method="POST" action="" id="rejectForm" class="p-6 space-y-4">
                @csrf @method('PATCH')
                <h3 class="text-lg font-bold text-gray-900">Tolak Pengajuan Izin</h3>
                <p class="text-sm text-red-500">Alasan penolakan wajib diisi</p>
                <textarea name="catatan_verifikasi" rows="2" class="input-field" placeholder="Masukkan alasan..." required></textarea>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="hideAllModals()" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-danger">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showApproveModal(id) {
    document.getElementById('approveForm').action = '{{ url("dashboard/leaves") }}/' + id + '/approve';
    document.getElementById('approveModal').classList.remove('hidden');
}
function showRejectModal(id) {
    document.getElementById('rejectForm').action = '{{ url("dashboard/leaves") }}/' + id + '/reject';
    document.getElementById('rejectModal').classList.remove('hidden');
}
function hideAllModals() {
    document.querySelectorAll('.fixed.inset-0.z-50').forEach(el => el.classList.add('hidden'));
}
</script>
@endpush
@endsection
