<?php

use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Dashboard\DashboardController;
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
use App\Http\Controllers\Rcheck\RadgroupreplyController;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\AuditLog\AuditLogController;
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


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/about', fn () => Inertia::render('About'))->name('about');

    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::resource('users', UserController::class)->except(['index', 'show']);
    Route::patch('users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Dashboard
    

    //radcheck
    Route::resource('radcheck', RadcheckController::class);

    //NAS
    Route::resource('nas', NasController::class);

    //Radacct
    Route::resource('radacct', RadacctController::class);

    //Radgroupcheck
    Route::resource('rgroup', RadgroupcheckController::class);
   /*  Route::post('rgroup/{groupId}/assign-clients', [RadgroupcheckController::class, 'assignClients'])->name('rgroup.assignClients');
    Route::post('rgroup/{id}/delClients',[RadgroupcheckController::class, 'delClients'])->name('rgroup.delClients'); */

    //Radusergroup
    Route::resource('ruserg', RadusergroupController::class);
    /* Route::get('/ruserg/{id}',[RadusergroupController::class, 'index'])->name('ruserg.index'); */

    //RadgroupReply
    Route::resource('rgreply', RadgroupreplyController::class);
    Route::post('rgreply/{groupId}/assign-clients',[RadgroupreplyController::class, 'assignClients'])->name('rgreply.assignClients');
    Route::post('rgreply/{id}/delClients',[RadgroupreplyController::class, 'delClients'])->name('rgreply.delClients');
  

    //Clientes
    Route::resource('client', ClientController::class);
    Route::post('client/{id}/showRcheck',[ClientController::class, 'showRcheck'])->name('client.showRcheck');
    Route::post('client/{id}/toggle',[ClientController::class, 'toggle'])->name('client.toggle');

    //ROles 
    Route::resource('rol', RolController::class);
    

    //Reportes
    Route::resource('report', ReportController::class);
    Route::get('/reports/user-bandwidth-stats', [ReportController::class, 'getUserBandwidthStats'])->name('reports.userBandwidthStats');
    Route::get('/reports/user-connection-history', [ReportController::class, 'getUserConnectionHistory'])->name('reports.userConnectionHistory');
    Route::get('/reports/new-clients-this-week', [ReportController::class, 'getNewClientsThisWeek'])->name('reports.newClientsThisWeek');
    Route::get('/reports/inactive-clients', [ReportController::class, 'getInactiveClients'])->name('reports.inactiveClients');
    Route::get('/reports/clients-without-plan', [ReportController::class, 'getClientsWithoutPlan'])->name('reports.clientsWithoutPlan');
    Route::get('/reports/failed-auth-attempts', [ReportController::class, 'getFailedAuthAttempts'])->name('reports.failedAuthAttempts');
    Route::get('/reports/simultaneous-sessions', [ReportController::class, 'getSimultaneousSessions'])->name('reports.simultaneousSessions');
    Route::get('/reports/disconnection-stats', [ReportController::class, 'getDisconnectionStats'])->name('reports.disconnectionStats');
    Route::get('/reports/nas-by-user', [ReportController::class, 'getNasStatsByUser'])->name('reports.nasByUser');
    Route::get('/reports/export-excel', [ReportController::class, 'exportToExcel'])->name('reports.exportExcel');
    Route::get('/reports/export-pdf', [ReportController::class, 'exportToPdf'])->name('reports.exportPdf');


      //Mikrotik
    Route::get('/pppoe/sesiones', [MikrotikController::class, 'sessions']);
    
    //Audit Logs
    Route::resource('auditlog', AuditLogController::class);

});

require __DIR__.'/auth.php';
