<?php

use App\Http\Controllers\API\CollectionController;
use App\Http\Controllers\API\materialsController;
use App\Http\Controllers\API\MessageController;
use App\Http\Controllers\API\userController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\filtrationController;
use App\Http\Controllers\mainPageController;
use App\Http\Controllers\PurchaseController;
use App\Http\Resources\materialsResource;
use App\Http\Resources\MessageResource;
use App\Http\Resources\ReportedResourse;
use App\Http\Resources\SubsResource;
use App\Http\Resources\UserResource;
use App\Models\approved_material;
use App\Models\Bought;
use App\Models\Collection;
use App\Models\Collection_items;
use App\Models\Message;
use App\Models\Pockets;
use App\Models\Price;
use App\Models\Report;
use App\Models\Subscription;
use App\Models\tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\User;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Авторизация
Route::post('/register', [userController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);
Route::get('/getuser/{id}', [UserController::class, 'get_user']);
Route::post('/deleteperson', [UserController::class, 'delete']);

Route::post('/edit', [userController::class, 'updateData']);
Route::post('/follow', [userController::class, 'follow']);

Route::post('/usersearch', [userController::class, 'find_user']);
Route::post('/reportuser', [userController::class, 'report_user']);
Route::post('/deletereport', [userController::class, 'delete_report']);

// Материалы
Route::post('/sendingmaterial', [materialsController::class, 'send_for_approval']);
Route::get('/getinadmin', [materialsController::class, 'show_in_admin']);
Route::post('/delete', [materialsController::class, 'delete']);
Route::post('/approve', [materialsController::class, 'approve']);
Route::get('/maindata', [materialsController::class, 'show_in_main']);
Route::get('/maindatapopular', [materialsController::class, 'show_popular_in_main']);
Route::post('/like', [materialsController::class, 'like']);

// Загрузить больше
Route::post('/moredata', [mainPageController::class, 'more_data']);
Route::post('/moredatauser', [mainPageController::class, 'more_user']);
Route::post('/moredataliked', [mainPageController::class, 'more_liked']);


Route::post('/searchword', [filtrationController::class, 'wordSearch']);
Route::post('/filter', [filtrationController::class, 'filter']);


Route::get('/usermaterials/{id}', [materialsController::class, 'show_users']);
Route::get('/userliked/{id}', [materialsController::class, 'show_liked']);


Route::post('/create_collection', [CollectionController::class, 'make']);
Route::post('/delete_collection', [CollectionController::class, 'delete']);
Route::post('/collectionstatuschange', [CollectionController::class, 'status_change']);


Route::post('/buypakege', [PurchaseController::class, 'buypackege']);
Route::post('/downloadmaterial', [PurchaseController::class, 'buy']);
Route::post('/pricechange', [PurchaseController::class, 'price_change']);


Route::post('/deletemessage', [MessageController::class, 'delete']);
Route::post('/warning', [MessageController::class, 'warning']);


// Геттеры
Route::get('/tags', function () {
    $tags = tag::all();
    return $tags;
});
Route::get('/prices', function () {
    $prices = Price::all();
    return $prices;
});
Route::get('/subcheck/{id}', function ($id) {
    $subscribed = false;
    if(Auth::check()){
        $subscribed = Subscription::where([
            ['users_id', '=', Auth::user()->id], 
            ['followed_id', '=', $id]
        ])->exists();
    }

    return $subscribed;
});
Route::get('/subs', function () {
    $subs = Subscription::where('users_id', '=', Auth::user()->id)->get();
    return SubsResource::collection($subs);
});
Route::get('/getuserscollections', function () {
    $collections = Collection::where('users_id', '=', Auth::user()->id)->get();
    return $collections;
});
Route::get('/collectionitems/{id}', function ($id) {
    $items = Collection_items::join('approved_materials', 'approved_materials.id', '=', 'collection_items.approved_materials_id')
            ->where('collection_items.collections_id', '=', $id)
            ->select('approved_materials.id as id', 'approved_materials.path', 'approved_materials.format', 'approved_materials.type', 'approved_materials.users_id', 'approved_materials.likes')
            ->paginate(100);
    return materialsResource::collection($items);
});
Route::get('/collectionname/{id}', function ($id) {
    $collections = Collection::where('id', '=', $id)->get();
    return $collections[0]->name;
});
Route::get('/pakages', function () {
    $exists = Pockets::where('users_id', '=', Auth::user()->id)->exists();
    if($exists){
        $ammount = Pockets::where('users_id', '=', Auth::user()->id)->get();
        return $ammount[0]->purchases_left;
    }
    return 0;
});
Route::get('/material/{id}', function ($id) {
    $material = approved_material::where('id', '=', $id)->get();
    return materialsResource::collection($material);
});
Route::get('/isbought/{id}', function ($id) {
    $exists = Bought::where([
        ['approved_materials_id', '=', $id],
        ['users_id', '=', Auth::user()->id]
        ])->exists();
    return $exists;
});
Route::get('/mybought', function () {
    $items = Bought::join('approved_materials', 'approved_materials.id', '=', 'boughts.approved_materials_id')
            ->where('boughts.users_id', '=', Auth::user()->id)
            ->select('approved_materials.id as id', 'approved_materials.path', 'approved_materials.format', 'approved_materials.type', 'approved_materials.users_id', 'approved_materials.likes')
            ->paginate(100);
    return materialsResource::collection($items);
});
Route::get('/messages', function () {
    $messages = Message::where('users_id', '=', Auth::user()->id)->orderBy('id', 'desc')->get();
    return MessageResource::collection($messages);
});
Route::get('/author_', function () {
    $authors = User::orderBy('followers', 'asc')->where('nikname', '<>', 'Admin')->paginate(9);

    return $authors;
});
Route::get('/allpeople', function () {
    $authors = User::where('nikname', '<>', 'Admin')->get();

    return $authors;
});
Route::get('/collectioncheck/{id}', function ($id) {
    $exists = Collection::where([
        ['users_id', '=', Auth::user()->id],
        ['id', '=', $id]
    ])->exists();

    return $exists;
});
Route::get('/reportedusers', function () {
    $reports = Report::all();
    return ReportedResourse::collection($reports);
});