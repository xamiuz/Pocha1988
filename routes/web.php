<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderChatController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

// Halaman Pengguna / Menu Pocha
Route::get('/', [HomeController::class, 'index'])->name('home');

// Checkout & Pemesanan Samarinda
Route::get('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
Route::post('/checkout', [OrderController::class, 'store'])->name('orders.store');

// Lacak Pesanan, Pembayaran, Webhook & Fitur Geser Konfirmasi Pesanan
Route::get('/orders/{orderNumber}', [OrderController::class, 'show'])->name('orders.show');
Route::get('/orders/{orderNumber}/status', [OrderController::class, 'checkStatus'])->name('orders.check_status');
Route::post('/orders/{orderNumber}/upload-proof', [OrderController::class, 'uploadProof'])->name('orders.upload_proof');
Route::post('/orders/{orderNumber}/payment-success', [OrderController::class, 'paymentSuccess'])->name('orders.payment_success');
Route::post('/orders/{orderNumber}/confirm-delivery', [OrderController::class, 'confirmDelivery'])->name('orders.confirm_delivery');
Route::post('/midtrans/webhook', [OrderController::class, 'webhookNotification'])->name('midtrans.webhook');
Route::post('/payment/webhook-sms', [OrderController::class, 'webhookSms'])->name('payment.webhook_sms');

// Live Chat Antara Pembeli & Admin/Resto
Route::get('/orders/{orderNumber}/messages', [OrderChatController::class, 'index'])->name('orders.messages.index');
Route::post('/orders/{orderNumber}/messages', [OrderChatController::class, 'store'])->name('orders.messages.store');

// Autentikasi Admin (Password Gate)
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->middleware('throttle:5,1')->name('admin.login.submit');
Route::match(['get', 'post'], '/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Dashboard Admin & Manajemen Dapur / Menu (Terproteksi Password)
Route::prefix('admin')->name('admin.')->middleware('admin.auth')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/check-new-orders', [AdminController::class, 'checkNewOrders'])->name('check_new_orders');
    Route::get('/archive', [AdminController::class, 'archiveOrders'])->name('archive');
    Route::post('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.update_status');
    Route::post('/orders/{order}/verify-payment', [AdminController::class, 'verifyPayment'])->name('orders.verify_payment');

    // CRUD Menu Makanan
    Route::get('/menu', [AdminController::class, 'menuIndex'])->name('menu.index');
    Route::post('/menu', [AdminController::class, 'storeMenuItem'])->name('menu.store');
    Route::put('/menu/{item}', [AdminController::class, 'updateMenuItem'])->name('menu.update');
    Route::delete('/menu/{item}', [AdminController::class, 'destroyMenuItem'])->name('menu.destroy');
    Route::post('/menu/{item}/toggle', [AdminController::class, 'toggleMenuAvailability'])->name('menu.toggle');

    // CRUD Jenis / Kategori Menu
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');

    // CRUD Menu Promo & Penawaran Spesial
    Route::get('/promos', [AdminController::class, 'promoIndex'])->name('promos.index');
    Route::post('/promos', [AdminController::class, 'storePromo'])->name('promos.store');
    Route::put('/promos/{promo}', [AdminController::class, 'updatePromo'])->name('promos.update');
    Route::delete('/promos/{promo}', [AdminController::class, 'destroyPromo'])->name('promos.destroy');
    Route::post('/promos/{promo}/toggle', [AdminController::class, 'togglePromo'])->name('promos.toggle');

    // Manajemen Metode Pembayaran (QRIS, Rekening Bank, COD) & Status Buka/Tutup Toko
    Route::get('/payments', [AdminController::class, 'paymentSettings'])->name('payments.index');
    Route::post('/payments', [AdminController::class, 'updatePaymentSettings'])->name('payments.update');
    Route::post('/store/toggle', [AdminController::class, 'toggleStoreStatus'])->name('store.toggle');
});
