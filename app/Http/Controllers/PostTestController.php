<?php

namespace App\Http\Controllers;

use App\Models\PostTest;
use App\Models\PostTestAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostTestController extends Controller
{
    public function start()
    {
        $questions = PostTest::all(); // ambil semua soal
        return view('dashboard.posttest.quiz', compact('questions'));
    }

    public function submit(Request $request)
    {
        $answers = $request->input('answers', []);

        foreach ($answers as $postTestId => $answer) {
            $question = PostTest::find($postTestId);

            if (!$question) continue;

            $isCorrect = $answer === $question->correct_answer;

            PostTestAnswer::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'post_test_id' => $question->id,
                ],
                [
                    'answer' => $answer,
                    'is_correct' => $isCorrect,
                ]
            );
        }

        return redirect()->route('dashboard.posttest.result')->with('success', 'Post-Test selesai!');
    }

    public function result()
    {
        $answers = PostTestAnswer::where('user_id', Auth::id())->with('question')->get();
        $score = $answers->where('is_correct', true)->count();

        // arahkan ke blade utama (dashboard.posttest.index)
        return view('dashboard.posttest.index', compact('answers', 'score'));
    }
}
