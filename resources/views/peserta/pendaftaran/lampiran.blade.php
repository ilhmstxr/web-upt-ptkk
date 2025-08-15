@extends('peserta.layout.main')

@section('title', 'Lampiran Pendaftaran Pelatihan')
@section('content')
<main class="form-container">
    <form id="lampiranForm">
        <div class="form-row">
            <div class="form-group">
                <label for="fotocopy-ktp">Unggah Fotocopy KTP</label>
                <div class="file-input-container">
                    <div class="file-status" id="ktp-status">📄 Tidak ada file yang dipilih</div>
                    <button type="button" class="file-btn" onclick="document.getElementById('ktp-file').click()">Pilih
                        Dokumen</button>
                    <input type="file" id="ktp-file" class="file-input" accept=".pdf,.jpg,.jpeg,.png"
                        onchange="handleFileSelect('ktp')" />
                </div>
            </div>
            <div class="form-group">
                <label for="fotocopy-ijazah">Unggah Fotocopy Ijazah Terakhir</label>
                <div class="file-input-container">
                    <div class="file-status" id="ijazah-status">📄 Tidak ada file yang dipilih</div>
                    <button type="button" class="file-btn"
                        onclick="document.getElementById('ijazah-file').click()">Pilih Dokumen</button>
                    <input type="file" id="ijazah-file" class="file-input" accept=".pdf,.jpg,.jpeg,.png"
                        onchange="handleFileSelect('ijazah')" />
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="fotocopy-tugas">Unggah Fotocopy Surat Tugas</label>
                <div class="file-input-container">
                    <div class="file-status" id="tugas-status">📄 Tidak ada file yang dipilih</div>
                    <button type="button" class="file-btn"
                        onclick="document.getElementById('tugas-file').click()">Pilih Dokumen</button>
                    <input type="file" id="tugas-file" class="file-input" accept=".pdf,.jpg,.jpeg,.png"
                        onchange="handleFileSelect('tugas')" />
                </div>
            </div>
            <div class="form-group">
                <label for="surat-sehat">Unggah Surat Sehat</label>
                <div class="file-input-container">
                    <div class="file-status" id="sehat-status">📄 Tidak ada file yang dipilih</div>
                    <button type="button" class="file-btn"
                        onclick="document.getElementById('sehat-file').click()">Pilih Dokumen</button>
                    <input type="file" id="sehat-file" class="file-input" accept=".pdf,.jpg,.jpeg,.png"
                        onchange="handleFileSelect('sehat')" />
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="nomor-tugas">Nomor Surat Tugas</label>
                <input type="text" id="nomor-tugas" name="nomor-tugas" placeholder="Masukkan Nomor Surat Tugas" />
            </div>
            <div class="form-group">
                <label for="pas-foto">Unggah Pas Foto</label>
                <div class="file-input-container">
                    <div class="file-status" id="foto-status">📄 Tidak ada file yang dipilih</div>
                    <button type="button" class="file-btn" onclick="document.getElementById('foto-file').click()">Pilih
                        Dokumen</button>
                    <input type="file" id="foto-file" class="file-input" accept=".jpg,.jpeg,.png"
                        onchange="handleFileSelect('foto')" />
                </div>
                <div class="file-note">Pas Foto Background Merah ukuran 3x4</div>
            </div>
        </div>

        <div class="button-container">
            <button type="button" class="btn btn-secondary" onclick="goBack()">Kembali</button>
            <button type="submit" class="btn btn-primary">Kirim</button>
        </div>
    </form>
</main>
@endsection
<script>
    const uploadedFiles = {
        ktp: null,
        ijazah: null,
        tugas: null,
        sehat: null,
        foto: null,
    };

    function handleFileSelect(type) {
        const fileInput = document.getElementById(`${type}-file`);
        const statusDiv = document.getElementById(`${type}-status`);
        const file = fileInput.files[0];

        if (file) {
            uploadedFiles[type] = file;
            statusDiv.textContent = `✅ ${file.name}`;
            statusDiv.classList.add("has-file");
        }
    }

    document.getElementById("lampiranForm").addEventListener("submit", function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const nomorTugas = formData.get("nomor-tugas");

        // Validate nomor tugas
        if (!nomorTugas || nomorTugas.trim() === "") {
            document.getElementById("nomor-tugas").style.borderColor = "#ef4444";
            alert("Mohon masukkan Nomor Surat Tugas!");
            return;
        }

        // Check  files
        const Files = ["ktp", "ijazah", "tugas", "sehat", "foto"];
        const missingFiles = Files.filter((type) => !uploadedFiles[type]);

        if (missingFiles.length > 0) {
            const fileNames = {
                ktp: "KTP",
                ijazah: "Ijazah Terakhir",
                tugas: "Surat Tugas",
                sehat: "Surat Sehat",
                foto: "Pas Foto",
            };
            const missingFileNames = missingFiles.map((type) => fileNames[type]).join(", ");
            alert(`Mohon unggah dokumen berikut: ${missingFileNames}`);
            return;
        }

        // Reset border color
        document.getElementById("nomor-tugas").style.borderColor = "#e5e7eb";

        // Simulate successful submission
        alert(
            "Pendaftaran berhasil dikirim! Terima kasih telah mendaftar pelatihan di UPT PTKK Jawa Timur.Harap cek email yang terdaftar."
            );

        // Optional: redirect to success page or reset form
        // window.location.href = 'success.html';
    });

    function goBack() {
        window.location.href = "biodata-sekolah.html";
    }

    // Reset border color on input
    document.getElementById("nomor-tugas").addEventListener("input", function() {
        this.style.borderColor = "#e5e7eb";
    });

    // File validation
    function validateFile(file, allowedTypes, maxSize = 5 * 1024 * 1024) {
        // 5MB default
        if (!allowedTypes.includes(file.type)) {
            alert("Format file tidak didukung. Gunakan format PDF, JPG, JPEG, atau PNG.");
            return false;
        }

        if (file.size > maxSize) {
            alert("Ukuran file terlalu besar. Maksimal 5MB.");
            return false;
        }

        return true;
    }

    // Update file inputs to include validation
    document.querySelectorAll(".file-input").forEach((input) => {
        input.addEventListener("change", function() {
            const file = this.files[0];
            if (file) {
                const allowedTypes = ["application/pdf", "image/jpeg", "image/jpg", "image/png"];
                if (validateFile(file, allowedTypes)) {
                    // File is valid, handleFileSelect will be called by onchange attribute
                } else {
                    // Clear the input if file is invalid
                    this.value = "";
                }
            }
        });
    });
</script>
