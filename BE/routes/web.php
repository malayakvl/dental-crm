<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

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
//Route::middleware('auth')->group(function () {
//    Route::resource('clinic', \App\Http\Controllers\Crud\ClinicController::class);
//});

Route::prefix('api')
    ->name('api.')
    ->group(function () {
        //AUTH
        Route::post('login', [\App\Http\Controllers\Api\UserController::class, 'login'])->name('user.login');
        Route::get('profile', [\App\Http\Controllers\Api\UserController::class, 'profile'])->name('user.profile');
        Route::get('profile-prmission', [\App\Http\Controllers\Api\UserController::class, 'profilePermissions'])->name('user.permissions');

        Route::get('pages/all', [\App\Http\Controllers\Api\UserController::class, 'page'])->name('user.page');

        // CRUD
        Route::post('clinic/store', [\App\Http\Controllers\Crud\ClinicController::class, 'store'])->name('clinic.store');
        Route::post('clinic/update/{id}', [\App\Http\Controllers\Crud\ClinicController::class, 'update'])->name('clinic.update');
        Route::delete('clinic/delete/{id}', [\App\Http\Controllers\Crud\ClinicController::class, 'delete'])->name('clinic.delete');

        Route::post('cabinet/store', [\App\Http\Controllers\Crud\CabinetController::class, 'store'])->name('cabinet.store');
        Route::post('cabinet/update/{id}', [\App\Http\Controllers\Crud\CabinetController::class, 'update'])->name('cabinet.update');
        Route::delete('cabinet/delete/{id}', [\App\Http\Controllers\Crud\CabinetController::class, 'delete'])->name('cabinet.delete');

        Route::post('services/store', [\App\Http\Controllers\Crud\ServiceController::class, 'store'])->name('service.store');
        Route::post('services/update/{id}', [\App\Http\Controllers\Crud\ServiceController::class, 'update'])->name('service.update');
        Route::delete('services/delete/{id}', [\App\Http\Controllers\Crud\ServiceController::class, 'delete'])->name('service.delete');

        Route::get('user/edit', [\App\Http\Controllers\Crud\UserController::class, 'edit'])->name('user.edit');
        Route::post('user/update', [\App\Http\Controllers\Crud\UserController::class, 'update'])->name('user.update');

        Route::post('scheduler/store', [\App\Http\Controllers\Crud\SchedulerEventController::class, 'store'])->name('scheduler.store');
        Route::delete('scheduler/delete/{id}', [\App\Http\Controllers\Crud\SchedulerEventController::class, 'delete'])->name('scheduler.delete');
        Route::post('scheduler/update/{id}', [\App\Http\Controllers\Crud\SchedulerEventController::class, 'update'])->name('scheduler.update');

        // Api
        Route::get('user/load', [\App\Http\Controllers\Api\UserController::class, 'load'])->name('user.load');
        Route::get('user/search', [\App\Http\Controllers\Api\UserController::class, 'search'])->name('user.search');
        Route::get('user/check-notifications', [\App\Http\Controllers\Api\UserController::class, 'checkNotifocations'])->name('user.check-notifications');
        Route::post('user/invite', [\App\Http\Controllers\Api\UserController::class, 'invite'])->name('user.invite');

        Route::get('notifications/accept/{id}', [\App\Http\Controllers\Api\NotificationController::class, 'accept'])->name('notification.accept');
        Route::get('notifications/reject/{id}', [\App\Http\Controllers\Api\NotificationController::class, 'reject'])->name('notification.reject');
        Route::get('notifications/delete/{id}', [\App\Http\Controllers\Api\NotificationController::class, 'delete'])->name('notification.delete');
        Route::get('notifications/markRead/{id}', [\App\Http\Controllers\Api\NotificationController::class, 'markRead'])->name('notification.markRead');
        Route::get('notifications', [\App\Http\Controllers\Api\NotificationController::class, 'index'])->name('notification.index');

        Route::get('staff/list', [\App\Http\Controllers\Api\StaffController::class, 'staffList'])->name('staff.staffList');
        Route::get('staff/roles', [\App\Http\Controllers\Api\StaffController::class, 'index'])->name('staff.index');
        Route::get('staff/getPatients', [\App\Http\Controllers\Api\PatientController::class, 'searchPatients'])->name('staff.searchPatients');
        Route::get('staff/setColor', [\App\Http\Controllers\Api\StaffController::class, 'setColor'])->name('staff.setColor');
        Route::get('staff/setRole', [\App\Http\Controllers\Api\StaffController::class, 'setRole'])->name('staff.setRole');
        Route::get('staff/setPermission', [\App\Http\Controllers\Api\StaffController::class, 'setPermission'])->name('staff.setPermission');

        Route::get('clinic/list', [\App\Http\Controllers\Api\ClinicController::class, 'index'])->name('clinic.index');
        Route::get('clinic/edit/{id}', [\App\Http\Controllers\Api\ClinicController::class, 'edit'])->name('clinic.edit');
        Route::get('clinic/login/{id}', [\App\Http\Controllers\Api\ClinicController::class, 'clinicLogin'])->name('clinic.clinicLogin');
        Route::get('clinic/employee-login/{id}', [\App\Http\Controllers\Api\ClinicController::class, 'employeeLogin'])->name('clinic.employeeLogin');

        Route::get('cabinet/list', [\App\Http\Controllers\Api\CabinetController::class, 'index'])->name('cabinet.index');
        Route::get('cabinet/edit/{id}', [\App\Http\Controllers\Api\CabinetController::class, 'edit'])->name('cabinet.edit');

        Route::get('services/list', [\App\Http\Controllers\Api\ServiceController::class, 'index'])->name('cabinet.index');
        Route::get('services/list-for-teeth', [\App\Http\Controllers\Api\ServiceController::class, 'list'])->name('cabinet.list');
        Route::get('services/edit/{id}', [\App\Http\Controllers\Api\ServiceController::class, 'edit'])->name('cabinet.edit');

        Route::get('diagnosis/list', [\App\Http\Controllers\Api\DiagnoseController::class, 'index'])->name('diagnose.index');

        Route::get('scheduler/fetchEvents', [\App\Http\Controllers\Api\SchedulerEventController::class, 'fetchEvents'])->name('scheduler.fetchEvents');
        Route::get('scheduler/fetchEvent/{id}', [\App\Http\Controllers\Api\SchedulerEventController::class, 'fetchEvent'])->name('scheduler.fetchEvent');

        Route::get('patients/list', [\App\Http\Controllers\Api\PatientController::class, 'fetchPatients'])->name('patients.fetchPatients');
        Route::get('patient/visits/{id}', [\App\Http\Controllers\Api\PatientController::class, 'getVisits'])->name('patients.getVisits');
        Route::get('patient/visit/{id}', [\App\Http\Controllers\Api\PatientController::class, 'getVisit'])->name('patients.getVisit');
        Route::post('visit/upload/{id}', [\App\Http\Controllers\Api\PatientController::class, 'uploadVisitFiles'])->name('patients.uploadVisitFiles');
        Route::post('visit/teeth-update/{id}', [\App\Http\Controllers\Api\PatientController::class, 'visitTeethUpdate'])->name('patients.visitTeethUpdate');
        Route::get('patient/edit/{id}', [\App\Http\Controllers\Api\PatientController::class, 'edit'])->name('patients.edit');
        Route::get('patient/set-tooth', [\App\Http\Controllers\Api\PatientController::class, 'setupToothDiagnoz'])->name('patients.setupToothDiagnoz');
        Route::get('patient/set-tooth-gum', [\App\Http\Controllers\Api\PatientController::class, 'setupGumDiagnoz'])->name('patients.setupGumDiagnoz');
        Route::get('patient/set-tooth-part', [\App\Http\Controllers\Api\PatientController::class, 'setupToothPartDiagnoz'])->name('patients.setupToothPartDiagnoz');
        Route::post('patient/update/{id}', [\App\Http\Controllers\Crud\PatientController::class, 'update'])->name('patients.update');
        Route::post('patient/store', [\App\Http\Controllers\Crud\PatientController::class, 'store'])->name('patients.store');

        Route::get('get-menu', [\App\Http\Controllers\Api\UserController::class, 'getMenu'])->name('menu.get');
    });

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

//Route::get('/{any}', function () {
//    return view('dashboard');
//})->where('any', '^(?!login|register|forgot-password|reset-password|verify-email|email|confirm-password|logout|api).*$')
//    ->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';



