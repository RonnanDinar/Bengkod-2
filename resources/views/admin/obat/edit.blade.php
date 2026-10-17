<x-layouts.app title="Edit Obat">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('obat.index') }}" class="text-slate-600 hover:text-slate-900 transition text-base">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="text-2xl font-bold text-slate-800">
            Edit Obat
        </h2>
    </div>

    {{-- Card --}}
    <div class="bg-white shadow-sm rounded-2xl border border-slate-200/80 p-8">

        <form action="{{ route('obat.update', $obat->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Nama Obat --}}
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Nama Obat <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_obat" value="{{ old('nama_obat', $obat->nama_obat) }}"
                    placeholder="Masukkan nama obat..."
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 text-sm focus:border-indigo-600 focus:outline-none focus:ring-1 focus:ring-indigo-600 transition placeholder:text-slate-400 @error('nama_obat') border-red-500 @enderror"
                    required>
                @error('nama_obat')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                {{-- Kemasan --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Kemasan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="kemasan" value="{{ old('kemasan', $obat->kemasan) }}"
                        placeholder="Contoh: Strip @ 10 Tablet, Botol 60ml..."
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 text-sm focus:border-indigo-600 focus:outline-none focus:ring-1 focus:ring-indigo-600 transition placeholder:text-slate-400 @error('kemasan') border-red-500 @enderror"
                        required>
                    @error('kemasan')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Harga --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Harga (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="harga" value="{{ old('harga', $obat->harga) }}"
                        placeholder="Contoh: 15000" min="0"
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 text-sm focus:border-indigo-600 focus:outline-none focus:ring-1 focus:ring-indigo-600 transition placeholder:text-slate-400 @error('harga') border-red-500 @enderror"
                        required>
                    @error('harga')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#4f46e5] hover:bg-[#4338ca] text-white rounded-xl font-semibold text-sm shadow-sm transition">
                    <i class="fas fa-save text-sm"></i>
                    Update
                </button>

                <a href="{{ route('obat.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-semibold text-sm transition">
                    Batal
                </a>
            </div>

        </form>

    </div>

</x-layouts.app>
