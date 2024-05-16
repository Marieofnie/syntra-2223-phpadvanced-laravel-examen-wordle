<?php

namespace App\Http\Controllers;

use App\Models\Validword;
use App\Models\Word;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function check(Request $request)
    {
        // Check if word is present in the call

        $word = strtolower($request->input('word'));

        if (!$word) {
            return response()->json([
                'status' => 'error',
                'code' => 1,
                'message' => 'Word is required'
            ], 422);
        }

        // Solution with MVC: we created a modal, migration and seeder for valid words
        $today = date('Y-m-d');

        $wordoftoday = Word::whereDate('scheduled_at', $today)->first();

        if (!$wordoftoday) {
            $randomword = Validword::inRandomOrder()->first();
            $word = new Word(['word' => $randomword->word, 'scheduled_at' => $today]);
            $word->save();
            $wordoftoday = $randomword;
        }

        $wordoftoday = $wordoftoday->word;
        if ($wordoftoday == $word) {
            return response()->json([
                'status' => 'success',
                'code' => 4,
                'message' => 'Word is correct'
            ], 200);
        }
        ;
    }
}