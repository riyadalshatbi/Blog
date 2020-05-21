<?php

use Illuminate\Support\Facades\Route;

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
// Index Page
Route::get('/','pagesController@posts');

// Display Home Pages
Route::get('/posts','pagesController@posts');

// Display Single Post >>Read More<<
Route::get('/posts/{post}','pagesController@post');

// Add Comment in post
Route::post('/post/{post}/store','CommentsController@store');

// Diplay Posts of single category
Route::get('/category/{name}','pagesController@category');

// For Auth
Route::get('/register','RegistrationController@create');

// Register New User
Route::post('/register','RegistrationController@store');

// Display Login Page
Route::get('/login','SessionsController@create');

// Store Session For Login
Route::post('/login','SessionsController@store');

// For Destroy Sesstion and Logout
Route::get('/logout','SessionsController@destroy');

// for Access Denied Message
Route::get('access-denied','pagesController@accessDenied');

// Access Roles Admins
Route::group(['middleware' => 'roles' , 'roles' => ['Admin']] , function(){
    
    // Admin Role for Enter Page Admin
    Route::get('/admin','pagesController@admin');
    
    // for change role
    Route::post('/add-role','pagesController@addRole');
    
    // Access To Statistics Page
    Route::get('/statistics','PagesController@statistics');

    // For Change Settings
    Route::post('/settings','pagesController@settings');
});

// Access Roles Editors
Route::group(['middleware' => 'roles' , 'roles' => ['Editor','Admin']] , function(){
    
    // Editor Role for Access Pages
    Route::get('/editor','pagesController@editor');
    
    // Editor role for Add new post
    Route::post('/posts/store','pagesController@store');
});

// Access Roles Users
Route::group(['middleware' => 'roles' , 'roles' => ['User','Editor','Admin']] , function(){
    
    // Send Like Post By Ajax With PHP File
    Route::post('/like','pagesController@like')->name('like');
    
    // Send Dislike Post By Ajax With PHP File
    Route::post('/dislike','pagesController@dislike')->name('dislike');
});














