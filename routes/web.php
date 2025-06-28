<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JasaController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AdminTransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Models\Transaction;
use App\Models\Cart;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $jasas = App\Models\Jasa::all();
    $barangs = App\Models\Barang::all();
    return view('welcome', compact('jasas', 'barangs'));
});

Route::get('/dashboard', function () {
    $totalTransactions = Transaction::count();
    $activeCarts = Cart::count();
    $pendingTransactions = Transaction::where('status', 'pending')->count();
    $completedTransactions = Transaction::where('status', 'completed')->count();
    $currentMonthRevenue = Transaction::where('status', 'completed')
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->sum('total_price');
    $previousMonthRevenue = Transaction::where('status', 'completed')
        ->whereMonth('created_at', now()->subMonth()->month)
        ->whereYear('created_at', now()->subMonth()->year)
        ->sum('total_price');
    $revenueGrowth = $previousMonthRevenue > 0
        ? round((($currentMonthRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100, 2)
        : ($currentMonthRevenue > 0 ? 100 : 0);

    return view('dashboard', compact(
        'totalTransactions',
        'activeCarts',
        'pendingTransactions',
        'completedTransactions',
        'currentMonthRevenue',
        'revenueGrowth'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Jasa Routes
    Route::resource('jasas', JasaController::class);

    // Barang Routes
    Route::resource('barangs', BarangController::class);

    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/{cart}', [CartController::class, 'remove'])->name('cart.remove');

    // Transaction Routes
    Route::post('/checkout', [TransactionController::class, 'checkout'])->name('transactions.checkout');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::post('/transactions/{transaction}/upload', [TransactionController::class, 'uploadPaymentProof'])->name('transactions.upload');
    Route::post('/transactions/{transaction}/upload-remaining', [TransactionController::class, 'uploadRemainingPaymentProof'])->name('transactions.uploadRemaining');
    Route::get('/transactions/checkout/history', [TransactionController::class, 'history'])->name('transactions.history');

    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        // Transaction Routes
        Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{transaction}', [AdminTransactionController::class, 'show'])->name('transactions.show');
        Route::patch('/transactions/{transaction}/approve', [AdminTransactionController::class, 'approve'])->name('transactions.approve');
        Route::patch('/transactions/{transaction}/reject', [AdminTransactionController::class, 'reject'])->name('transactions.reject');
        Route::post('/transactions/{transaction}/upload-remaining', [AdminTransactionController::class, 'uploadRemainingPaymentProof'])->name('transactions.uploadRemaining');
        Route::patch('/transactions/{transaction}/cancel', [AdminTransactionController::class, 'cancel'])->name('transactions.cancel');

        // User Routes
        Route::resource('users', UserController::class);

        // Report Routes
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/print', [ReportController::class, 'print'])->name('reports.print');
        Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.exportExcel');
        Route::get('/reports/export-pdf', [ReportController::class, 'exportPDF'])->name('reports.exportPDF');
    });
});

require __DIR__ . '/auth.php';