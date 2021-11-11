<?php

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\AdminCheck;
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

Route::post('/register', [AuthController::class, 'register']);

Route::prefix('auth')->middleware([AdminCheck::class])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    // users 
    Route::get('/user/getAll', [UsersController::class, 'getAllUsers']);
    Route::get('/user/getById/{id}', [UsersController::class, 'getByIdUser']);
    Route::post('/user/create', [UsersController::class, 'createUser']);
    Route::put('/user/update/{id}', [UsersController::class, 'updateUser']);
    Route::delete('user/delete/{id}',[UsersController::class,'deleteUser']);
    // posts
    Route::get('/posts/getAll', [PostController::class, 'getAllPosts']);
    Route::get('/post/getById/{id}', [PostController::class, 'getByIdPost']);
    Route::post('/post/create', [PostController::class, 'createPost']);
    Route::put('/post/update/{id}', [PostController::class, 'updatePost']);
    Route::delete('post/delete/{id}', [PostController::class, 'deletePost']);
    // roles
    Route::get('/roles/getAll', [RolesController::class, 'getAllRoles']);
    Route::get('/role/getById/{id}', [RolesController::class, 'getByIdRole']);
    Route::post('/role/create', [RolesController::class, 'createRole']);
    Route::put('/role/update/{id}', [RolesController::class, 'updateRole']);
    // permission
    Route::get('/permission/getAll', [PermissionController::class, 'getAllPermission']);
    Route::get('/permission/getById/{id}', [PermissionController::class, 'getByIdPermission']);   
});

// if role admin
Route::group(['middleware' => ['role:admin']],function(){
    //roles
    // Route::post('/role/create', [RolesController::class, 'createRole']);
    // Route::put('/role/update/{id}', [RolesController::class, 'updateRole']);
     //permissions
//     Route::post('/permission/create', [PermissionController::class, 'createPermission']);
//     Route::put('/permission/update/{id}', [PermissionController::class, 'updatePermission']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);
});
