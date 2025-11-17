<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pertanyaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Load Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .opsi-jawaban-item { border-left: 3px solid #0d6efd; }
        .modal-body { max-height: 70vh; overflow-y: auto; }
        .current-image-container .img-thumbnail { max-width: 200px; }
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Manajemen Pertanyaan</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
                <i class="bi bi-plus-circle"></i> Tambah Pertanyaan
            </button>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Terdapat masalah dengan input Anda.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tes</th>
                            <th>No.</th>
                            <th>Pertanyaan</th>
                            <th>Gambar</th>
                            <th>Tipe Jawaban</th>
                            <th width="150px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pertanyaans as $p)
                        <tr>
                            <td><strong>{{ $p->tes->nama_tes ?? 'N/A' }}</strong></td>
                            <td>{{ $p->nomor }}</td>
                            <td>{!! nl2br(e($p->teks_pertanyaan)) !!}</td>
                            <td>
                                @if($p->gambar)
                                    <img src="{{ asset('storage/' . $p->gambar) }}" alt="Gambar Pertanyaan" width="100" class="img-thumbnail">
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ Str::title(str_replace('_', ' ', $p->tipe_jawaban)) }}</td>
                            <td>
                                <form action="{{ route('pertanyaan.destroy', $p->id) }}" method="POST">
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal-{{ $p->id }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data pertanyaan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- =================================================================== -->
    <!-- |                  MODAL TAMBAH PERTANYAAN                        | -->
    <!-- =================================================================== -->
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('pertanyaan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahModalLabel">Tambah Pertanyaan Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" x-data="{ tipe: '{{ old('tipe_jawaban', 'pilihan_ganda') }}', opsi: {{ json_encode(old('opsiJawabans', [['teks_opsi' => '']])) }} }">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="teks_pertanyaan_tambah" class="form-label">Teks Pertanyaan</label>
                                    <textarea class="form-control" id="teks_pertanyaan_tambah" name="teks_pertanyaan" rows="8" required>{{ old('teks_pertanyaan') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="tes_id_tambah" class="form-label">Tes</label>
                                    <select class="form-select" id="tes_id_tambah" name="tes_id" required>
                                        <option value="">Pilih Tes</option>
                                        @foreach($tests as $tes)
                                            <option value="{{ $tes->id }}" {{ old('tes_id') == $tes->id ? 'selected' : '' }}>{{ $tes->nama_tes }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="nomor_tambah" class="form-label">Nomor Urut</label>
                                    <input type="number" class="form-control" id="nomor_tambah" name="nomor" required value="{{ old('nomor') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tipe Jawaban</label>
                                    <select class="form-select" name="tipe_jawaban" x-model="tipe">
                                        <option value="pilihan_ganda">Pilihan Ganda</option>
                                        <option value="esai">Esai</option>
                                        <option value="skala_likert">Skala Likert</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="gambar_tambah" class="form-label">Gambar Pertanyaan (Opsional)</label>
                            <input class="form-control" type="file" id="gambar_tambah" name="gambar">
                        </div>

                        <div x-show="tipe === 'pilihan_ganda'" style="display: none;">
                            <hr>
                            <h6>Opsi Jawaban (Pilih satu sebagai jawaban benar)</h6>
                            <template x-for="(item, index) in opsi" :key="index">
                                <div class="p-3 mb-2 rounded opsi-jawaban-item bg-light">
                                    <div class="input-group mb-2">
                                        <div class="input-group-text">
                                            <input class="form-check-input mt-0" type="radio" name="opsi_benar" :value="index">
                                        </div>
                                        <input type="text" class="form-control" placeholder="Teks Opsi Jawaban" :name="`opsiJawabans[${index}][teks_opsi]`" x-model="item.teks_opsi" required>
                                        <button type="button" class="btn btn-outline-danger" @click="opsi.splice(index, 1)" x-show="opsi.length > 1"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                    <!-- Input Gambar untuk Opsi -->
                                    <input class="form-control form-control-sm" type="file" :name="`opsiJawabans[${index}][gambar]`">
                                </div>
                            </template>
                            <button type="button" class="btn btn-outline-primary btn-sm mt-2" @click="opsi.push({teks_opsi: '', gambar: null})">
                                <i class="bi bi-plus"></i> Tambah Opsi
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- =================================================================== -->
    <!-- |                   MODAL EDIT PERTANYAAN                         | -->
    <!-- =================================================================== -->
    @foreach ($pertanyaans as $p)
    <div class="modal fade" id="editModal-{{ $p->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $p->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('pertanyaan.update', $p->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel-{{ $p->id }}">Edit Pertanyaan #{{ $p->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" x-data="{ tipe: '{{ old('tipe_jawaban', $p->tipe_jawaban) }}', opsi: {{ json_encode(
                        collect(old('opsiJawabans', $p->opsiJawabans->isEmpty() ? [['id' => null, 'teks_opsi' => '', 'gambar' => null, 'apakah_benar' => false]] : $p->opsiJawabans->toArray()))->map(function($item) {
                            $item['apakah_benar'] = $item['apakah_benar'] ?? false;
                            $item['id'] = $item['id'] ?? null;
                            $item['gambar'] = $item['gambar'] ?? null;
                            return $item;
                        })
                    ) }}, opsiBenar: '{{ old('opsi_benar', $p->opsiJawabans->search(fn($o) => $o->apakah_benar)) }}' }">
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="teks_pertanyaan_edit_{{ $p->id }}" class="form-label">Teks Pertanyaan</label>
                                    <textarea class="form-control" id="teks_pertanyaan_edit_{{ $p->id }}" name="teks_pertanyaan" rows="8" required>{{ old('teks_pertanyaan', $p->teks_pertanyaan) }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="tes_id_edit_{{ $p->id }}" class="form-label">Tes</label>
                                    <select class="form-select" id="tes_id_edit_{{ $p->id }}" name="tes_id" required>
                                        @foreach($tests as $tes)
                                            <option value="{{ $tes->id }}" {{ old('tes_id', $p->tes_id) == $tes->id ? 'selected' : '' }}>{{ $tes->nama_tes }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="nomor_edit_{{ $p->id }}" class="form-label">Nomor Urut</label>
                                    <input type="number" class="form-control" id="nomor_edit_{{ $p->id }}" name="nomor" required value="{{ old('nomor', $p->nomor) }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tipe Jawaban</label>
                                    <select class="form-select" name="tipe_jawaban" x-model="tipe">
                                        <option value="pilihan_ganda">Pilihan Ganda</option>
                                        <option value="esai">Esai</option>
                                        <option value="skala_likert">Skala Likert</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            @if($p->gambar)
                                <label class="form-label">Gambar Saat Ini</label>
                                <div class="current-image-container">
                                    <img src="{{ asset('storage/' . $p->gambar) }}" alt="Gambar Pertanyaan" class="img-thumbnail">
                                </div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="gambar_edit_{{ $p->id }}" class="form-label">Ganti Gambar (Opsional)</label>
                            <input class="form-control" type="file" id="gambar_edit_{{ $p->id }}" name="gambar">
                        </div>

                        <div x-show="tipe === 'pilihan_ganda'" style="display: none;">
                            <hr>
                            <h6>Opsi Jawaban (Pilih satu sebagai jawaban benar)</h6>
                            <template x-for="(item, index) in opsi" :key="index">
                                <div class="p-3 mb-2 rounded opsi-jawaban-item bg-light">
                                    <input type="hidden" :name="`opsiJawabans[${index}][id]`" x-model="item.id">
                                    <div class="input-group mb-2">
                                        <div class="input-group-text">
                                            <input class="form-check-input mt-0" type="radio" name="opsi_benar" :value="index" :checked="opsiBenar == index || item.apakah_benar">
                                        </div>
                                        <input type="text" class="form-control" placeholder="Teks Opsi Jawaban" :name="`opsiJawabans[${index}][teks_opsi]`" x-model="item.teks_opsi" required>
                                        <button type="button" class="btn btn-outline-danger" @click="opsi.splice(index, 1)" x-show="opsi.length > 1"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                    <!-- Input & Preview Gambar untuk Opsi -->
                                    <div class="row align-items-center">
                                        <div class="col-sm-8">
                                            <label class="form-label visually-hidden">Gambar Opsi</label>
                                            <input class="form-control form-control-sm" type="file" :name="`opsiJawabans[${index}][gambar]`">
                                        </div>
                                        <div class="col-sm-4 text-end" x-show="item.gambar">
                                            <img :src="'/storage/' + item.gambar" class="img-thumbnail" style="max-height: 40px;">
                                        </div>
                                    </div>
                                </div>
                            </template>
                             <button type="button" class="btn btn-outline-primary btn-sm mt-2" @click="opsi.push({id: null, teks_opsi: '', apakah_benar: false, gambar: null})">
                                <i class="bi bi-plus"></i> Tambah Opsi
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

