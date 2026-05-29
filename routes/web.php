<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $cursos = \App\Models\Curso::with('palestrantes')->where('ativo', true)->orderBy('ordem')->orderBy('data_inicio')->get();
    $documentos = \App\Models\Documento::where('ativo', true)
        ->where(function ($q) { $q->whereNull('data_vencimento')->orWhere('data_vencimento', '>=', now()->toDateString()); })
        ->orderBy('ordem')->orderBy('created_at', 'desc')->get();
    $configs = \App\Models\SiteConfig::all()->pluck('valor', 'chave')->toArray();
    return view('welcome', compact('cursos', 'documentos', 'configs'));
});

Route::get('/run-seeder-iug2026', function () {
    if (request('token') !== 'IUG2k7mP9seed') abort(403);
    Artisan::call('db:seed', ['--class' => 'SiteDataSeeder', '--force' => true]);
    return 'Seeder executado: ' . Artisan::output();
});

Route::get('/cursos', [\App\Http\Controllers\CursoController::class, 'index'])->name('cursos.index');
Route::get('/cursos/{id}', [\App\Http\Controllers\CursoController::class, 'show'])->name('cursos.show');
Route::get('/documentos', [\App\Http\Controllers\DocumentoController::class, 'index'])->name('documentos.index');
Route::get('/download/documento/{id}', [\App\Http\Controllers\DocumentoController::class, 'download'])->name('download.documento');
Route::get('/contato', function () { return view('contato'); })->name('contato.form');
Route::post('/contato', [\App\Http\Controllers\MensagemController::class, 'store'])->name('contato.store');

Route::prefix('admin')->group(function () {
    Route::get('/', function () {
        return auth()->check() ? redirect()->route('admin.dashboard') : redirect()->route('login');
    });
    Route::get('/login', [\App\Http\Controllers\Auth\AuthController::class, 'showLogin'])->middleware('guest')->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\AuthController::class, 'login'])->middleware('guest', 'throttle:login');
    Route::post('/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'active'])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');

        Route::get('/cursos', [\App\Http\Controllers\CursoController::class, 'adminIndex'])->name('admin.cursos.index');
        Route::get('/cursos/create', [\App\Http\Controllers\CursoController::class, 'adminCreate'])->name('admin.cursos.create');
        Route::post('/cursos', [\App\Http\Controllers\CursoController::class, 'adminStore'])->name('admin.cursos.store');
        Route::get('/cursos/{id}/edit', [\App\Http\Controllers\CursoController::class, 'adminEdit'])->name('admin.cursos.edit');
        Route::put('/cursos/{id}', [\App\Http\Controllers\CursoController::class, 'adminUpdate'])->name('admin.cursos.update');
        Route::delete('/cursos/{id}', [\App\Http\Controllers\CursoController::class, 'adminDestroy'])->name('admin.cursos.destroy');

        Route::get('/documentos', [\App\Http\Controllers\DocumentoController::class, 'adminIndex'])->name('admin.documentos.index');
        Route::get('/documentos/create', [\App\Http\Controllers\DocumentoController::class, 'adminCreate'])->name('admin.documentos.create');
        Route::post('/documentos', [\App\Http\Controllers\DocumentoController::class, 'adminStore'])->name('admin.documentos.store');
        Route::get('/documentos/{id}/edit', [\App\Http\Controllers\DocumentoController::class, 'adminEdit'])->name('admin.documentos.edit');
        Route::put('/documentos/{id}', [\App\Http\Controllers\DocumentoController::class, 'adminUpdate'])->name('admin.documentos.update');
        Route::delete('/documentos/{id}', [\App\Http\Controllers\DocumentoController::class, 'adminDestroy'])->name('admin.documentos.destroy');

        Route::get('/mensagens', [\App\Http\Controllers\MensagemController::class, 'adminIndex'])->name('admin.mensagens.index');
        Route::get('/mensagens/{id}', [\App\Http\Controllers\MensagemController::class, 'adminShow'])->name('admin.mensagens.show');
        Route::delete('/mensagens/{id}', [\App\Http\Controllers\MensagemController::class, 'adminDestroy'])->name('admin.mensagens.destroy');

        Route::get('/palestrantes', [\App\Http\Controllers\PalestranteController::class, 'adminIndex'])->name('admin.palestrantes.index');
        Route::get('/palestrantes/create', [\App\Http\Controllers\PalestranteController::class, 'adminCreate'])->name('admin.palestrantes.create');
        Route::post('/palestrantes', [\App\Http\Controllers\PalestranteController::class, 'adminStore'])->name('admin.palestrantes.store');
        Route::get('/palestrantes/{id}/edit', [\App\Http\Controllers\PalestranteController::class, 'adminEdit'])->name('admin.palestrantes.edit');
        Route::put('/palestrantes/{id}', [\App\Http\Controllers\PalestranteController::class, 'adminUpdate'])->name('admin.palestrantes.update');
        Route::delete('/palestrantes/{id}', [\App\Http\Controllers\PalestranteController::class, 'adminDestroy'])->name('admin.palestrantes.destroy');

        Route::get('/config', [\App\Http\Controllers\ConfigController::class, 'index'])->name('admin.config.index');
        Route::put('/config', [\App\Http\Controllers\ConfigController::class, 'update'])->name('admin.config.update');
    });
});
