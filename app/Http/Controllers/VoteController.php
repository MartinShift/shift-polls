<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Models\Question;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; //Illuminati
use App\Models\Option;
use App\Services\TranslatorService;
class VoteController extends Controller
{
    private TranslatorService $translatorService;

    public function __construct(TranslatorService $translatorService)
    {
        $this->translatorService = $translatorService;
    }
    public function index()
    {
        $questions = Question::with('image')
        ->get()
        ->map(function ($question)  {
            // Define the full image source URL
            $question['image_src'] = asset('storage/app/public/images/' . $question->image->filename);
    
            // Translate title and description
            $question['title'] = $this->translatorService->translate($question->title, app()->getLocale());
            $question['description'] = $this->translatorService->translate($question->description, app()->getLocale());
    
            // Translate options (if 'options' is a relation on the Question model)
            $question->options->transform(function ($option) {
                $option['value'] = $this->translatorService->translate($option->value, app()->getLocale());
                return $option;
            });
    
            return $question;
        });

    return view('vote.index',  [
        'questions' => $questions,
        't' => $this->translatorService
    ]);
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'question_id' => 'required|integer|',
            'option' => 'required|string|',
        ]);

        $question = Question::findOrFail($validatedData['question_id']);
        $selectedOption = $question->options()->where('value', $validatedData['option'])->first();

        if ($selectedOption) {
            $user = Auth::user();
            $vote = new Vote([
                'option_id' => $selectedOption->id,
                'question_id' => $question->id,
                'user_id' => $user->id,
            ]);
            $vote->save();

            return response()->json('Vote added successfully');
        } else {
            // If the option is not found, return an error response
            return response()->json('Option not found for the provided question');
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($questionId)
    {
        $question = Question::with('options', 'votes')
        ->with('image')  
        ->where('id', $questionId)
        ->get()
        ->map(function ($question) {
            $question['image_src'] = asset('storage/app/public/images/' . $question->image->filename);
            $question['title'] = $this->translatorService->translate($question->title, app()->getLocale());
            $question['description'] = $this->translatorService->translate($question->description, app()->getLocale());
    
            // Translate options (if 'options' is a relation on the Question model)
            $question->options->transform(function ($option) {
                $option['value'] = $this->translatorService->translate($option->value, app()->getLocale());
                return $option;
            });

            return $question;
        })[0] ?? [];
        $user = Auth::user();
        
        $userHasVoted = $question->votes()->where('user_id', $user->id)->exists();
        return view('vote.vote', compact('question','userHasVoted'));
    }

    public function results($questionId)
    {
$question = Question::with(['options' => function ($query) {
        $query->withCount('votes');  }, 'image'])
        ->withCount('votes')
    ->get()
    ->where('id', $questionId)
    ->map(function ($question) {
        $question['image_src'] = asset('storage/app/public/images/' . $question->image->filename);
        $question['title'] = $this->translatorService->translate($question->title, app()->getLocale());
        $question['description'] = $this->translatorService->translate($question->description, app()->getLocale());

        // Translate options (if 'options' is a relation on the Question model)
        $question->options->transform(function ($option) {
            $option['value'] = $this->translatorService->translate($option->value, app()->getLocale());
            return $option;
        });

        return $question;
    })[0];

    return view('vote.results', compact('question'));
    }
    public function search(Request $request)
    {
        $query = $request->input('query');
        $questions = Question::with('image')
        ->get()
        ->map(function ($question) {
            $question['image_src'] = asset('storage/app/public/images/' . $question->image->filename);

            // Translate title and description
            $question['title'] = $this->translatorService->translate($question->title, app()->getLocale());
            $question['description'] = $this->translatorService->translate($question->description, app()->getLocale());

            // Translate options (if 'options' is a relation on the Question model)
            $question->options->transform(function ($option) {
                $option['value'] = $this->translatorService->translate($option->value, app()->getLocale());
                return $option;
            });

            return $question;
        });

    // Apply where condition to filtered data based on translated titles and descriptions
    $questions = $questions->filter(function ($question) use ($query) {
        return stripos($question['title'], $query ?? "") !== false || stripos($question['description'], $query ?? "") !== false;
    })->take(9);

        return response()->json($questions);
    }
}
