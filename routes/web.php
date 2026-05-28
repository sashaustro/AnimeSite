<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnimeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\EpisodeController;

// 1. ПУБЛІЧНІ (Без авторизації)
Route::get('/', [AnimeController::class, 'index'])->name('home');
Route::get('/genres', [AnimeController::class, 'genres'])->name('anime.genres');
Route::get('/ongoing', [AnimeController::class, 'ongoing'])->name('anime.ongoing');
Route::get('/top', [AnimeController::class, 'top'])->name('anime.top');
Route::get('/anime/{anime}', [AnimeController::class, 'show'])->name('anime.show');

// 2. АВТОРИЗОВАНІ (Кабінет)
Route::middleware(['auth'])->group(function () {
    Route::get('/cabinet', [ProfileController::class, 'dashboard'])->name('cabinet');
    Route::get('/cabinet/lists', [ProfileController::class, 'lists'])->name('cabinet.lists');
    Route::get('/cabinet/collections', [ProfileController::class, 'collections'])->name('cabinet.collections');
    Route::put('/cabinet/profile', [ProfileController::class, 'updateProfile'])->name('cabinet.profile.update');
    Route::post('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::post('/anime/{anime}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Списки та Колекції (Аякс)
    Route::post('/anime/{anime}/list-status', [\App\Http\Controllers\UserListController::class, 'updateStatus'])->name('user.list.status');
    Route::post('/anime/{anime}/list-favorite', [\App\Http\Controllers\UserListController::class, 'toggleFavorite'])->name('user.list.favorite');

    // Колекції (CRUD)
    Route::post('/collections', [\App\Http\Controllers\CollectionController::class, 'store'])->name('collections.store');
    Route::put('/collections/{collection}', [\App\Http\Controllers\CollectionController::class, 'update'])->name('collections.update');
    Route::delete('/collections/{collection}', [\App\Http\Controllers\CollectionController::class, 'destroy'])->name('collections.destroy');
    Route::post('/collections/{collection}/anime/{anime}', [\App\Http\Controllers\CollectionController::class, 'toggleAnime'])->name('collections.toggle_anime');
    // Ratings and Comments
    Route::post('/anime/{anime}/ratings', [\App\Http\Controllers\RatingController::class, 'store'])->name('ratings.store');
    Route::delete('/anime/{anime}/ratings', [\App\Http\Controllers\RatingController::class, 'destroy'])->name('ratings.destroy');
    Route::post('/anime/{anime}/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [\App\Http\Controllers\CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [\App\Http\Controllers\CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{comment}/vote', [\App\Http\Controllers\CommentController::class, 'vote'])->name('comments.vote');
    Route::post('/reports', [\App\Http\Controllers\ReportController::class, 'store'])->name('reports.store');
});

// Публічний профіль
Route::get('/user/{id}', [\App\Http\Controllers\PublicProfileController::class, 'show'])->name('profile.public');

// 3. АДМІН (Захищено auth + admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/anime', [AnimeController::class, 'adminIndex'])->name('anime.admin_index');
    Route::get('/anime/create', [AnimeController::class, 'create'])->name('anime.create');
    Route::post('/anime', [AnimeController::class, 'store'])->name('anime.store');
    Route::get('/anime/{anime}/edit', [AnimeController::class, 'edit'])->name('anime.edit');
    Route::put('/anime/{anime}', [AnimeController::class, 'update'])->name('anime.update');
    Route::delete('/anime/{anime}', [AnimeController::class, 'destroy'])->name('anime.destroy');
    // Користувачі
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::post('/users/{user}/toggle-role', [\App\Http\Controllers\Admin\UserController::class, 'toggleRole'])->name('admin.users.toggle_role');
    Route::post('/users/{user}/mute', [\App\Http\Controllers\Admin\ReportController::class, 'mute'])->name('admin.users.mute');
    Route::post('/users/{user}/unmute', [\App\Http\Controllers\Admin\ReportController::class, 'unmute'])->name('admin.users.unmute');

    // Скарги
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.reports.index');
    Route::post('/reports/{report}/resolve', [\App\Http\Controllers\Admin\ReportController::class, 'resolve'])->name('admin.reports.resolve');
    Route::delete('/reports/{report}', [\App\Http\Controllers\Admin\ReportController::class, 'destroy'])->name('admin.reports.destroy');

    // Жанри
    Route::resource('genres', \App\Http\Controllers\Admin\GenreController::class)->except(['show'])->names([
        'index' => 'admin.genres.index',
        'create' => 'admin.genres.create',
        'store' => 'admin.genres.store',
        'edit' => 'admin.genres.edit',
        'update' => 'admin.genres.update',
        'destroy' => 'admin.genres.destroy',
    ]);

    // Episodes
    Route::get('/anime/{anime}/episodes/create', [EpisodeController::class, 'create'])->name('episodes.create');
    Route::post('/anime/{anime}/episodes', [EpisodeController::class, 'store'])->name('episodes.store');
    Route::get('/episodes/{episode}/edit', [EpisodeController::class, 'edit'])->name('episodes.edit');
    Route::put('/episodes/{episode}', [EpisodeController::class, 'update'])->name('episodes.update');
    Route::delete('/episodes/{episode}', [EpisodeController::class, 'destroy'])->name('episodes.destroy');

    Route::post('/episodes/upload-chunk', [EpisodeController::class, 'uploadChunk'])->name('episodes.upload_chunk');
    Route::get('/episodes/upload-chunk', [EpisodeController::class, 'uploadChunk']);
});

// Відображення локальних відеофайлів (обхід проблеми з symlink на Windows)
Route::get('/stream/episodes/{filename}', function ($filename) {
    $path = storage_path('app/public/episodes/' . $filename);
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->file($path);
})->name('episodes.video');

require __DIR__.'/auth.php';
