<x-layouts.app title="Periksa Pasien">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('periksa-pasien.index') }}" class="inline-flex items-center justify-center w-9 h-9 
                  rounded-lg bg-slate-100 text-slate-500 
                  hover:bg-slate-200 transition">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <h2 class="text-2xl font-bold text-slate-800">
            {{ isset($periksa) ? 'Edit Pemeriksaan Pasien' : 'Periksa Pasien' }}
        </h2>
    </div>

    {{-- Error Alert --}}
    @if ($errors->any())
    <div class="alert alert-error shadow-sm mb-6 rounded-xl text-sm">
        <i class="fas fa-circle-exclamation"></i>
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Info Pasien --}}
    @if(isset($daftarPoli))
    <div class="card bg-base-100 shadow-sm rounded-2xl border border-slate-200 mb-6">
        <div class="card-body p-6">
            <h3 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                <i class="fas fa-user-injured text-blue-600"></i>
                Informasi Pasien
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="text-xs text-slate-400 block font-medium">Nama Pasien</span>
                    <span class="font-semibold text-slate-800">{{ $daftarPoli->pasien->nama ?? $daftarPoli->pasien->name ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block font-medium">No. Rekam Medis</span>
                    <span class="font-semibold text-slate-700">{{ $daftarPoli->pasien->no_rm ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block font-medium">Keluhan</span>
                    <span class="text-slate-800">{{ $daftarPoli->keluhan ?: '-' }}</span>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Card Form --}}
    <div class="card bg-base-100 shadow-sm rounded-2xl border border-slate-200">
        <div class="card-body p-8">

            <form action="{{ route('periksa-pasien.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id_daftar_poli" value="{{ $id }}">

                {{-- Pilih Obat --}}
                <div class="form-control mb-5">
                    <label class="label pb-1">
                        <span class="text-sm font-semibold text-gray-700">Pilih Obat <span class="text-red-500">*</span></span>
                    </label>
                    <select id="select-obat" class="select select-bordered w-full rounded-lg border-2 px-4">
                        <option value="">-- Pilih Obat --</option>
                        @foreach ($obats as $obat)
                            <option value="{{ $obat->id }}"
                                data-nama="{{ $obat->nama_obat }}"
                                data-harga="{{ $obat->harga }}">
                                {{ $obat->nama_obat }} ({{ $obat->kemasan }}) - Rp {{ number_format($obat->harga, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Obat Terpilih --}}
                <div class="form-control mb-5">
                    <label class="label pb-1 ">
                        <span class="text-sm font-semibold text-gray-700">Obat Terpilih</span>
                    </label>

                    <ul id="obat-terpilih" class="flex flex-col gap-2 mb-2 min-h-[48px]"></ul>

                    <input type="hidden" name="biaya_periksa" id="biaya_periksa" value="0">
                    <input type="hidden" name="obat_json" id="obat_json">
                </div>

                {{-- Total Harga --}}
                <div class="form-control mb-5">
                    <label class="label pb-1">
                        <span class="text-sm font-semibold text-gray-700">Total Harga (Obat + Jasa Dokter Rp 150.000)</span>
                    </label>
                    <div class="input input-bordered w-full rounded-lg flex items-center bg-slate-50 text-slate-700 font-bold" id="total-harga">
                        Rp 150.000
                    </div>
                </div>

                {{-- Catatan --}}
                <div class="form-control mb-8">
                    <label class="label pb-1">
                        <span class="text-sm font-semibold text-gray-700">Catatan Dokter <span class="text-slate-400 font-normal">(Opsional)</span></span>
                    </label>
                    <textarea name="catatan" id="catatan" rows="4"
                        placeholder="Masukkan diagnosa atau catatan pemeriksaan..."
                        class="textarea textarea-bordered w-full border-2 px-4 py-2 rounded-lg resize-none">{{ old('catatan', $periksa->catatan ?? '') }}</textarea>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-3">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg font-semibold text-sm shadow-sm transition cursor-pointer"
                        style="background-color: #2563eb !important; color: #ffffff !important;">
                        <i class="fas fa-save text-xs"></i>
                        Simpan
                    </button>
                    <a href="{{ route('periksa-pasien.index') }}"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg font-semibold text-sm transition"
                        style="background-color: #f1f5f9 !important; color: #475569 !important; border: 1px solid #e2e8f0;">
                        Batal
                    </a>
                </div>

            </form>
        </div>
    </div>

    <script>
        const selectObat = document.getElementById('select-obat');
        const listObat = document.getElementById('obat-terpilih');
        const inputBiaya = document.getElementById('biaya_periksa');
        const inputObatJson = document.getElementById('obat_json');
        const totalHargaEl = document.getElementById('total-harga');
        const BIAYA_DOKTER = 150000;

        let daftarObat = @json(isset($periksa) && $periksa->detailPeriksas ? $periksa->detailPeriksas->map(fn($d) => ['id' => (string)($d->obat->id ?? $d->id_obat), 'nama' => $d->obat->nama_obat ?? 'Obat', 'harga' => (int)($d->obat->harga ?? 0)]) : []);

        selectObat.addEventListener('change', () => {
            const selectedOption = selectObat.options[selectObat.selectedIndex];
            const id = selectedOption.value;
            const nama = selectedOption.dataset.nama;
            const harga = parseInt(selectedOption.dataset.harga || 0);

            if (!id || daftarObat.some(o => o.id == id)) return;

            daftarObat.push({ id, nama, harga });
            renderObat();
            selectObat.selectedIndex = 0;
        });

        function renderObat() {
            listObat.innerHTML = '';
            let totalObat = 0;

            if (daftarObat.length === 0) {
                listObat.innerHTML = '<li class="text-xs text-slate-400 italic py-2">Belum ada obat yang dipilih.</li>';
            }

            daftarObat.forEach((obat, index) => {
                totalObat += obat.harga;

                const item = document.createElement('li');
                item.className = 'flex items-center justify-between px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700';
                item.innerHTML = `
                    <span>${obat.nama} — <span class="font-semibold">Rp ${obat.harga.toLocaleString('id-ID')}</span></span>
                    <button type="button"
                        onclick="hapusObat(${index})"
                        class="btn btn-sm text-white border-none rounded-lg px-3 cursor-pointer"
                        style="background-color: #ef4444 !important; color: #ffffff !important;">
                        <i class="fas fa-trash"></i>
                    </button>
                `;
                listObat.appendChild(item);
            });

            inputBiaya.value = totalObat;
            totalHargaEl.textContent = `Rp ${(totalObat + BIAYA_DOKTER).toLocaleString('id-ID')}`;
            inputObatJson.value = JSON.stringify(daftarObat.map(o => o.id));
        }

        function hapusObat(index) {
            daftarObat.splice(index, 1);
            renderObat();
        }

        // Render on page load (for preloaded obats during edit)
        renderObat();

        document.querySelector('form').addEventListener('submit', function (e) {
            if (daftarObat.length === 0) {
                e.preventDefault();
                alert('Silakan pilih minimal 1 jenis obat untuk pasien.');
            }
        });
    </script>

</x-layouts.app>
