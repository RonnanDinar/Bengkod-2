<x-layouts.app title="Data Dokter">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Data Dokter
        </h2>

        <a href="{{ route('dokter.create') }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-xl text-sm font-semibold shadow-sm transition">
            <i class="fas fa-plus text-xs"></i>
            Tambah Dokter
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
                            <th class="px-6 py-4 text-left">NAMA DOKTER</th>
                            <th class="px-6 py-4 text-left">EMAIL</th>
                            <th class="px-6 py-4 text-left">NO. KTP</th>
                            <th class="px-6 py-4 text-left">NO. HP</th>
                            <th class="px-6 py-4 text-left">ALAMAT</th>
                            <th class="px-6 py-4 text-left">POLI</th>
                            <th class="px-6 py-4 text-right">AKSI</th>
                        </tr>
                    </thead>

                    {{-- Body --}}
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($dokters as $dokter)
                        <tr class="hover:bg-slate-50/60 transition">

                            {{-- Nama Dokter --}}
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                {{ $dokter->nama }}
                            </td>

                            {{-- Email --}}
                            <td class="px-6 py-4 text-slate-600">
                                {{ $dokter->email }}
                            </td>

                            {{-- No KTP --}}
                            <td class="px-6 py-4 text-slate-600">
                                {{ $dokter->no_ktp }}
                            </td>

                            {{-- No HP --}}
                            <td class="px-6 py-4 text-slate-600">
                                {{ $dokter->no_hp }}
                            </td>

                            {{-- Alamat --}}
                            <td class="px-6 py-4 text-slate-600">
                                {{ $dokter->alamat }}
                            </td>

                            {{-- Poli (Badge) --}}
                            <td class="px-6 py-4">
                                @if($dokter->poli)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-600">
                                    {{ $dokter->poli->nama_poli }}
                                </span>
                                @else
                                <span class="text-slate-400">-</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center justify-end gap-2">

                                    {{-- Edit --}}
                                    <a href="{{ route('dokter.edit', $dokter->id) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                                        <i class="fas fa-pen-to-square text-xs"></i>
                                        Edit
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('dokter.destroy', $dokter->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Yakin ingin menghapus dokter ini?')"
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
                            <td colspan="7" class="text-center py-14 text-slate-400">
                                <i class="fas fa-user-doctor text-4xl mb-3 block text-slate-300"></i>
                                Belum ada data dokter
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</x-layouts.app>
