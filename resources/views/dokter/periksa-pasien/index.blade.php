<x-layouts.app title="Periksa Pasien">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">
                Daftar Pasien Periksa
            </h2>
            <p class="text-xs text-slate-500 mt-1">
                Daftar pasien yang mendaftar pada jadwal praktik Anda
            </p>
        </div>
    </div>

    {{-- Alert Success / Message --}}
    @if(session('success'))
    <div class="alert alert-success shadow-sm mb-4 rounded-xl text-sm">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- Card Table --}}
    <div class="card bg-base-100 shadow-sm rounded-2xl border border-slate-200">
        <div class="card-body p-0">

            <div class="overflow-x-auto">
                <table class="table w-full">

                    {{-- Head --}}
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                            <th class="px-6 py-4 text-left">NO. URUT</th>
                            <th class="px-6 py-4 text-left">NAMA PASIEN</th>
                            <th class="px-6 py-4 text-left">KELUHAN</th>
                            <th class="px-6 py-4 text-left">STATUS</th>
                            <th class="px-6 py-4 text-right">AKSI</th>
                        </tr>
                    </thead>

                    {{-- Body --}}
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($daftarPasien as $item)
                        <tr class="hover:bg-slate-50/60 transition">

                            {{-- No Urut / No Antrian --}}
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                <span class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 inline-flex items-center justify-center font-bold text-xs">
                                    {{ $item->no_antrian }}
                                </span>
                            </td>

                            {{-- Nama Pasien --}}
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                {{ $item->pasien->nama ?? $item->pasien->name ?? '-' }}
                                <div class="text-xs font-normal text-slate-400">
                                    No. RM: {{ $item->pasien->no_rm ?? '-' }}
                                </div>
                            </td>

                            {{-- Keluhan --}}
                            <td class="px-6 py-4 text-slate-600 max-w-xs truncate">
                                {{ $item->keluhan ?? '-' }}
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4">
                                @if($item->periksas && $item->periksas->isNotEmpty())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700" style="background-color: #d1fae5; color: #047857;">
                                    <i class="fas fa-check-circle mr-1 text-xs"></i> Sudah Diperiksa
                                </span>
                                @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700" style="background-color: #fef3c7; color: #b45309;">
                                    <i class="fas fa-clock mr-1 text-xs"></i> Menunggu
                                </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center justify-end gap-2">
                                    @if($item->periksas && $item->periksas->isNotEmpty())
                                    <a href="{{ route('periksa-pasien.create', $item->id) }}"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-xs font-semibold shadow-sm transition"
                                        style="background-color: #f59e0b !important; color: #ffffff !important;">
                                        <i class="fas fa-pen-to-square text-xs"></i>
                                        Edit
                                    </a>
                                    @else
                                    <a href="{{ route('periksa-pasien.create', $item->id) }}"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold shadow-sm transition"
                                        style="background-color: #2563eb !important; color: #ffffff !important;">
                                        <i class="fas fa-stethoscope text-xs"></i>
                                        Periksa
                                    </a>
                                    @endif
                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-14 text-slate-400">
                                <i class="fas fa-bed-pulse text-4xl mb-3 block text-slate-300"></i>
                                Belum ada pasien yang mendaftar
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</x-layouts.app>
