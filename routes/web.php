<?php

use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Rcheck\RadcheckController;
use App\Http\Controllers\Nas\NasController;
use App\Http\Controllers\Rdacct\RadacctController;
use App\Http\Controllers\Rcheck\RadgroupcheckController;
use App\Http\Controllers\Rcheck\RadusergroupController;
use App\Http\Controllers\MikroTik\MikrotikController;
use Inertia\Inertia;

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

/* Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
 */
Route::get('/', function () {
    return Inertia::render('Auth/Login');
});


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/about', fn () => Inertia::render('About'))->name('about');

    Route::get('users', [UserController::class, 'index'])->name('users.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    //radcheck
    Route::resource('radcheck', RadcheckController::class);

    //NAS
    Route::resource('nas', NasController::class);

    //Radacct
    Route::resource('radacct', RadacctController::class);

    //Radgroupcheck
    Route::resource('rgroup', RadgroupcheckController::class);

    //Radusergroup
    Route::resource('ruserg', RadusergroupController::class);
    /* Route::get('/ruserg/{id}',[RadusergroupController::class, 'index'])->name('ruserg.index'); */

  

    //Clientes
    Route::resource('client', ClientController::class);




      //Mikrotik
    Route::get('/pppoe/sesiones', [MikrotikController::class, 'sessions']);
    

});

require __DIR__.'/auth.php';
