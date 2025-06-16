<?php

use App\Http\Controllers\AgoraChatController;
use App\Http\Controllers\appointmentController;
use App\Http\Controllers\authenticationController;
use App\Http\Controllers\callController;
use App\Http\Controllers\commentController;
use App\Http\Controllers\docterController;
use App\Http\Controllers\patientController;
use App\Http\Controllers\paymentController;
use App\Http\Controllers\specializationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//login and sign up
Route::post("/signUp",[authenticationController::class, "signUp"]);
Route::post("/login",[authenticationController::class, "login"]);
Route::post("/logout",[authenticationController::class, "logout"]);
// ->middleware("auth:api");


//patient

Route::post("/patient/setProfile", [patientController::class, "setProfile"]);
// ->middleware("auth:api");
Route::get("/patient/{id}", [patientController::class, "getPatientById"]);
// ->middleware("auth:api");
Route::get('/doctorList/{id}',[patientController::class, "getDocterList"]);
// ->middleware("auth:api");

//docter
Route::get("/doctors", [docterController::class, "getDoctors"]);
Route::get("/doctor/{id}", [docterController::class, "getDoctorById"]);
// ->middleware("auth:api");
Route::get("/doctor/getProfileById/{id}",[docterController::class, "getProfileById"]);
// ->middleware("auth:api");
Route::get('/doctor/{id}/commment', [docterController::class, 'getCommentsOfDoctor']);
// ->middleware("auth:api");
Route::post('/doctor/setprofile', [docterController::class, 'setProfile']);
// ->middleware("auth:api");
Route::post('/doctor/editProfile/{id}', [docterController::class, 'editProfile']);
// ->middleware("auth:api");
Route::get('/patientList/{id}',[docterController::class,"getPatientList"]);
//  ->middleware("auth:api");
Route::get('/doctors/unverified-doctor', [docterController::class, 'getUserWithUnverifiedDoctor']);

//appointments
Route::post("/setAppointment",[appointmentController::class, "setAppointment"]);
// ->middleware("auth:api");
Route::get("/appointment/patient/{id}", [appointmentController::class, "getAppointmetnByPatientId"]);
// ->middleware("auth:api");
Route::get('/appointments/success/{id}', [appointmentController::class, 'appointmentSuccess']);
// ->middleware("auth:api");
Route::get("/doctor/{id}/UpcomingAppointments", [appointmentController::class, "getUpcomingAppointmentByDoctorId"]);
// ->middleware("auth:api");
Route::get("/doctor/{id}/closedAppointment", [appointmentController::class, "getClosedAppointment"]);
// ->middleware("auth:api");
Route::get("/patient/{id}/upcommingAppointments", [appointmentController::class, "getUpcomingAppointmentByPatientId"]);
// ->middleware("auth:api");


//call 
Route::post("/setCall",[callController::class, "setCall"]);
// ->middleware("auth:api");

//payment
Route::post("/setPayment",[paymentController::class, "setPayment"]);
// ->middleware("auth:api");

//comment
Route::get("/docter/{id}",[commentController::class,"getCommentByDoctorId"]);
// ->middleware("auth:api");
Route::post('/appointment/{id}/comment', [commentController::class, 'postComment']);
// ->middleware("auth:api");


//speciality 
Route::get('/specialization/{name}/doctorsList', [specializationController::class,  'specializedDoctors']);
// ->middleware("auth:api");

//Agorachat
Route::post('/create/agora/user', [AgoraChatController::class, 'create']);
Route::post('/agora/token/user', [AgoraChatController::class, 'generateUserToken']);
Route::get('/agora/token/app', [AgoraChatController::class, 'generateAppToken']);
