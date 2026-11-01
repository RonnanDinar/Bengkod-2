<x-layouts.app title="Riwayat Pasien">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">
                Riwayat Pasien
            </h2>
            <p class="text-xs text-slate-500 mt-1">
                Daftar riwayat pemeriksaan pasien yang telah selesai dilakukan
            </p>
        </div>
    </div>

    {{-- Card --}}
    <div class="card bg-base-100 shadow-sm rounded-2xl border border-slate-200">
        <div class="card-body p-0">

            <div class="overflow-x-auto">
                <table class="table w-full">

                    {{-- Head --}}
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                            <th class="px-6 py-4 text-left">NO. ANTRIAN</th>
                            <th class="px-6 py-4 text-left">NAMA PASIEN</th>
                            <th class="px-6 py-4 text-left">KELUHAN</th>
                            <th class="px-6 py-4 text-left">TANGGAL PERIKSA</th>
                            <th class="px-6 py-4 text-left">BIAYA</th>
                            <th class="px-6 py-4 text-right">AKSI</th>
                        </tr>
                    </thead>

                    {{-- Body --}}
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($riwayatPasien as $riwayat)
                        <tr class="hover:bg-slate-50/60 transition">

                            {{-- No Antrian --}}
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                <span class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 inline-flex items-center justify-center font-bold text-xs">
                                    {{ $riwayat->daftarPoli->no_antrian ?? '-' }}
                                </span>
                            </td>

                            {{-- Nama Pasien --}}
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                {{ $riwayat->daftarPoli->pasien->nama ?? $riwayat->daftarPoli->pasien->name ?? '-' }}
                                <div class="text-xs font-normal text-slate-400">
                                    No. RM: {{ $riwayat->daftarPoli->pasien->no_rm ?? '-' }}
                                </div>
                            </td>

                            {{-- Keluhan --}}
                            <td class="px-6 py-4 text-slate-600 max-w-xs truncate">
                                {{ $riwayat->daftarPoli->keluhan ?? '-' }}
                            </td>

                            {{-- Tanggal Periksa --}}
                            <td class="px-6 py-4 text-slate-600">
                                <span class="inline-flex items-center gap-1.5 font-medium">
                                    <i class="far fa-calendar text-xs text-slate-400"></i>
                                    {{ \Carbon\Carbon::parse($riwayat->tgl_periksa)->format('d/m/Y H:i') }} WIB
                                </span>
                            </td>

                            {{-- Biaya --}}
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                Rp {{ number_format($riwayat->biaya_periksa, 0, ',', '.') }}
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center justify-end gap-2">
                                    <a href="{{ route('riwayat-pasien.show', $riwayat->id) }}"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-semibold shadow-sm transition cursor-pointer"
                                        style="background-color: #2563eb !important; color: #ffffff !important;">
                                        <i class="fas fa-eye text-xs"></i>
                                        Detail
                                    </a>
                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-14 text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fas fa-inbox text-4xl text-slate-300 mb-1"></i>
                                    <span class="font-medium">Belum ada riwayat pemeriksaan</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</x-layouts.app>
