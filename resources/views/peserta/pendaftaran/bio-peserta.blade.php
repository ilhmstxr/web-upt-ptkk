@extends('peserta.layout.main')

@section('title', 'Lampiran Pendaftaran Pelatihan')
@section('content')

    <main class="form-container">
        <form id="registrationForm" action="{{ route('pendaftaran.store') }}" method="GET">
            <div class="form-row">
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" placeholder="Masukkan Nama" />
                </div>
                <div class="form-group">
                    <label for="nik">NIK (16 digit)</label>
                    <input type="text" id="nik" name="nik" placeholder="Masukkan NIK 16 digit"
                        maxlength="16" />
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="tempat-lahir">Tempat Lahir</label>
                    <input type="text" id="tempat-lahir" name="tempat-lahir" placeholder="Masukkan Tempat Lahir" />
                </div>
                <div class="form-group">
                    <label for="tanggal-lahir">Tanggal Lahir</label>
                    <input type="date" id="tanggal-lahir" name="tanggal-lahir" placeholder="Masukkan Tanggal Lahir" />
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="jenis-kelamin">Jenis Kelamin</label>
                    <select id="jenis-kelamin" name="jenis-kelamin">
                        <option value="">Masukkan Jenis Kelamin</option>
                        <option value="laki-laki">Laki-laki</option>
                        <option value="perempuan">Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="agama">Agama</label>
                    <select id="agama" name="agama">
                        <option value="">Masukkan Agama</option>
                        <option value="islam">Islam</option>
                        <option value="kristen">Kristen</option>
                        <option value="katolik">Katolik</option>
                        <option value="hindu">Hindu</option>
                        <option value="buddha">Buddha</option>
                        <option value="konghucu">Konghucu</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group full-width">
                    <label for="alamat">Alamat Tempat Tinggal</label>
                    <textarea id="alamat" name="alamat" placeholder="Masukkan alamat lengkap tempat tinggal"></textarea>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group full-width">
                    <label for="nomor-hp">Nomor Handphone</label>
                    <input type="tel" id="nomor-hp" name="nomor-hp" placeholder="Format Angka (08)" maxlength="15"
                        pattern="[0-9]{10,15}" title="Nomor HP hanya boleh angka dan maksimal 15 digit" />
                </div>
            </div>

            <div class="form-row">
                <div class="form-group full-width">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan Email" />
                </div>
            </div>

            <button type="submit" class="submit-btn">Selanjutnya</button>
        </form>
    </main>
    </div>
    </div>

    <script>
        // Set maximum date for birth date (today)
        const tanggalLahirInput = document.getElementById("tanggal-lahir");
        const today = new Date().toISOString().split("T")[0];
        tanggalLahirInput.setAttribute("max", today);

        // NIK validation function
        function validateNIK(nik) {
            const cleanNIK = nik.replace(/\D/g, "");
            return cleanNIK.length === 16;
        }

        // Phone number input validation (only numbers)
        const inputHP = document.getElementById("nomor-hp");
        inputHP.addEventListener("input", function() {
            this.value = this.value.replace(/\D/g, "");
        });

        // NIK input validation (only numbers and real-time feedback)
        document.getElementById("nik").addEventListener("input", function() {
            const nikValue = this.value;
            const cleanNIK = nikValue.replace(/\D/g, "");

            // Only allow numbers and limit to 16 digits
            if (cleanNIK !== nikValue) {
                this.value = cleanNIK.slice(0, 16);
            }

            // Visual feedback
            if (cleanNIK.length === 16) {
                this.style.borderColor = "#10b981"; // Green for valid
            } else if (cleanNIK.length > 0) {
                this.style.borderColor = "#f59e0b"; // Orange for incomplete
            } else {
                this.style.borderColor = "#e5e7eb"; // Default
            }
        });

        // Form submission handler
        document.getElementById("registrationForm").addEventListener("submit", function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData);

            // Check if all required fields are filled
            let isValid = true;
            const requiredFields = ["nama", "nik", "tempat-lahir", "tanggal-lahir", "jenis-kelamin", "agama",
                "alamat", "nomor-hp", "email"
            ];

            requiredFields.forEach((field) => {
                const input = document.getElementById(field);
                if (!data[field] || data[field].trim() === "") {
                    input.style.borderColor = "#ef4444";
                    isValid = false;
                } else {
                    input.style.borderColor = "#e5e7eb";
                }
            });

            // Specific NIK validation
            if (data.nik && !validateNIK(data.nik)) {
                document.getElementById("nik").style.borderColor = "#ef4444";
                isValid = false;
                alert("NIK harus berisi 16 digit angka!");
                return;
            }

            // Birth date validation
            const tglLahirValue = data["tanggal-lahir"];
            if (tglLahirValue && new Date(tglLahirValue) > new Date(today)) {
                alert("Tanggal lahir tidak boleh di masa depan.");
                tanggalLahirInput.focus();
                tanggalLahirInput.style.borderColor = "#ef4444";
                isValid = false;
            }

            if (isValid) {
                // Save data to localStorage for next step (optional)
                localStorage.setItem("biodataForm", JSON.stringify(data));

                // Show success message and redirect
                alert("Data berhasil disimpan! Melanjutkan ke tahap Biodata Sekolah...");

                // Redirect to biodata sekolah page
                window.location.href = "biodata-sekolah.html";
            } else {
                alert("Mohon lengkapi semua field yang diperlukan!");
            }
        });

        // Reset border color when user starts typing
        const inputs = document.querySelectorAll("input, select, textarea");
        inputs.forEach((input) => {
            input.addEventListener("input", function() {
                if (this.style.borderColor === "rgb(239, 68, 68)") {
                    // Only reset if it was red (error state)
                    this.style.borderColor = "#e5e7eb";
                }
            });
        });
    </script>
@endsection
