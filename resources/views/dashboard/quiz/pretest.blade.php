@extends('dashboard.layouts.main')

@section('title', 'Pre-Test')
@section('page-title', 'Pre-Test')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md">
    <h2 class="font-semibold text-xl mb-4">Pre-Test</h2>

    @if(isset($answers)) 
        {{-- Bagian hasil pre test --}}
        <div class="mb-6">
            <h3 class="text-lg font-semibold">Hasil Pre Test</h3>
            <p class="text-gray-700">Skor kamu: {{ $score }} dari {{ $answers->count() }}</p>
        </div>

        <ul class="list-disc pl-5 space-y-4">
            @foreach($answers as $ans)
                <li>
                    <strong>{{ $ans->question->question }}</strong><br>
                    Jawaban kamu: {{ $ans->answer }} <br>
                    Status:
                    @if($ans->is_correct)
                        ✅ Benar
                    @else
                        ❌ Salah (jawaban benar: {{ $ans->question->correct_answer }} )
                    @endif
                </li>
            @endforeach
        </ul>

    @else
        {{-- Bagian soal --}}
        <p class="text-gray-600 mb-4">Jawab semua soal sebelum waktu habis.</p>

        <!-- Timer -->
        <div id="timer" class="text-red-600 font-bold text-lg mb-4"></div>

        <form id="quizForm" method="POST" action="{{ route('dashboard.pretest.submit') }}">
            @csrf

            <div id="questionContainer">
                @foreach($questions as $index => $q)
                <div class="question hidden" data-index="{{ $index }}">
                    <p class="font-semibold mb-2">{{ $index+1 }}. {{ $q->question }}</p>
                    <div>
                        <label><input type="radio" name="answers[{{ $q->id }}]" value="A"> {{ $q->option_a }}</label><br>
                        <label><input type="radio" name="answers[{{ $q->id }}]" value="B"> {{ $q->option_b }}</label><br>
                        <label><input type="radio" name="answers[{{ $q->id }}]" value="C"> {{ $q->option_c }}</label><br>
                        <label><input type="radio" name="answers[{{ $q->id }}]" value="D"> {{ $q->option_d }}</label><br>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Navigation Buttons -->
            <div class="mt-4 flex justify-between">
                <button type="button" id="prevBtn" class="px-4 py-2 bg-gray-400 text-white rounded-lg">Previous</button>
                <button type="button" id="nextBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Next</button>
                <button type="submit" id="submitBtn" class="px-4 py-2 bg-green-600 text-white rounded-lg hidden">Submit</button>
            </div>
        </form>

        <script>
            let currentIndex = 0;
            const questions = document.querySelectorAll('.question');
            const totalQuestions = questions.length;

            function showQuestion(index) {
                questions.forEach((q, i) => {
                    q.classList.add('hidden');
                    if (i === index) q.classList.remove('hidden');
                });

                document.getElementById('prevBtn').style.display = (index === 0) ? 'none' : 'inline-block';
                document.getElementById('nextBtn').style.display = (index === totalQuestions - 1) ? 'none' : 'inline-block';
                document.getElementById('submitBtn').classList.toggle('hidden', index !== totalQuestions - 1);
            }

            document.getElementById('prevBtn').addEventListener('click', () => {
                if (currentIndex > 0) {
                    currentIndex--;
                    showQuestion(currentIndex);
                }
            });

            document.getElementById('nextBtn').addEventListener('click', () => {
                const currentQuestion = questions[currentIndex];
                const checked = currentQuestion.querySelector('input[type="radio"]:checked');

                if (!checked) {
                    alert("Pilih jawaban dulu sebelum lanjut!");
                    return;
                }

                if (currentIndex < totalQuestions - 1) {
                    currentIndex++;
                    showQuestion(currentIndex);
                }
            });

            // Timer 15 menit
            let timeLeft = 15 * 60;
            const timer = document.getElementById('timer');
            const countdown = setInterval(() => {
                let minutes = Math.floor(timeLeft / 60);
                let seconds = timeLeft % 60;
                timer.textContent = `Sisa waktu: ${minutes}:${seconds < 10 ? '0'+seconds : seconds}`;
                if (timeLeft <= 0) {
                    clearInterval(countdown);
                    document.getElementById('quizForm').submit();
                }
                timeLeft--;
            }, 1000);

            // Mulai dengan soal pertama
            showQuestion(currentIndex);
        </script>
    @endif
</div>
@endsection
