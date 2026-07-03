<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::redirect('/admin', '/admin/login');
Route::view('/admin/login', 'admin.Auth.login')->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login']);

Route::view('/admin/dashboard', 'admin.Dashboard.index')->name('admin.dashboard');
Route::view('/admin/enrollments', 'admin.Pendaftaran.index')->name('admin.enrollments.index');
Route::view('/admin/users', 'admin.User.index')->name('admin.users.index');
Route::view('/admin/training-centers', 'admin.TrainingCenter.index')->name('admin.training-centers.index');
Route::view('/admin/pelatihan', 'admin.Pelatihan.index')->name('admin.pelatihan.index');
Route::view('/admin/log-activities', 'admin.LogActivity.index')->name('admin.log-activities.index');

Route::view('/user/login', 'user.Auth.login')->name('user.login');
Route::view('/user/register', 'user.Auth.register')->name('user.register');
Route::view('/user/dashboard', 'user.Dashboard.dashboard')->name('user.dashboard');
Route::view('/user/profile', 'user.Profile.index')->name('user.profile');
Route::view('/user/questionnaire', 'user.Questionnaire.index')->name('user.questionnaire');
Route::view('/user/recommendations', 'user.Recommendation.index')->name('user.recommendations');
Route::view('/user/enrollments', 'user.Enrollment.index')->name('user.enrollments');

Route::get('/', function () {
    return view('welcome');
});
