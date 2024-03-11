<?php
use Stichoza\GoogleTranslate\GoogleTranslate;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
use Illuminate\Support\Facades\Cookie;
use \App\Http\Middleware\LocalizationMiddleware;
use App\Models\Translator;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::group(['middleware' => LocalizationMiddleware::class], function () {
    Route::get('/', [Controllers\VoteController::class, 'index'])->name('vote.index');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');
    //profile
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
    //vote
    Route::get('/vote', [Controllers\VoteController::class, 'index'])->name('vote.index');
    Route::get('/vote/{questionId}/results', [Controllers\VoteController::class, 'results'])->name('vote.results');
    Route::get('/search', [Controllers\VoteController::class, 'search'])->name('search.questions');
    Route::get('/translate/{text}', function (string $text) {
        echo "log";
        $translator = new GoogleTranslate();
        $translator->setSource('en'); 
        $translator->setTarget(App::getLocale()); 
        $translatedText = $translator->translate($text);
        echo $translatedText;
        return $translatedText;
    })->name('translate');
Route::get('/lang/{locale}', function (string $locale) {
    if (!in_array($locale, array_keys(config('languages')))) {
        abort(400);
    }
    
    Cookie::queue('locale', $locale, 60 * 24 * 365);

    return redirect()->back();
})->name('lang.switch');


    Route::middleware('auth')->group(function () {
        Route::get('/questions', [Controllers\QuestionController::class, 'index'])->name('questions.index');
       Route::post('/questions/lock', [Controllers\QuestionController::class, 'lock'])->name('questions.lock');
        Route::post('/questions/unlock', [Controllers\QuestionController::class, 'unlock'])->name('questions.unlock');
        Route::delete('/questions/delete', [Controllers\QuestionController::class, 'destroy'])->name('questions.delete');
        //create
        Route::get('/questions/create', [\App\Http\Controllers\QuestionController::class, 'create'])->name('questions.create');
        Route::post('/questions/store', [Controllers\QuestionController::class, 'store'])->name('questions.store');

        //edit
        Route::get('/questions/{questionId}/edit', [Controllers\QuestionController::class, 'edit'])->name('questions.edit');

        Route::post('/question/update', [\App\Http\Controllers\QuestionController::class, 'update'])->name('questions.update');
        //vote
        Route::get('/vote/{questionId}/vote', [Controllers\VoteController::class, 'show'])->name('vote.vote');
        Route::post('vote/submit', [Controllers\VoteController::class, 'store'])->name('vote.submit');

    });
});
require __DIR__ . '/auth.php';
