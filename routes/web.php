<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\KioskController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return view('auth.login');
});

Route::group(['middleware' => ['auth', 'verified', 'role:1']], function () {
    Route::resource('/users', AdminController::class);
    Route::resource('/types', DocumentTypeController::class);
    Route::resource('/departments', DepartmentController::class);
    
    Route::get('/quick-remarks', [\App\Http\Controllers\QuickRemarkController::class, 'index'])->name('quick-remarks.index');
    Route::post('/quick-remarks', [\App\Http\Controllers\QuickRemarkController::class, 'store'])->name('quick-remarks.store');
    Route::delete('/quick-remarks/{quickRemark}', [\App\Http\Controllers\QuickRemarkController::class, 'destroy'])->name('quick-remarks.destroy');

    Route::get('/dashboard-data', [DashboardController::class, 'getDashboardData'])->name('dashboard.data');
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::post('process-all', [DocumentController::class, 'processAll'])->name('documents.processAll');
    Route::post('mark-all-ready', [DocumentController::class, 'markAllReady'])->name('documents.markAllReady');
    Route::post('mark-all-claimed', [DocumentController::class, 'markAllClaimed'])->name('documents.markAllClaimed');
    Route::post('/users/{user}/regenerate-barcode', [AdminController::class, 'regenerateBarcode'])->name('users.regenerate-barcode');
    Route::patch('/documents/{id}/mark-ready', [DocumentController::class, 'markReady'])
        ->name('documents.markReady');

    Route::post('/documents/scan-track', [DocumentController::class, 'scanTrack'])
        ->name('documents.scanTrack');

    Route::patch('/documents/{id}/mark-claimed', [DocumentController::class, 'markClaimed'])
        ->name('documents.markClaimed');
    Route::patch('/documents/{id}/override', [DocumentController::class, 'overrideSubmitted'])
        ->name('documents.override');
    Route::get('/documents/{id}/override-guide', [DocumentController::class, 'printOverrideGuide'])
        ->name('documents.overrideGuide');

    Route::get('/users/barcodes/pdf', [AdminController::class, 'downloadAllBarcodes'])
        ->name('users.barcodes.pdf');

    Route::post('/documents/confirm-scan', [DocumentController::class, 'confirmScan'])
        ->name('documents.confirmScan');
        
    Route::get('/documents/export', [DocumentController::class, 'export'])
        ->name('documents.export');
        
    Route::get('/test', [DocumentController::class, 'testing'])->name('test.route');
});

Route::group(['middleware' => ['auth', 'verified']], function () {
}); 

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard-data', [DashboardController::class, 'getDashboardData'])->name('dashboard.data');
    Route::resource('/documents', DocumentController::class);
    Route::get('/records', [DocumentController::class, 'record'])->name('records.index');
    Route::resource('/students', StudentController::class);
    Route::post('process-all', [DocumentController::class, 'processAll'])->name('documents.processAll');
    Route::post('mark-all-ready', [DocumentController::class, 'markAllReady'])->name('documents.markAllReady');
    Route::post('mark-all-claimed', [DocumentController::class, 'markAllClaimed'])->name('documents.markAllClaimed');
    Route::patch('/documents/{id}/mark-ready', [DocumentController::class, 'markReady'])
        ->name('documents.markReady');

    Route::post('/documents/scan-track', [DocumentController::class, 'scanTrack'])
        ->name('documents.scanTrack');

    Route::patch('/documents/{id}/mark-claimed', [DocumentController::class, 'markClaimed'])
        ->name('documents.markClaimed');
    Route::patch('/documents/{id}/override', [DocumentController::class, 'overrideSubmitted'])
        ->name('documents.override');
    Route::get('/documents/{id}/override-guide', [DocumentController::class, 'printOverrideGuide'])
        ->name('documents.overrideGuide');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::prefix('kiosk')->middleware('ip')->group(function () {

    Route::get('/', [KioskController::class, 'index'])->name('kiosk.home');
    Route::get('/submit', [KioskController::class, 'create'])->name('kiosk.submit');
    Route::post('/submit', [KioskController::class, 'store'])->name('kiosk.store');
    Route::post('/verify-id', [KioskController::class, 'verifyID'])->name('kiosk.verifyID');
    Route::get('/carousel', [KioskController::class, 'carousel'])->name('kiosk.carousel');
    
    Route::get('/claim', [KioskController::class, 'claim'])->name('kiosk.claim');
    Route::post('/claim/verify', [KioskController::class, 'verifyClaim'])->name('kiosk.claim.verify');
    Route::post('/claim/confirm', [KioskController::class, 'confirmClaim'])->name('kiosk.claim.confirm');
    
    Route::post('/submit/finalize', [KioskController::class, 'finalizeSubmission'])->name('kiosk.submit.finalize');
    Route::post('/submit/printer-error', [KioskController::class, 'flagPrinterError'])->name('kiosk.submit.printerError');
});

require __DIR__.'/auth.php';
