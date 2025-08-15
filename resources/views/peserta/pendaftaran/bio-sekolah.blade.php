@extends('peserta.layout.main')

@section('title', 'Lampiran Pendaftaran Pelatihan')
@section('content')
    <main class="form-container">
        <form id="biodataSekolahForm" action="{{ route('pendaftaran.store') }}" method="GET">
            <div class="form-row">
                <div class="form-group">
                    <label for="asal-lembaga">Asal Lembaga Sekolah</label>
                    <input type="text" id="asal-lembaga" name="asal-lembaga" placeholder="Masukkan Asal Lembaga" />
                </div>
                <div class="form-group">
                    <label for="alamat-sekolah">Alamat Sekolah</label>
                    <input type="text" id="alamat-sekolah" name="alamat-sekolah" placeholder="Masukkan Alamat Sekolah" />
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="kompetensi-bidang">Kompetensi/Bidang Keahlian</label>
                    <select id="kompetensi-bidang" name="kompetensi-bidang">
                        <option value="">Masukkan Kompetensi</option>
                        <option value="teknik-informatika">Teknik Informatika</option>
                        <option value="teknik-mesin">Teknik Mesin</option>
                        <option value="teknik-elektro">Teknik Elektro</option>
                        <option value="teknik-sipil">Teknik Sipil</option>
                        <option value="akuntansi">Akuntansi</option>
                        <option value="administrasi-perkantoran">Administrasi Perkantoran</option>
                        <option value="pemasaran">Pemasaran</option>
                        <option value="multimedia">Multimedia</option>
                        <option value="tata-boga">Tata Boga</option>
                        <option value="tata-busana">Tata Busana</option>
                        <option value="tata-kecantikan">Tata Kecantikan</option>
                        <option value="perhotelan">Perhotelan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="kelas">Kelas</label>
                    <select id="kelas" name="kelas">
                        <option value="">Masukkan Kelas</option>
                        <option value="X">Kelas X</option>
                        <option value="XI">Kelas XI</option>
                        <option value="XII">Kelas XII</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group full-width">
                    <label for="cabang-dinas">Cabang Dinas Wilayah</label>
                    <select id="cabang-dinas" name="cabang-dinas">
                        <option value="">Masukkan Dinas Wilayah</option>
                        <option value="surabaya">Cabang Dinas Wilayah Surabaya</option>
                        <option value="malang">Cabang Dinas Wilayah Malang</option>
                        <option value="kediri">Cabang Dinas Wilayah Kediri</option>
                        <option value="madiun">Cabang Dinas Wilayah Madiun</option>
                        <option value="bojonegoro">Cabang Dinas Wilayah Bojonegoro</option>
                        <option value="pamekasan">Cabang Dinas Wilayah Pamekasan</option>
                        <option value="jember">Cabang Dinas Wilayah Jember</option>
                    </select>
                </div>
            </div>

            <div class="button-container">
                <button type="button" class="btn btn-secondary" onclick="goBack()">Kembali</button>
                <button type="submit" class="btn btn-primary">Selanjutnya</button>
            </div>
        </form>
    </main>

    <script>
        document.getElementById("biodataSekolahForm").addEventListener("submit", function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);

            // Validate  fields
            let isValid = true;
            const Fields = ["asal-lembaga", "alamat-sekolah", "kompetensi-bidang", "kelas", "cabang-dinas"];

            Fields.forEach((field) => {
                const input = document.getElementById(field);
                if (!data[field] || data[field].trim() === "") {
                    input.style.borderColor = "#ef4444";
                    isValid = false;
                } else {
                    input.style.borderColor = "#e5e7eb";
                }
            });

            if (isValid) {
                alert("Biodata sekolah berhasil disimpan! Melanjutkan ke tahap Lampiran...");
                // Redirect to lampiran.html
                window.location.href = "lampiran.html";
            } else {
                alert("Mohon lengkapi semua field yang diperlukan!");
            }
        });

        function goBack() {
            window.location.href = "index.html";
        }

        // Reset border color on input
        const inputs = document.querySelectorAll("input, select");
        inputs.forEach((input) => {
            input.addEventListener("input", function() {
                this.style.borderColor = "#e5e7eb";
            });
        });
    </script>
@endsection
