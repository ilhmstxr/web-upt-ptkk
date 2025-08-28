{{--
    File ini adalah partial untuk menampilkan satu kartu pertanyaan.
    Perubahan utama adalah mengganti '$question->opsiJawabans'
    menjadi '$question->opsiJawabanFinal' agar dapat mengambil
    opsi jawaban dari template jika ada.
--}}
<div class="mb-8 @error('answers.' . $question->id) border border-red-500 rounded-lg p-3 @enderror"
    id="q-{{ $question->id }}">

    <p class="mb-4 font-medium text-gray-800 leading-relaxed">{{ $question->nomor }}. {{ $question->teks_pertanyaan }}
    </p>

    {{-- KONDISI 1: Tampilan khusus untuk SKALA LIKERT dengan EMOJI --}}
    @if ($question->tipe_jawaban === 'skala_likert')
        @php
            // REVISI: Definisikan "peta emoji" menggunakan teks_opsi sebagai kunci (key).
            // Ini membuat pemetaan menjadi dinamis dan tidak bergantung pada kolom 'nilai'.
            $emojiMap = [
                // Skala Kepuasan
                'Sangat Tidak Memuaskan' => '😠',
                'Tidak Memuaskan' => '😟',
                'Kurang Memuaskan' => '😐',
                'Memuaskan' => '😊',
                'Sangat Memuaskan' => '🤩',

                // Skala Manfaat & Kebutuhan & Dukungan
                'Tidak Bermanfaat' => '👎',
                'Kurang Bermanfaat' => '🤔',
                'Bermanfaat' => '👍',
                'Sangat Bermanfaat' => '🎉',
                'Tidak Perlu' => '👎',
                'Kurang Perlu' => '🤔',
                'Perlu' => '👍',
                'Sangat Perlu' => '🎉',
                'Tidak Mendukung' => '👎',
                'Kurang Mendukung' => '🤔',
                'Mendukung' => '👍',
                'Sangat Mendukung' => '🎉',

                // Skala Lainnya (Disiplin, Rapi, Baik)
                'Tidak Disiplin' => '😟',
                'Kurang Disiplin' => '😐',
                'Disiplin' => '😊',
                'Sangat Disiplin' => '🤩',
                'Tidak rapi' => '😟',
                'Kurang Rapi' => '😐',
                'Rapi' => '😊',
                'Sangat Rapi' => '🤩',
                'Tidak baik' => '😟',
                'Kurang baik' => '😐',
                'baik' => '😊',
                'Sangat baik' => '🤩',
            ];
        @endphp
        <div class="rating-options-container grid grid-cols-2 sm:grid-cols-4 gap-2">
            {{-- Loop tetap menggunakan accessor 'opsiJawabanFinal' --}}
            @foreach ($question->opsiJawabanFinal->sortBy('nilai') as $opsi)
                <label
                    class="rating-option flex flex-col items-center p-3 border-2 bg-gray-50 border-gray-200 rounded-lg cursor-pointer transition-all duration-200 hover:border-indigo-300 has-[:checked]:bg-indigo-100 has-[:checked]:border-indigo-400">
                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $opsi->nilai ?? $opsi->id }}"
                        class="hidden" @if (old('answers.' . $question->id) == ($opsi->nilai ?? $opsi->id)) checked @endif>

                    {{-- REVISI: Emoji diambil dari $emojiMap menggunakan teks_opsi dari database sebagai kunci --}}
                    <span class="text-3xl mb-1">{{ $emojiMap[$opsi->teks_opsi] ?? '🤔' }}</span>

                    <span class="text-xs text-gray-600 mt-1 text-center font-semibold">{{ $opsi->teks_opsi }}</span>
                </label>
            @endforeach
        </div>

        {{-- KONDISI 2: Untuk tipe jawaban Pilihan Ganda (tanpa emoji) --}}
    @elseif ($question->tipe_jawaban === 'pilihan_ganda')
        <div class="space-y-3">
            {{-- Menggunakan accessor 'opsiJawabanFinal' --}}
            @foreach ($question->opsiJawabanFinal as $opsi)
                <label
                    class="flex items-center p-3 border-2 bg-gray-50 border-gray-200 rounded-lg cursor-pointer transition-all duration-200 hover:border-indigo-300 has-[:checked]:bg-indigo-100 has-[:checked]:border-indigo-400">
                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $opsi->id }}"
                        class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                        @if (old('answers.' . $question->id) == $opsi->id) checked @endif>
                    <span class="ml-3 text-sm font-medium text-gray-700">{{ $opsi->teks_opsi }}</span>
                </label>
            @endforeach
        </div>

        {{-- KONDISI 3: Jika tipe jawaban adalah TEKS BEBAS --}}
    @elseif ($question->tipe_jawaban === 'teks_bebas')
        <textarea name="answers[{{ $question->id }}]" rows="4"
            class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400"
            placeholder="Tuliskan jawaban Anda di sini...">{{ old('answers.' . $question->id) }}</textarea>

    @endif

    @error('answers.' . $question->id)
        <p class="text-red-600 text-sm mt-2">Kolom ini wajib diisi.</p>
    @enderror
</div>
