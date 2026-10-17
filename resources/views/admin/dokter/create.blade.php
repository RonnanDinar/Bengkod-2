<x-layouts.app title="Tambah Dokter">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('dokter.index') }}" class="text-slate-600 hover:text-slate-900 transition text-base">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="text-2xl font-bold text-slate-800">
            Tambah Dokter
        </h2>
    </div>

    {{-- Card --}}
    <div class="bg-white shadow-sm rounded-2xl border border-slate-200/80 p-8">

        <form action="{{ route('dokter.store') }}" method="POST">
            @csrf

            {{-- Row 1: Nama Dokter & Email --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Nama Dokter <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" value="{{ old('nama') }}"
                        placeholder="Masukkan nama dokter..."
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 text-sm focus:border-indigo-600 focus:outline-none focus:ring-1 focus:ring-indigo-600 transition placeholder:text-slate-400 @error('nama') border-red-500 @enderror"
                        required>
                    @error('nama')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        placeholder="Masukkan email..."
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 text-sm focus:border-indigo-600 focus:outline-none focus:ring-1 focus:ring-indigo-600 transition placeholder:text-slate-400 @error('email') border-red-500 @enderror"
                        required>
                    @error('email')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Row 2: No. KTP & No. HP --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        No. KTP <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="no_ktp" value="{{ old('no_ktp') }}"
                        placeholder="Masukkan No. KTP..."
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 text-sm focus:border-indigo-600 focus:outline-none focus:ring-1 focus:ring-indigo-600 transition placeholder:text-slate-400 @error('no_ktp') border-red-500 @enderror"
                        required>
                    @error('no_ktp')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        No. HP <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="no_hp" value="{{ old('no_hp') }}"
                        placeholder="Masukkan No. HP..."
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 text-sm focus:border-indigo-600 focus:outline-none focus:ring-1 focus:ring-indigo-600 transition placeholder:text-slate-400 @error('no_hp') border-red-500 @enderror"
                        required>
                    @error('no_hp')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Row 3: Alamat --}}
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Alamat <span class="text-red-500">*</span>
                </label>
                <textarea name="alamat" rows="3" placeholder="Masukkan alamat..."
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 text-sm focus:border-indigo-600 focus:outline-none focus:ring-1 focus:ring-indigo-600 transition resize-none placeholder:text-slate-400 @error('alamat') border-red-500 @enderror"
                    required>{{ old('alamat') }}</textarea>
                @error('alamat')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Row 4: Poli --}}
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Poli <span class="text-red-500">*</span>
                </label>
                <select name="id_poli"
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 text-sm focus:border-indigo-600 focus:outline-none focus:ring-1 focus:ring-indigo-600 transition bg-white @error('id_poli') border-red-500 @enderror"
                    required>
                    <option value="">Pilih Poli</option>
                    @foreach($polis as $poli)
                    <option value="{{ $poli->id }}" {{ (string) old('id_poli') === (string) $poli->id ? 'selected' : '' }}>
                        {{ $poli->nama_poli }}
                    </option>
                    @endforeach
                </select>
                @error('id_poli')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Row 5: Password --}}
            <div class="mb-8">
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Password <span class="text-red-500">*</span>
                </label>
                <input type="password" name="password" placeholder="Minimal 8 karakter..."
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 text-sm focus:border-indigo-600 focus:outline-none focus:ring-1 focus:ring-indigo-600 transition placeholder:text-slate-400 @error('password') border-red-500 @enderror"
                    required>
                @error('password')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#4f46e5] hover:bg-[#4338ca] text-white rounded-xl font-semibold text-sm shadow-sm transition">
                    <i class="fas fa-save text-sm"></i>
                    Simpan
                </button>

                <a href="{{ route('dokter.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-semibold text-sm transition">
                    Batal
                </a>
            </div>

        </form>

    </div>

</x-layouts.app>