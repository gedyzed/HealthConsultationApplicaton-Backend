<?php

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


//patient

Route::post("/patient/setProfile", [patientController::class, "setProfile"]);
Route::get("/patient/{id}", [patientController::class, "getPatientById"]);

//docter
Route::post("/doctor/setProfile", [docterController::class, "setProfile"]);
Route::get("/doctor/{id}", [docterController::class, "getDoctorById"]);
Route::get("/doctor/getProfileById/{id}",[docterController::class, "getProfileById"]);
Route::get('/doctor/{id}/commment', [docterController::class, 'getCommentsOfDoctor']);
Route::post('/doctor/setDoctorProfile', [docterController::class, 'setDoctorProfile']);


//appointments
Route::post("/setAppointment",[appointmentController::class, "setAppointment"]);
Route::get("/appointment/patient/{id}", [appointmentController::class, "getAppointmetnByPatientId"]);
Route::get('/appointments/success/{id}', [appointmentController::class, 'appointmentSuccess']);
Route::get("/doctor/{id}/UpcomingAppointments", [appointmentController::class, "getUpcomingAppointmentByDoctorId"]);
Route::get("/doctor/{id}/closedAppointment", [appointmentController::class, "getClosedAppointment"]);
Route::get("/patient/{id}/upcommingAppointments", [appointmentController::class, "getUpcomingAppointmentByPatientId"]);


//call 
Route::post("/setCall",[callController::class, "setCall"]);

//payment
Route::post("/setPayment",[paymentController::class, "setPayment"]);

//comment
Route::get("/docter/{id}",[commentController::class,"getCommentByDoctorId"]);
Route::post('/appointment/{id}/comment', [commentController::class, 'postComment']);


//speciality 
Route::get('/specialization/{name}/doctorsList', [specializationController::class,  'specializedDoctors']);

