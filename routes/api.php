<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('/', function(){
    return 'Bienvenido';
});

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);


//Aqui creo un grupo con el middleware que controla el token 
Route::group(
    ['middleware' => 'jwt.auth'],
    function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::get('/logout', [AuthController::class, 'logout']);
    }
);


Route::group(
    ['middleware' => ['jwt.auth','is.super.admin']],
    function(){
        Route::post('/addRoleSuperAdmin/{id}', [UserController::class, 'addRoleSuperAdminToId']);
        Route::delete('/deleteRoleSuperAdmin/{id}',[UserController::class, 'deleteRoleSuperAdminToId']);
    }
);


Route::group(
    ['middleware' => ['jwt.auth']],
    function(){
        Route::post('/tasks',[TaskController::class,'createTask']);
        Route::get('/tasks',[TaskController::class,'getAllTasksByUserId']);
        Route::get('/tasks/{id}',[TaskController::class,'getOneTasksById']);
        Route::delete('tasks/{id}',[TaskController::class,'deleteOneTaskById']);
        Route::put('tasks/{id}',[TaskController::class,'updateOneTaskById']);

    }
);
