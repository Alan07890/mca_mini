<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FarmerController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('layouts.guest');
})->name('guest');


/*
|--------------------------------------------------------------------------
| User Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return redirect()->route('farmerhome');
})->middleware('auth')->name('dashboard');


/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
| Only authenticated users with role = admin can access.
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Admin Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/index', [AdminController::class, 'index'])
        ->name('index');


    /*
    |--------------------------------------------------------------------------
    | User Management
    |--------------------------------------------------------------------------
    */

    Route::get('/users', [AdminController::class, 'users'])
        ->name('users');

    Route::put('/users/{id}', [AdminController::class, 'user_update'])
        ->name('user_update');

    Route::delete('/users/{id}', [AdminController::class, 'user_delete'])
        ->name('user_delete');


    /*
    |--------------------------------------------------------------------------
    | Category Management
    |--------------------------------------------------------------------------
    */

    Route::get('/category', [CategoryController::class, 'category'])
        ->name('category');

    Route::post(
        '/diseasecategory_insert',
        [CategoryController::class, 'diseasecategory_insert']
    )->name('diseasecategory_insert');

    Route::get(
        '/categoryview',
        [CategoryController::class, 'categoryview']
    )->name('categoryview');

    Route::get(
        '/category_edit/{id}',
        [CategoryController::class, 'category_edit']
    )->name('category_edit');

    Route::put(
        '/category_update/{id}',
        [CategoryController::class, 'category_update']
    )->name('category_update');

    Route::delete(
        '/category_delete/{id}',
        [CategoryController::class, 'category_delete']
    )->name('category_delete');

    Route::get(
        '/displaycategory',
        [CategoryController::class, 'index']
    )->name('displaycategory');


    /*
    |--------------------------------------------------------------------------
    | Symptom Management
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/symptoms',
        [AdminController::class, 'symptoms']
    )->name('symptoms');

    Route::post(
        '/symptom_insert',
        [AdminController::class, 'symptom_insert']
    )->name('symptom_insert');

    Route::get(
        '/symptomsview',
        [AdminController::class, 'symptomsview']
    )->name('symptomsview');

    Route::get(
        '/symptom_edit/{id}',
        [AdminController::class, 'symptom_edit']
    )->name('symptom_edit');

    Route::put(
        '/symptom_update/{id}',
        [AdminController::class, 'symptom_update']
    )->name('symptom_update');

    Route::delete(
        '/symptom_delete/{id}',
        [AdminController::class, 'symptom_delete']
    )->name('symptom_delete');


    /*
    |--------------------------------------------------------------------------
    | Symptom Registration
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/symptom_regs',
        [AdminController::class, 'indexSymptomReg']
    )->name('symptom_regs');

    Route::get(
        '/symptom_reg',
        [AdminController::class, 'createSymptomReg']
    )->name('symptom_reg');

    Route::post(
        '/symptom_reg_insert',
        [AdminController::class, 'storeSymptomReg']
    )->name('symptom_reg_insert');

    Route::get(
        '/symptom_reg_edit/{id}',
        [AdminController::class, 'editSymptomReg']
    )->name('symptom_reg_edit');

    Route::put(
        '/symptom_reg_update/{id}',
        [AdminController::class, 'updateSymptomReg']
    )->name('symptom_reg_update');

    Route::delete(
        '/symptom_reg_delete/{id}',
        [AdminController::class, 'deleteSymptomReg']
    )->name('symptom_reg_delete');


    /*
    |--------------------------------------------------------------------------
    | Fertilizer Management
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/fertilizer',
        [AdminController::class, 'fertilizer']
    )->name('fertilizer');

    Route::post(
        '/fertilizer_insert',
        [AdminController::class, 'fertilizer_insert']
    )->name('fertilizer_insert');

    Route::get(
        '/fertilizer_edit/{id}',
        [AdminController::class, 'fertilizer_edit']
    )->name('fertilizer_edit');

    Route::put(
        '/fertilizer_update/{id}',
        [AdminController::class, 'fertilizer_update']
    )->name('fertilizer_update');

    Route::delete(
        '/fertilizer_delete/{id}',
        [AdminController::class, 'fertilizer_delete']
    )->name('fertilizer_delete');

    Route::post(
        '/fertilizer_mapping_store',
        [AdminController::class, 'fertilizer_mapping_store']
    )->name('fertilizer_mapping_store');

    Route::delete(
        '/fertilizer_mapping_delete/{id}',
        [AdminController::class, 'fertilizer_mapping_delete']
    )->name('fertilizer_mapping_delete');


    /*
    |--------------------------------------------------------------------------
    | Field Management
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/field-management',
        [AdminController::class, 'fieldManagement']
    )->name('admin.field-management');

    Route::put(
        '/field-management/{id}',
        [AdminController::class, 'fieldUpdate']
    )->name('admin.field-update');

    Route::delete(
        '/field-management/{id}',
        [AdminController::class, 'fieldDelete']
    )->name('admin.field-delete');

});


/*
|--------------------------------------------------------------------------
| FARMER ROUTES
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| Farmer Home
|--------------------------------------------------------------------------
*/

Route::get(
    '/farmerhome',
    [FarmerController::class, 'farmerHome']
)->middleware('auth')->name('farmerhome');


/*
|--------------------------------------------------------------------------
| Farmer Disease Identification Page
|--------------------------------------------------------------------------
*/

Route::get(
    '/farmer/disease-identification',
    [FarmerController::class, 'diseaseIdentification']
)->middleware('auth')->name('disease.identification');


/*
|--------------------------------------------------------------------------
| Farmer Disease Identification Analysis
|--------------------------------------------------------------------------
|
| Existing symptom-only analysis route.
|--------------------------------------------------------------------------
*/

Route::post(
    '/farmer/disease-identification/analyze',
    [FarmerController::class, 'analyzeDisease']
)->middleware('auth')->name('disease.identification.analyze');


/*
|--------------------------------------------------------------------------
| Farmer Disease Image Preliminary Analysis
|--------------------------------------------------------------------------
|
| Stage 1:
| The farmer uploads a plant image.
| Gemini Vision analyzes the image and returns:
| - visible observations
| - visible symptoms
| - possible disease suggestions
| - confirmation questions
| - image quality
|
| This stage does NOT finalize the disease prediction.
|--------------------------------------------------------------------------
*/

Route::post(
    '/farmer/disease-identification/analyze-image',
    [FarmerController::class, 'analyzePlantImage']
)->middleware('auth')->name('disease.identification.analyze-image');


/*
|--------------------------------------------------------------------------
| Farmer Disease Image + Confirmed Symptoms Final Analysis
|--------------------------------------------------------------------------
|
| Stage 2:
| The image and farmer-confirmed symptoms are sent to Gemini.
| Gemini performs the final AI-assisted disease prediction.
|--------------------------------------------------------------------------
*/

Route::post(
    '/farmer/disease-identification/predict-image',
    [FarmerController::class, 'predictDiseaseFromImageAndSymptoms']
)->middleware('auth')->name('disease.identification.predict-image');


/*
|--------------------------------------------------------------------------
| Farmer Field Management
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get(
        '/farmer/field',
        [FarmerController::class, 'field']
    )->name('farmer.field');

    Route::post(
        '/farmer/field',
        [FarmerController::class, 'saveField']
    )->name('farmer.field.save');

});


/*
|--------------------------------------------------------------------------
| Laravel Breeze Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
