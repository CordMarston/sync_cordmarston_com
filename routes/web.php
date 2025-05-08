<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Project\SyncController;
use App\Http\Controllers\Project\ConnectionsController;
use Illuminate\Support\Facades\Auth;

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

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });


Route::get('/', function () {
    return redirect()->route(Auth::check() ? 'dashboard' : 'login');
});


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('projects', ProjectController::class)->middleware(['auth', 'verified']);
Route::resource('projects/connections', ConnectionsController::class)->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/projects/{project}/tables', [ProjectController::class, 'tables'])->name('projects.tables');
    Route::get('/projects/{project}/connections', [ProjectController::class, 'connections'])->name('projects.connections');
    Route::post('/projects/connections/test', [ConnectionsController::class, 'testConnection'])->name('sync.connection.test');
    Route::post('/projects/{project}/sync', [SyncController::class, 'syncSchema'])->name('sync.schema');
});

// Route::get('/{page}', function ($page) {
//     // Split the page by "/"
//     $segments = explode('/', $page);

//     // Capitalize each segment
//     $segments = array_map(fn($segment) => ucfirst($segment), $segments);

//     // Recombine with "/"
//     $pagePath = implode('/', $segments);

//     return Inertia::render($pagePath);
// })->where('page', '.*')->name('pages.show')->middleware(['auth', 'verified']);


require __DIR__.'/auth.php';
