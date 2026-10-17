<x-layouts.app title="Jadwal Periksa">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Jadwal Periksa
        </h2>

        <a href="{{ route('jadwal-periksa.create') }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-xl text-sm font-semibold shadow-sm transition">
            <i class="fas fa-plus text-xs"></i>
            Tambah Jadwal
        </a>
    </div>

    {{-- Card Table --}}
    <div class="card bg-base-100 shadow-sm rounded-2xl border border-slate-200">
        <div class="card-body p-0">

            <div class="overflow-x-auto">
                <table class="table w-full">

                    {{-- Head --}}
                    <thead>
                        <tr class="border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                            <th class="px-6 py-4 text-left">HARI</th>
                            <th class="px-6 py-4 text-left">JAM MULAI</th>
                            <th class="px-6 py-4 text-left">JAM SELESAI</th>
                            <th class="px-6 py-4 text-left">STATUS</th>
                            <th class="px-6 py-4 text-right">AKSI</th>
                        </tr>
                    </thead>

                    {{-- Body --}}
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($jadwals as $jadwal)
                        <tr class="hover:bg-slate-50/60 transition">

                            {{-- Hari --}}
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                {{ $jadwal->hari }}
                            </td>

                            {{-- Jam Mulai --}}
                            <td class="px-6 py-4 text-slate-600">
                                <span class="inline-flex items-center gap-1.5 font-medium">
                                    <i class="far fa-clock text-xs text-slate-400"></i>
                                    {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} WIB
                                </span>
                            </td>

                            {{-- Jam Selesai --}}
                            <td class="px-6 py-4 text-slate-600">
                                <span class="inline-flex items-center gap-1.5 font-medium">
                                    <i class="far fa-clock text-xs text-slate-400"></i>
                                    {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }} WIB
                                </span>
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                    Aktif
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center justify-end gap-2">

                                    {{-- Edit --}}
                                    <a href="{{ route('jadwal-periksa.edit', $jadwal->id) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                                        <i class="fas fa-pen-to-square text-xs"></i>
                                        Edit
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('jadwal-periksa.destroy', $jadwal->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Yakin ingin menghapus jadwal periksa ini?')"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                                            <i class="fas fa-trash text-xs"></i>
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-14 text-slate-400">
                                <i class="fas fa-calendar-days text-4xl mb-3 block text-slate-300"></i>
                                Belum ada jadwal periksa
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</x-layouts.app>
