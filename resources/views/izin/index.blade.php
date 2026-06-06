@extends('layouts.app')

@section('title', 'Pengajuan Izin')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Pengajuan Izin</h1>
        <p class="text-gray-600 mt-1">Kelola pengajuan izin, sakit, dan cuti</p>
    </div>
    <a href="{{ route('leaves.create') }}" class="btn-primary">Ajukan Izin</a>
</div>

<div class="card overflow-hidden">
    <div class="p-6">
        <form method="GET" class="mb-6 flex gap-3">
            <select name="status" class="input-field">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <select name="karyawan_id" class="input-field">
                <option value="">Semua Karyawan</option>
                @foreach($karyawan as $k)
                    <option value="{{ $k->id }}" {{ request('karyawan_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_lengkap }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-secondary">Filter</button>
        </form>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Karyawan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Izin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($izin as $item)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item->karyawan?->nama_lengkap }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $item->jenis_izin)) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusClass = ['pending'=>'bg-yellow-100 text-yellow-800','disetujui'=>'bg-green-100 text-green-800','ditolak'=>'bg-red-100 text-red-800'];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass[$item->status] }}">
                                {{ $item->status == 'pending' ? 'Menunggu' : ($item->status == 'disetujui' ? 'Disetujui' : 'Ditolak') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <a href="{{ route('leaves.show', $item) }}" class="text-blue-600 hover:text-blue-900 mr-3">Lihat</a>
                            @if($item->status == 'pending')
                                <button onclick="showApproveModal({{ $item->id }})" class="text-green-600 hover:text-green-900 mr-3">Setujui</button>
                                <button onclick="showRejectModal({{ $item->id }})" class="text-red-600 hover:text-red-900">Tolak</button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $izin->links() }}</div>
    </div>
</div>

<!-- Modal Approve -->
<div id="approveModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity" onclick="hideAllModals()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form method="POST" action="" id="approveForm" class="p-6 space-y-4">
                @csrf @method('PATCH')
                <h3 class="text-lg font-bold text-gray-900">Setujui Pengajuan Izin</h3>
                <p class="text-sm text-gray-500">Tambahkan catatan verifikasi (opsional)</p>
                <textarea name="catatan_verifikasi" rows="2" class="input-field" placeholder="Tambahkan catatan..."></textarea>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="hideAllModals()" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary">Setujui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity" onclick="hideAllModals()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form method="POST" action="" id="rejectForm" class="p-6 space-y-4">
                @csrf @method('PATCH')
                <h3 class="text-lg font-bold text-gray-900">Tolak Pengajuan Izin</h3>
                <p class="text-sm text-gray-500">Alasan penolakan <span class="text-red-500">*</span></p>
                <textarea name="catatan_verifikasi" rows="2" class="input-field" placeholder="Masukkan alasan penolakan..." required></textarea>
                <div class="flex justify-end gap-3 pt-4">
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
