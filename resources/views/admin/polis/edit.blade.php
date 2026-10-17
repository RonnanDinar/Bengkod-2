<x-layouts.app title="Edit Poli">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('polis.index') }}" class="text-slate-600 hover:text-slate-900 transition text-base">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="text-2xl font-bold text-slate-800">
            Edit Poli
        </h2>
    </div>

    {{-- Card --}}
    <div class="bg-white shadow-sm rounded-2xl border border-slate-200/80 p-8">

        <form action="{{ route('polis.update', $poli->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Nama Poli --}}
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Nama Poli <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_poli" value="{{ old('nama_poli', $poli->nama_poli) }}"
                    placeholder="Masukkan nama poli..."
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 text-sm focus:border-indigo-600 focus:outline-none focus:ring-1 focus:ring-indigo-600 transition placeholder:text-slate-400 @error('nama_poli') border-red-500 @enderror"
                    required>
                @error('nama_poli')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Keterangan --}}
            <div class="mb-8">
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Keterangan
                </label>
                <textarea name="keterangan" rows="4" placeholder="Masukkan keterangan poli..."
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 text-sm focus:border-indigo-600 focus:outline-none focus:ring-1 focus:ring-indigo-600 transition resize-none placeholder:text-slate-400 @error('keterangan') border-red-500 @enderror">{{ old('keterangan', $poli->keterangan) }}</textarea>
                @error('keterangan')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#4f46e5] hover:bg-[#4338ca] text-white rounded-xl font-semibold text-sm shadow-sm transition">
                    <i class="fas fa-save text-sm"></i>
                    Update
                </button>

                <a href="{{ route('polis.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-semibold text-sm transition">
                    Batal
                </a>
            </div>

        </form>

    </div>

</x-layouts.app>