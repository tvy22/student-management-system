<?php

use Illuminate\Support\Facades\Route;


//temporary routes to test frontend

Route::get('/app', function () {
    return view('layouts.app');
});

//login form
Route::get("/", function(){
    return view('auth.login');
});

//register form
Route::get("/register", function(){
    return view('auth.register');
});

//dashboard
Route::get("/dashboard", function(){
    return view('dashboard');
});

//view student from class card
Route::get("/student", function(){
    return view('students.index');
});

//student attendance history page
Route::get("/attendance", function(){
    return view('students.attendance-history');
});

//take daily attendance
Route::get("/take", function(){
    return view('attendance.take-attendance');
});

//about page
Route::get("/about", function(){
    return view('pages.about');
});

//classes page
Route::get("/class", function(){
    return view('pages.classes');
});

//view students from dashboard sidebar
Route::get("/students", function(){
    return view('pages.students');
});
