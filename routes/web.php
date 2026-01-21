<?php
/**
 * Web Routes Definition
 * 
 * Define all web routes here
 */

use App\Helpers\Router;

$router = new Router();

// Home page
$router->get('/', 'HomeController@index');
$router->get('/home', 'HomeController@index');

// Dergi routes
$router->get('/dergiler', 'DergiController@index');
$router->get('/dergi/{id}', 'DergiController@show');

// Makale routes
$router->get('/makale/ara', 'MakaleController@search');
$router->get('/makale/{id}', 'MakaleController@show');

// Kurul routes
$router->get('/kurul/{id}', 'KurulController@show');

// Künye route
$router->get('/kunye', 'KunyeController@show');

// Static pages
$router->get('/hakkimizda', 'PageController@about');
$router->get('/dizinler', 'PageController@indexing');
$router->get('/makale-esaslari', 'PageController@guidelines');
$router->get('/yayin-etigi', 'PageController@ethics');
$router->get('/telif-devir', 'PageController@copyright');

// Auth routes
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');
$router->get('/kayit', 'AuthController@showRegister');
$router->post('/kayit', 'AuthController@register');

// User routes
$router->get('/dashboard', 'UserController@dashboard');
$router->get('/profil', 'UserController@profile');
$router->post('/profil/guncelle', 'UserController@updateProfile');
$router->post('/profil/sifre', 'UserController@changePassword');
$router->post('/profil/imza-olustur', 'UserController@createSignature');

// Admin routes
$router->get('/admin/users', 'AdminController@users');
$router->post('/admin/users/delete/{id}', 'AdminController@deleteUser');
$router->post('/admin/users/update-group/{id}', 'AdminController@updateUserGroup');

// Board Management (Dergi Kurulları)
$router->get('/admin/kurul', 'Admin\KurulController@index');
$router->get('/admin/kurul/create', 'Admin\KurulController@create');
$router->post('/admin/kurul/store', 'Admin\KurulController@store');
$router->get('/admin/kurul/edit/{id}', 'Admin\KurulController@edit');
$router->post('/admin/kurul/update/{id}', 'Admin\KurulController@update');
$router->post('/admin/kurul/delete/{id}', 'Admin\KurulController@delete');

// Lookup tables management (Admin only)
$router->get('/admin/lookup/unvan', 'Admin\KurulController@manageUnvan');
$router->post('/admin/lookup/unvan/store', 'Admin\KurulController@storeUnvan');
$router->post('/admin/lookup/unvan/update/{id}', 'Admin\KurulController@updateUnvan');
$router->post('/admin/lookup/unvan/delete/{id}', 'Admin\KurulController@deleteUnvan');

$router->get('/admin/lookup/kurul', 'Admin\KurulController@manageKurul');
$router->post('/admin/lookup/kurul/store', 'Admin\KurulController@storeKurul');
$router->post('/admin/lookup/kurul/update/{id}', 'Admin\KurulController@updateKurul');
$router->post('/admin/lookup/kurul/delete/{id}', 'Admin\KurulController@deleteKurul');

$router->get('/admin/lookup/gorev', 'Admin\KurulController@manageGorev');
$router->post('/admin/lookup/gorev/store', 'Admin\KurulController@storeGorev');
$router->post('/admin/lookup/gorev/update/{id}', 'Admin\KurulController@updateGorev');
$router->post('/admin/lookup/gorev/delete/{id}', 'Admin\KurulController@deleteGorev');
$router->get('/api/kurul/duties/{id}', 'Admin\KurulController@getDutiesByBoard');

// Künye (Masthead) management
$router->get('/admin/kunye', 'Admin\KunyeController@index');
$router->get('/admin/kunye/create', 'Admin\KunyeController@create');
$router->post('/admin/kunye/store', 'Admin\KunyeController@store');
$router->get('/admin/kunye/edit/{id}', 'Admin\KunyeController@edit');
$router->post('/admin/kunye/update/{id}', 'Admin\KunyeController@update');
$router->post('/admin/kunye/delete/{id}', 'Admin\KunyeController@delete');

$router->get('/admin/lookup/kunye-baslik', 'Admin\KunyeController@manageCategories');
$router->post('/admin/lookup/kunye-baslik/store', 'Admin\KunyeController@storeCategory');
$router->post('/admin/lookup/kunye-baslik/update/{id}', 'Admin\KunyeController@updateCategory');
$router->post('/admin/lookup/kunye-baslik/delete/{id}', 'Admin\KunyeController@deleteCategory');

// Dizin (Indexing) management
$router->get('/admin/dizin', 'Admin\DizinController@index');
$router->get('/admin/dizin/create', 'Admin\DizinController@create');
$router->post('/admin/dizin/store', 'Admin\DizinController@store');
$router->get('/admin/dizin/edit/{id}', 'Admin\DizinController@edit');
$router->post('/admin/dizin/update/{id}', 'Admin\DizinController@update');
$router->post('/admin/dizin/delete/{id}', 'Admin\DizinController@delete');

// Makale Esas (Article Guidelines) management
$router->get('/admin/makale-esas', 'Admin\MakaleEsasController@index');
$router->get('/admin/makale-esas/create', 'Admin\MakaleEsasController@create');
$router->post('/admin/makale-esas/store', 'Admin\MakaleEsasController@store');
$router->get('/admin/makale-esas/edit/{id}', 'Admin\MakaleEsasController@edit');
$router->post('/admin/makale-esas/update/{id}', 'Admin\MakaleEsasController@update');
$router->post('/admin/makale-esas/delete/{id}', 'Admin\MakaleEsasController@delete');

// Makale Sözleşme (Copyright Agreement) management
$router->get('/admin/makale-sozlesme', 'Admin\MakaleSozlesmeController@index');
$router->get('/admin/makale-sozlesme/create', 'Admin\MakaleSozlesmeController@create');
$router->post('/admin/makale-sozlesme/store', 'Admin\MakaleSozlesmeController@store');
$router->get('/admin/makale-sozlesme/edit/{id}', 'Admin\MakaleSozlesmeController@edit');
$router->post('/admin/makale-sozlesme/update/{id}', 'Admin\MakaleSozlesmeController@update');
$router->post('/admin/makale-sozlesme/delete/{id}', 'Admin\MakaleSozlesmeController@delete');

// Dergi Sayı Yönetimi (Journal Issues)
$router->get('/admin/dergi-tanim', 'Admin\DergiTanimController@index');
$router->get('/admin/dergi-tanim/create', 'Admin\DergiTanimController@create');
$router->post('/admin/dergi-tanim/store', 'Admin\DergiTanimController@store');
$router->get('/admin/dergi-tanim/edit/{id}', 'Admin\DergiTanimController@edit');
$router->post('/admin/dergi-tanim/update/{id}', 'Admin\DergiTanimController@update');
$router->post('/admin/dergi-tanim/delete/{id}', 'Admin\DergiTanimController@delete');

// Makale Yayınlama Sistemi (Online Articles)
$router->get('/admin/online-makale', 'Admin\OnlineMakaleController@index');
$router->get('/admin/online-makale/create', 'Admin\OnlineMakaleController@create');
$router->get('/admin/online-makale/publish/{id}', 'Admin\OnlineMakaleController@publish');
$router->post('/admin/online-makale/store', 'Admin\OnlineMakaleController@store');
$router->get('/admin/online-makale/edit/{id}', 'Admin\OnlineMakaleController@edit');
$router->post('/admin/online-makale/update/{id}', 'Admin\OnlineMakaleController@update');
$router->post('/admin/online-makale/delete/{id}', 'Admin\OnlineMakaleController@delete');

// Researcher Submissions
$router->get('/submissions', 'SubmissionController@index');
$router->get('/submissions/create', 'SubmissionController@create');
$router->post('/submissions/store', 'SubmissionController@store');
$router->get('/submissions/{id}', 'SubmissionController@show');
$router->post('/submissions/resubmit/{id}', 'SubmissionController@resubmit');
$router->post('/submissions/sign/{id}', 'SubmissionController@signAgreement');

// Hakem Süreci & Gönderiler
$router->get('/admin/submissions', 'Admin\SubmissionController@index');
$router->get('/admin/submissions/revision/{id}', 'Admin\SubmissionController@revision');
$router->post('/admin/submissions/revision/{id}', 'Admin\SubmissionController@sendRevisionRequest');
$router->get('/admin/submissions/approve/{id}', 'Admin\SubmissionController@approve');
$router->post('/admin/submissions/delete/{id}', 'Admin\SubmissionController@delete');

// Destek Talepleri
$router->get('/support', 'SupportController@index');
$router->get('/support/create', 'SupportController@create');
$router->post('/support/store', 'SupportController@store');

$router->get('/admin/support', 'Admin\SupportController@index');
$router->get('/admin/support/reply/{id}', 'Admin\SupportController@reply');
$router->post('/admin/support/reply/{id}', 'Admin\SupportController@sendReply');
$router->post('/admin/support/delete/{id}', 'Admin\SupportController@delete');

$router->get('/admin/debug/mail', 'DebugController@testMail');

// Forgot Password
$router->get('/sifremi-unuttum', 'AuthController@showForgotPassword');
$router->post('/sifremi-unuttum', 'AuthController@sendResetLink');
$router->get('/sifre-sifirla/{token}', 'AuthController@showResetPassword');
$router->post('/sifre-sifirla', 'AuthController@updatePassword');

return $router;