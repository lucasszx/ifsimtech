<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ExamController,
    AttemptController,
    ResultController,
    ProfileController,
    QuestionController
};

// redireciona a raiz para o dashboard (evita conflito com 'welcome')
Route::redirect('/', '/dashboard');

Route::middleware(['auth'])->group(function () {
    // dashboard autenticado (sem 'verified' pra simplificar; adicione se usar verificação de e-mail)
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    // criar simulado
    Route::get('/exams/new', [ExamController::class, 'create'])->name('exams.create');
    Route::post('/exams', [ExamController::class, 'store'])->name('exams.store');

    // responder simulado
    Route::get('/attempts/{attempt}', [AttemptController::class, 'play'])->name('attempts.play');
    Route::post('/attempts/{attempt}/answer', [AttemptController::class, 'answer'])->name('attempts.answer');
    Route::post('/attempts/{attempt}/submit', [AttemptController::class, 'submit'])->name('attempts.submit');

    Route::post('/attempts/{attempt}/save',   [AttemptController::class, 'save'])->name('attempts.save');
    Route::post('/attempts/{attempt}/submit', [AttemptController::class, 'submit'])->name('attempts.submit');

    // resultados
    Route::get('/results/{attempt}', [ResultController::class, 'show'])->name('results.show');
    Route::get('/history', [ResultController::class, 'history'])->name('results.history');
    Route::delete('/history/{attempt}', [ResultController::class, 'destroy'])
    ->name('results.destroy');

    // rotas de perfil (satisfazem o link route('profile.edit') do Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // página simples de sugestões (pode evoluir depois)
    Route::view('/suggestions', 'suggestions.index')->name('suggestions.index');

    Route::middleware(['auth', 'can:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/questions', function () {
            $questions = \App\Models\Question::with('options','subject','topics')->latest()->paginate(12);
            return view('admin.questions.index', compact('questions'));
        })->name('questions.index');

        Route::get('/questions/create', [QuestionController::class, 'create'])->name('questions.create');
        Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');
        Route::get('/questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
        Route::put('/questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
        Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');
    });

    Route::get('/results/topic/{topic}', [ResultController::class, 'topicDetails'])
        ->name('results.topic')
        ->middleware('auth');

    Route::get('/results/{attempt}/topic/{topic}/errors',
        [ResultController::class, 'topicErrorsInAttempt']
    )->name('results.topic.errors');

});

// carrega rotas de autenticação (login/register/logout) do Breeze
require __DIR__.'/auth.php';
