<x-layouts.app title="Edit Jadwal Periksa">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('jadwal-periksa.index') }}" class="text-slate-600 hover:text-slate-900 transition text-base">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="text-2xl font-bold text-slate-800">
            Edit Jadwal Periksa
        </h2>
    </div>

    {{-- Card --}}
    <div class="bg-white shadow-sm rounded-2xl border border-slate-200/80 p-8">

        <form action="{{ route('jadwal-periksa.update', $jadwal->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Hari --}}
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Hari Praktik <span class="text-red-500">*</span>
                </label>
                <select name="hari"
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 text-sm focus:border-indigo-600 focus:outline-none focus:ring-1 focus:ring-indigo-600 transition bg-white @error('hari') border-red-500 @enderror"
                    required>
                    <option value="" disabled>Pilih Hari Praktik...</option>
                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                    <option value="{{ $hari }}" {{ old('hari', $jadwal->hari) == $hari ? 'selected' : '' }}>
                        {{ $hari }}
                    </option>
                    @endforeach
                </select>
                @error('hari')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Grid Jam Mulai & Jam Selesai --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                {{-- Jam Mulai --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Jam Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="jam_mulai"
                        value="{{ old('jam_mulai', \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i')) }}"
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 text-sm focus:border-indigo-600 focus:outline-none focus:ring-1 focus:ring-indigo-600 transition @error('jam_mulai') border-red-500 @enderror"
                        required>
                    @error('jam_mulai')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jam Selesai --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Jam Selesai <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="jam_selesai"
                        value="{{ old('jam_selesai', \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i')) }}"
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 text-sm focus:border-indigo-600 focus:outline-none focus:ring-1 focus:ring-indigo-600 transition @error('jam_selesai') border-red-500 @enderror"
                        required>
                    @error('jam_selesai')
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

                <a href="{{ route('jadwal-periksa.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-semibold text-sm transition">
                    Batal
                </a>
            </div>

        </form>

    </div>

</x-layouts.app>
