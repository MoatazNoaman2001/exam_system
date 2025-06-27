<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\User;
use App\Models\IntroAnswer;
use Illuminate\Support\Str;
use App\Models\IntroQuestion;
use App\Models\IntroSelection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class IntroTableSeeder extends Seeder
{
    public function run(): void
    {

        $questions = [
            [
                'id' => Str::uuid(),
                'text' => 'What is your experience level with project management?',
                'selections' => [
                    ['text' => 'Beginner', 'has_extra_text' => false],
                    ['text' => 'Intermediate', 'has_extra_text' => false],
                    ['text' => 'Advanced', 'has_extra_text' => true],
                ],
            ],
            [
                'id' => Str::uuid(),
                'text' => 'Which area of project management are you most interested in?',
                'selections' => [
                    ['text' => 'Risk Management', 'has_extra_text' => false],
                    ['text' => 'Time Management', 'has_extra_text' => false],
                    ['text' => 'Other', 'has_extra_text' => true],
                ],
            ],
        ];

        foreach ($questions as $questionData) {
            $selections = $questionData['selections'];
            unset($questionData['selections']);

            $question = IntroQuestion::create($questionData);

            // Create Intro Selections for each question
            $createdSelections = [];
            foreach ($selections as $selectionData) {
                $selection = IntroSelection::create([
                    'text' => $selectionData['text'],
                    'has_extra_text' => $selectionData['has_extra_text'],
                    'question_id' => $question->id,
                ]);
                $createdSelections[] = $selection;
            }
        }
    }
}
