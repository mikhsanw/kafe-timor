<?php

use App\Livewire\OrderStatus;
use App\Livewire\TableOrder;
use App\Models\Table;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $tables = Table::orderBy('id')->get();
    return view('welcome', compact('tables'));
})->name('home');

Route::get('/meja/{kode}', TableOrder::class)->name('table.menu');
Route::get('/pesanan/{nomor}', OrderStatus::class)->name('order.status');
