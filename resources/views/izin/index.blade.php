@extends('layouts.app')

@section('title', 'Pengajuan Izin')
@section('subtitle', 'Kelola pengajuan izin, sakit, dan cuti karyawan')

@section('content')
<!-- Page Header -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-indigo-700 via-purple-700 to-blue-700">Pengajuan Izin</h1>
        <p class="text-sm text-gray-600 mt-1">Total {{ $izin->total() }} pengajuan tercatat</p>
    </div>
    <a href="{{ route('leaves.create') }}" class="btn-primary">
        <i class="fa-solid fa-plus mr-2"></i>Ajukan Izin
    </a>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="card p-5 border-l-4 border-yellow-400">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">Menunggu</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $izin->where('status', 'pending')->count() }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-400 to-amber-500 flex items-center justify-center text-white shadow-md">
                <i class="fa-solid fa-hourglass-half text-xl"></i>
            </div>
        </div>
    </div>
    <div class="card p-5 border-l-4 border-green-400">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">Disetujui</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $izin->where('status', 'disetujui')->count() }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-white shadow-md">
                <i class="fa-solid fa-circle-check text-xl"></i>
            </div>
        </div>
    </div>
    <div class="card p-5 border-l-4 border-red-400">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase">Ditolak</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $izin->where('status', 'ditolak')->count() }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-pink-600 flex items-center justify-center text-white shadow-md">
                <i class="fa-solid fa-circle-xmark text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3">
        <select name="status" class="input-field md:w-48">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
            <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
        </select>
        <select name="karyawan_id" class="input-field md:w-64">
            <option value="">Semua Karyawan</option>
            @foreach($karyawan as $k)
                <option value="{{ $k->id }}" {{ request('karyawan_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_lengkap }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-primary">
            <i class="fa-solid fa-filter mr-2"></i>Filter
        </button>
        @if(request()->hasAny(['status', 'karyawan_id']))
            <a href="{{ route('leaves.index') }}" class="btn-secondary">
                <i class="fa-solid fa-times mr-2"></i>Reset
            </a>
        @endif
    </form>
</div>

<!-- Data Table -->
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-indigo-50/80 to-purple-50/80">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Karyawan</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Jenis Izin</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Periode</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-indigo-700 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($izin as $item)
                <tr class="hover:bg-indigo-50/30 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-9 w-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                {{ strtoupper(substr($item->karyawan?->nama_lengkap ?? '?', 0, 1)) }}
                            </div>
                            <div class="ml-3 text-sm font-semibold text-gray-900">{{ $item->karyawan?->nama_lengkap ?? 'Karyawan tidak ditemukan' }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $jenisBadge = [
                                'sakit' => 'badge-info',
                                'cuti' => 'badge-warning',
                                'izin_khusus' => 'badge-primary'
                            ];
                        @endphp
                        <span class="{{ $jenisBadge[$item->jenis_izin] ?? 'badge-primary' }}">
                            <i class="fa-solid {{ $item->jenis_izin == 'sakit' ? 'fa-head-side-virus' : ($item->jenis_izin == 'cuti' ? 'fa-umbrella-beach' : 'fa-file-circle-plus') }} mr-1"></i>
                            {{ ucfirst(str_replace('_', ' ', $item->jenis_izin)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        <i class="fa-regular fa-calendar text-indigo-400 mr-1"></i>
                        {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}
                        <span class="text-gray-400 mx-1">→</span>
                        {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $statusClass = [
                                'pending' => 'badge-warning',
                                'disetujui' => 'badge-success',
                                'ditolak' => 'badge-danger'
                            ];
                            $statusLabel = [
                                'pending' => 'Menunggu',
                                'disetujui' => 'Disetujui',
                                'ditolak' => 'Ditolak'
                            ];
                        @endphp
                        <span class="{{ $statusClass[$item->status] ?? 'badge-primary' }}">
                            <i class="fa-solid {{ $item->status == 'pending' ? 'fa-hourglass-half' : ($item->status == 'disetujui' ? 'fa-circle-check' : 'fa-circle-xmark') }} mr-1"></i>
                            {{ $statusLabel[$item->status] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-1.5">
                            <a href="{{ route('leaves.show', $item) }}" class="text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 px-2 py-1 rounded-lg transition-colors" title="Lihat Detail">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            @if($item->status == 'pending')
                                <a href="{{ route('leaves.edit', $item) }}" class="text-amber-600 hover:text-amber-900 hover:bg-amber-50 px-2 py-1 rounded-lg transition-colors" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <button onclick="showApproveModal({{ $item->id }})" class="text-green-600 hover:text-green-900 hover:bg-green-50 px-2 py-1 rounded-lg transition-colors" title="Setujui">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                                <button onclick="showRejectModal({{ $item->id }})" class="text-red-600 hover:text-red-900 hover:bg-red-50 px-2 py-1 rounded-lg transition-colors" title="Tolak">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center mb-3">
                                <i class="fa-solid fa-file-medical text-2xl text-indigo-500"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Belum ada pengajuan izin</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($izin->hasPages())
    <div class="px-6 py-4 bg-gradient-to-r from-gray-50/50 to-indigo-50/30 border-t border-gray-100">
        {{ $izin->links() }}
    </div>
    @endif
</div>

<!-- Modal Approve -->
<div id="approveModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="hideAllModals()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form method="POST" action="" id="approveForm" class="p-6 space-y-4">
                @csrf @method('PATCH')
                <div class="flex items-center gap-3 pb-2 border-b border-gray-100">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-white">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Setujui Pengajuan Izin</h3>
                </div>
                <p class="text-sm text-gray-500">Tambahkan catatan verifikasi (opsional)</p>
                <textarea name="catatan_verifikasi" rows="3" class="input-field" placeholder="Tambahkan catatan..."></textarea>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="hideAllModals()" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-success">Setujui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" onclick="hideAllModals()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form method="POST" action="" id="rejectForm" class="p-6 space-y-4">
                @csrf @method('PATCH')
                <div class="flex items-center gap-3 pb-2 border-b border-gray-100">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-500 to-pink-600 flex items-center justify-center text-white">
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Tolak Pengajuan Izin</h3>
                </div>
                <p class="text-sm text-gray-500">Alasan penolakan <span class="text-red-500">*</span></p>
                <textarea name="catatan_verifikasi" rows="3" class="input-field" placeholder="Masukkan alasan penolakan..." required></textarea>
                <div class="flex justify-end gap-3 pt-2">
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
