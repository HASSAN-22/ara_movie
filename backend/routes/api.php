<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Captcha\CaptchaController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * Login and register routes
 */
Route::group([],function(){
//    Route::get('getSiteKey',[CaptchaController::class,'siteKey'])->name('siteKey');
    Route::post('register',[RegisterController::class,'register'])->name('register');
    Route::post('login',[LoginController::class,'login'])->name('login');
    Route::post('forget-password',[LoginController::class,'forgetPassword'])->name('forgetPassword');
    Route::post('reset-password',[LoginController::class,'resetPassword'])->name('resetPassword');
    Route::post('get-reset-password-token',[LoginController::class,'getResetPasswordToken'])->name('getResetPasswordToken');
    Route::post('logout',[LoginController::class,'logout'])->name('logout');
    Route::get('verify',[\App\Http\Controllers\V1\VerifyController::class,'verify'])->name('verify');
    Route::get('vip-verify',[\App\Http\Controllers\V1\VerifyController::class,'vipVerify'])->name('vipVerify');
});

/**
 * Front routes
 */
Route::group([],function(){
    Route::get('index',[\App\Http\Controllers\V1\FrontController::class,'index'])->name('index');
    Route::get('genre/{genre}/{type}/{take}',[\App\Http\Controllers\V1\FrontController::class,'genre'])->name('genre');
    Route::get('get-movies',[\App\Http\Controllers\V1\FrontController::class,'getMovies'])->name('getMovies');
    Route::get('list-movies',[\App\Http\Controllers\V1\FrontController::class,'listMovies'])->name('listMovies');
    Route::get('movie-detail/{slug}',[\App\Http\Controllers\V1\FrontController::class,'movieDetail'])->name('movieDetail');
    Route::post('movie-like-dislike/{movie}',[\App\Http\Controllers\V1\FrontController::class,'likeAndDisLikeMovie'])->name('likeAndDisLikeMovie');
    Route::post('comment-like-dislike',[\App\Http\Controllers\V1\FrontController::class,'likeAndDisLikeComment'])->name('likeAndDisLikeComment');
    Route::post('comment-send/{movie}',[\App\Http\Controllers\V1\FrontController::class,'comment'])->name('comment');
    Route::post('report-link',[\App\Http\Controllers\V1\FrontController::class,'reportLink'])->name('reportLink');
    Route::post('search',[\App\Http\Controllers\V1\FrontController::class,'search'])->name('search');
    Route::post('filter',[\App\Http\Controllers\V1\FrontController::class,'filter'])->name('filter');
    Route::get('get-plan-vip',[\App\Http\Controllers\V1\FrontController::class,'getPlan'])->name('getPlan');
    Route::get('show-page/{title}',[\App\Http\Controllers\V1\FrontController::class,'showPage'])->name('showPage');
    Route::get('get-config',[\App\Http\Controllers\V1\FrontController::class,'getConfig'])->name('getConfig');
});



/** Dashboard Routes */
Route::group(['middleware'=>'auth:sanctum'],function(){
    Route::post('add-watch-list/{movie}',[\App\Http\Controllers\V1\FrontController::class,'watchList'])->name('watchList');
    Route::get('check-token',[\App\Http\Controllers\CheckTokenController::class,'checkToken'])->name('checkToken');
    Route::get('menu',[\App\Http\Controllers\V1\BackendController::class,'menu'])->name('menu');
    Route::get('dashboard',[\App\Http\Controllers\V1\BackendController::class,'dashboard'])->name('dashboard');
     /** Admin Routes */
        Route::group(['middleware'=>'front.session'],function(){
            // Search Routes
            Route::group([],function(){
                Route::post('user-search',[\App\Http\Controllers\V1\SearchController::class,'userSearch']);
                Route::post('movie-search',[\App\Http\Controllers\V1\SearchController::class,'movieSearch']);
                Route::post('movieLink-search',[\App\Http\Controllers\V1\SearchController::class,'movieLinkSearch']);
                Route::post('movieTag-search',[\App\Http\Controllers\V1\SearchController::class,'movieTagSearch']);
                Route::post('discount-search',[\App\Http\Controllers\V1\SearchController::class,'discountSearch']);
                Route::post('cart-search',[\App\Http\Controllers\V1\SearchController::class,'cartSearch']);
                Route::post('wallet-transaction-search',[\App\Http\Controllers\V1\SearchController::class,'walletTransactionSearch']);
                Route::post('checkout-search',[\App\Http\Controllers\V1\SearchController::class,'checkoutSearch']);
                Route::post('vip-search',[\App\Http\Controllers\V1\SearchController::class,'vipSearch']);
                Route::post('comment-search',[\App\Http\Controllers\V1\SearchController::class,'commentSearch']);
                Route::post('page-search',[\App\Http\Controllers\V1\SearchController::class,'pageSearch']);

            });

            Route::apiResource('category',\App\Http\Controllers\V1\Admin\CategoryController::class);
            Route::apiResource('user',\App\Http\Controllers\V1\Admin\UserController::class);
            Route::get('user-permission/{user}',[\App\Http\Controllers\V1\Admin\UserController::class,'permission'])->name('permission');
            Route::patch('user-update-permission/{user}',[\App\Http\Controllers\V1\Admin\UserController::class,'updatePermission'])->name('updatePermission');
            Route::apiResource('movie',\App\Http\Controllers\V1\Admin\MovieController::class);
            Route::apiResource('movieLink',\App\Http\Controllers\V1\Admin\MovieLinkController::class);
            Route::apiResource('movieTag',\App\Http\Controllers\V1\Admin\MovieTagController::class);
            Route::apiResource('plan',\App\Http\Controllers\V1\Admin\PlanController::class);
            Route::apiResource('discount',\App\Http\Controllers\V1\Admin\DiscountController::class);
            Route::apiResource('cart',\App\Http\Controllers\V1\Admin\CartController::class)->only(['index','update','show']);
            Route::apiResource('bankPortal',\App\Http\Controllers\V1\Admin\BankPortalController::class);
            Route::apiResource('wallet-transaction',\App\Http\Controllers\V1\Admin\WalletTransactionController::class)->only(['index']);
            Route::apiResource('checkout',\App\Http\Controllers\V1\Admin\CheckoutController::class)->only(['index','show','update']);
            Route::apiResource('vip',\App\Http\Controllers\V1\Admin\VipTransactionController::class)->only(['index']);
            Route::apiResource('comment',\App\Http\Controllers\V1\Admin\CommentController::class);
            Route::apiResource('page',\App\Http\Controllers\V1\Admin\PageController::class);
            Route::apiResource('slider',\App\Http\Controllers\V1\Admin\SliderController::class)->only('index','store','destroy');
            Route::apiResource('reportLink',\App\Http\Controllers\V1\Admin\ReportLinkController::class)->only('index','destroy');

            Route::get('comment-show/{id}/{action}',[\App\Http\Controllers\V1\Admin\CommentController::class,'commentShow'])->name('commentShow');
            Route::get('get-info-movie',[\App\Http\Controllers\V1\Admin\MovieController::class,'getInfo'])->name('getInfo');
            Route::get('profile/{user}',[\App\Http\Controllers\V1\Admin\ProfileController::class,'getProfile'])->name('getProfile');
            Route::post('profile/{user}',[\App\Http\Controllers\V1\Admin\ProfileController::class,'updateProfile'])->name('updateProfile');

            Route::post('show-gallery',[\App\Http\Controllers\V1\Admin\GalleryController::class,'showGallery'])->name('showGallery');
            Route::post('make-folder',[\App\Http\Controllers\V1\Admin\GalleryController::class,'makeFolder'])->name('makeFolder');
            Route::post('gallery-upload',[\App\Http\Controllers\V1\Admin\GalleryController::class,'galleryUpload'])->name('galleryUpload');
            Route::post('gallery-remove',[\App\Http\Controllers\V1\Admin\GalleryController::class,'galleryRemove'])->name('galleryRemove');
            Route::post('gallery-rename',[\App\Http\Controllers\V1\Admin\GalleryController::class,'galleryRename'])->name('galleryRename');
            Route::get('configSite/{configSite}',[\App\Http\Controllers\V1\Admin\ConfigSiteController::class,'show'])->name('show');
            Route::patch('configSite/{configSite}',[\App\Http\Controllers\V1\Admin\ConfigSiteController::class,'update'])->name('update');
        });
    /** End Admin Routes */

    /** User Routes */
    Route::group(['middleware'=>'front.session'],function(){
        // Search Routes
        Route::group([],function(){
            Route::post('user-wallet-transaction-search',[\App\Http\Controllers\V1\SearchController::class,'walletTransactionSearch']);
            Route::post('user-checkout-search',[\App\Http\Controllers\V1\SearchController::class,'checkoutSearch']);
            Route::post('user-vip-search',[\App\Http\Controllers\V1\SearchController::class,'vipSearch']);
            Route::post('user-watch-list-search',[\App\Http\Controllers\V1\SearchController::class,'watchList']);

        });
        Route::get('profile-user/{user}',[\App\Http\Controllers\V1\User\ProfileController::class,'getProfile'])->name('getProfile');
        Route::post('profile-user/{user}',[\App\Http\Controllers\V1\User\ProfileController::class,'updateProfile'])->name('updateProfile');
        Route::apiResource('user-cart',\App\Http\Controllers\V1\User\CartController::class)->only(['index','store']);
        Route::apiResource('user-wallet-transaction',\App\Http\Controllers\V1\User\WalletTransactionController::class)->only(['index','store']);
        Route::apiResource('user-checkout',\App\Http\Controllers\V1\User\CheckoutController::class)->only(['index','store','update']);
        Route::apiResource('user-vip',\App\Http\Controllers\V1\User\VipTransactionController::class)->only(['index','store']);
        Route::apiResource('user-watch-list',\App\Http\Controllers\V1\User\WatchListController::class)->only(['index','destroy']);
        Route::get('choose-plan/{plan}',[\App\Http\Controllers\V1\User\VipTransactionController::class,'choosePlan'])->name('choosePlan');

    });
    /** End User Routes */
});
