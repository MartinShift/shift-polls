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
class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private TranslatorService $translatorService;

    public function __construct(TranslatorService $translatorService)
    {
        $this->translatorService = $translatorService;
    }
    public function index()
    {
        $questions = Question::withCount('options', 'votes')
        ->with('image')  // Assuming your image relation is named 'image'
        ->get()
        ->where('user_id', Auth::user()->id)
        ->map(function ($question) {
            $question['image_src'] = asset('storage/app/public/images/' . $question->image->filename);
            
            return $question;
        });

    return view('question.index', compact('questions'));
    }

    public function create()
    {
        return view('question.create');
    }

    public function store(Request $request)
    {
        // Retrieve and validate the form data
        $validatedData = $request->validate([
            'title' => 'required|string|max:500',
            'description' => 'required|string|max:2000',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date',
            'image' => 'image|nullable|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);
    
        // Handle image upload
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
    
            // Store the image file
            $filename = time() . '_' . $imageFile->getClientOriginalName();
            Storage::putFileAs('public/images', $imageFile, $filename);

            $image = new \App\Models\Image();
            $image->filename = $filename;
            $image->save();
        }
        $question = new Question();
        $question->title = $validatedData['title'];
        $question->description = $validatedData['description'];
        $question->start_at = $validatedData['start_at'];
        $question->end_at = $validatedData['end_at'] ?? null; // If end_at is provided
        $question->image_id = $image->id ?? null; // Assign the image ID if it exists
        $user = Auth::user();
        $question->user_id = $user->id;
        // Save the question to the database
        $question->save();
    
        // Handle and save options associated with the question
            foreach ($request->input('options') as $optionValue) {
                $option = new Option();
                $option->question_id = $question->id; // Associate the option with the question
                $option->value = $optionValue;
                $option->save();
            }
    
        return redirect()->route('questions.index');
    }
    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        //
    }
    public function lock(Request $request)
    {
    //   echo "done";
       $questionId = $request->input('question_id');
    //         // Find the question by ID
            $question = Question::findOrFail($questionId);
           $question->active =  false;
           $question->save();
    }
    
    public function unlock(Request $request)
    {
    //   echo "done";
       $questionId = $request->input('question_id');
    //         // Find the question by ID
            $question = Question::findOrFail($questionId);
           $question->active = true;
           $question->save();
    }
    public function edit( $questionId)
    {
        $question = Question::with('options')->findOrFail($questionId);
        return view('question.edit',  compact('question'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Find the question by ID
     
    
        // Validate the updated data
        $validatedData = $request->validate([
            'title' => 'required|string|max:500',
            'description' => 'required|string|max:2000',
            'start_at' => 'required|date',
            'end_at' => 'required|date',
        ]);
       $question = Question::find($request->input('id'));
    
        if (!$question) {
            return response()->json('Question not found');
        }
      
      
            $question->title = $validatedData['title'];
            $question->description = $validatedData['description'];
            $question->start_at = $validatedData['start_at'];
            $question->end_at = $validatedData['end_at'];

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
    
            // Store the image file
            $filename = time() . '_' . $imageFile->getClientOriginalName();
            Storage::putFileAs('public/images', $imageFile, $filename);

            $image = new \App\Models\Image();
            $image->filename = $filename;
            $image->save();
            $question->image_id = $image->id;
        }

        $question->options()->delete(); 
        foreach ($request->input('options') as $optionValue) {
            $option = new Option();
            $option->question_id = $question->id; // Associate the option with the question
            $option->value = $optionValue;
            $option->save();
        }
            $question->save();
        return response()->json('Question updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $questionId = $request->input('question_id');
    
    // Find the question by ID
    $question = Question::find($questionId);

    // Check if the question exists
    if (!$question) {
        return response()->json(['message'=>'Question not found']);
    }

    // Delete the question
    try {
        $question->delete();
        return response()->json(['message'=>'Question deleted successfully']);
    } catch (\Exception $e) {
        return response()->json(['message'=>'there was an error deleting question']);
    }
    }   

}
