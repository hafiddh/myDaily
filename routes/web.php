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

// Route::get('/', 'Controller@index')->name('index');
Route::get('/', 'LoginController@hal_login')->name('index');

Route::post('/login', 'LoginController@login')->name('postLogin');
Route::get('/login', 'LoginController@hal_login')->name('login');

Route::post('/daftar/validate', 'LoginController@register')->name('postRegis');
Route::get('/resetpass', 'LoginController@resetpass')->name('resetpass');
Route::post('/login/lupapass', 'LoginController@lupapass')->name('postPassword');
Route::get('/login/verify/{token}', 'LoginController@verifyEmail')->name('login.verify');
Route::get('/login/verifyPass/{token}', 'LoginController@verifyPassword')->name('login.verify.pass');

Route::get('/logout', 'LoginController@logout')->name('logout');

Route::get('/kill-notif/{id}/{status}', 'Controller@kill_notif')->name('kill.notif');
Route::get('/kill-notif2/{id}/{status}', 'Controller@kill_notif2')->name('kill.notif2');

Route::get('/download-file/{id}', 'Controller@download_file')->name('download.file'); 
Route::get('/download-file-cpanel/{id}', 'Controller@download_file_cpanel')->name('download.file.cpanel'); 
// Route::get('pass', 'LoginController@bcrypt_gen');

// Admin
Route::group(['middleware'=> ['auth' , 'ceklevel:2']], function () {
            
    Route::get('/admin', 'AdminController@index')->name('admin.index');
    Route::get('/admin/organisasi', 'AdminController@opd')->name('admin.opd');
    Route::get('/admin/dataset/baru', 'AdminController@dataset_baru')->name('admin.dataset.baru');
    Route::get('/admin/dataset/tolak', 'AdminController@dataset_tolak')->name('admin.dataset.tolak');
    Route::get('/admin/dataset/terima', 'AdminController@dataset_terima')->name('admin.dataset.terima');
    Route::get('/admin/dataset/pembaruan', 'AdminController@dataset_pembaruan')->name('admin.dataset.pembaruan');
    Route::get('/admin/sektoral', 'AdminController@sektoral')->name('admin.sektoral');
    Route::get('/admin/infografik', 'AdminController@infografik')->name('admin.infografik');
    Route::get('/admin/log', 'AdminController@log')->name('admin.log');
    Route::get('/admin/profil', 'AdminController@profil')->name('admin.profil');
    Route::get('/admin/highlight', 'AdminController@highlight')->name('admin.highlight');
    
    Route::get('/admin/edit_dataset', 'DatasetController@edit_dataset')->name('admin.edit.dataset'); 
    Route::post('/admin/new_dataset', 'DatasetController@new_dataset')->name('admin.new.dataset'); 
    Route::post('/admin/post_edit_dataset', 'DatasetController@post_edit_dataset')->name('admin.postedit.dataset'); 
    Route::get('/admin/del_dataset', 'DatasetController@del_dataset')->name('admin.del.dataset');
    Route::get('/admin/detail_dataset', 'DatasetController@detail_dataset')->name('admin.detail.dataset'); 
    Route::post('/admin/post_tolak_dataset', 'DatasetController@post_tolak_dataset')->name('admin.posttolak.dataset'); 
    Route::post('/admin/post_proses_dataset', 'DatasetController@post_proses_dataset')->name('admin.postproses.dataset'); 
    Route::get('/admin/dataset_teruskan', 'DatasetController@dataset_teruskan')->name('admin.dataset.teruskan');
        
    Route::get('/admin/edit_infografik', 'AdminProsesController@edit_infografik')->name('admin.edit.infografik'); 
    Route::get('/admin/lihat_infografik', 'AdminProsesController@lihat_infografik')->name('admin.lihat.infografik'); 
    Route::post('/admin/new_infografik', 'AdminProsesController@new_infografik')->name('admin.new.infografik'); 
    Route::post('/admin/post_edit_infografik', 'AdminProsesController@post_edit_infografik')->name('admin.postedit.infografik'); 
    Route::get('/admin/del_infografik', 'AdminProsesController@del_infografik')->name('admin.del.infografik');

    Route::get('/admin/edit_sektoral', 'AdminProsesController@edit_sektoral')->name('admin.edit.sektoral'); 
    Route::post('/admin/new_sektoral', 'AdminProsesController@new_sektoral')->name('admin.new.sektoral'); 
    Route::post('/admin/post_edit_sektoral', 'AdminProsesController@post_edit_sektoral')->name('admin.postedit.sektoral'); 
    Route::get('/admin/del_sektoral', 'AdminProsesController@del_sektoral')->name('admin.del.sektoral');

    Route::get('/admin/edit_opd', 'AdminProsesController@edit_opd')->name('admin.edit.opd'); 
    Route::post('/admin/new_opd', 'AdminProsesController@new_opd')->name('admin.new.opd'); 
    Route::post('/admin/post_edit_opd', 'AdminProsesController@post_edit_opd')->name('admin.postedit.opd'); 
    Route::get('/admin/del_opd', 'AdminProsesController@del_opd')->name('admin.del.opd');
    
    Route::get('/admin/del_highlight', 'AdminProsesController@del_highlight')->name('admin.del.highlight');
    Route::post('/admin/tambah_highlight1', 'AdminProsesController@tambah_highlight1')->name('admin.highlight.tambah1');
    Route::post('/admin/tambah_highlight2', 'AdminProsesController@tambah_highlight2')->name('admin.highlight.tambah2');
    Route::get('/admin/high_dataset.cari', 'AdminProsesController@high_dataset_cari')->name('high_dataset.cari');
    Route::get('/admin/high_info.cari', 'AdminProsesController@high_info_cari')->name('high_infografik.cari');
    
    Route::post('/admin/post_edit_profil', 'AdminProsesController@post_edit_profil')->name('admin.postedit.profil'); 

    // Table API
    
    Route::get('/admin/get_opd', 'AdminController@get_opd')->name('admin.get.opd'); 
    Route::get('/admin/get_infografik', 'AdminController@get_infografik')->name('admin.get.infografik'); 
    Route::get('/admin/get_dataset_baru', 'AdminController@get_dataset_baru')->name('admin.get.dataset.baru'); 
    Route::get('/admin/get_dataset_tolak', 'AdminController@get_dataset_tolak')->name('admin.get.dataset.tolak'); 
    Route::get('/admin/get_dataset_terima', 'AdminController@get_dataset_terima')->name('admin.get.dataset.terima'); 
    Route::get('/admin/get_dataset', 'AdminController@get_dataset')->name('admin.get.dataset'); 
    Route::get('/admin/get_sektoral', 'AdminController@get_sektoral')->name('admin.get.sektoral'); 
    Route::get('/admin/get_infografik', 'AdminController@get_infografik')->name('admin.get.infografik'); 
    Route::get('/admin/get_log', 'AdminController@get_log')->name('admin.get.log'); 
    Route::get('/admin/get_highlight1', 'AdminController@get_highlight1')->name('admin.get.highlight1'); 
    Route::get('/admin/get_highlight2', 'AdminController@get_highlight2')->name('admin.get.highlight2'); 

});


// user
Route::group(['middleware'=> ['auth' , 'ceklevel:1']], function () {
            
    Route::get('/home', 'indexController@index')->name('web.index');
    Route::get('/opd/dataset_baru', 'OpdController@dataset_baru')->name('opd.dataset.baru');
    Route::get('/opd/dataset_tolak', 'OpdController@dataset_tolak')->name('opd.dataset.tolak');
    Route::get('/opd/dataset_terima', 'OpdController@dataset_terima')->name('opd.dataset.terima');
    Route::get('/opd/dataset/pembaruan', 'OpdController@dataset_pembaruan')->name('opd.dataset.pembaruan');

    Route::get('/opd/detail_dataset', 'OpdController@detail_dataset')->name('opd.detail.dataset'); 
    Route::get('/opd/del_dataset', 'OpdController@del_dataset')->name('opd.del.dataset');
    Route::get('/opd/edit_dataset', 'OpdController@edit_dataset')->name('opd.edit.dataset'); 
    Route::post('/opd/pembaruan_dataset', 'OpdController@post_pembaruan_dataset')->name('opd.pembaruan.dataset'); 
    Route::get('/opd/pembaruan_dataset_cari', 'OpdController@pembaruan_dataset_cari')->name('dataset.pembaruan.cari'); 
    Route::post('/opd/new_dataset', 'OpdController@new_dataset')->name('opd.new.dataset');
    Route::post('/opd/post_edit_dataset', 'OpdController@post_edit_dataset')->name('opd.postedit.dataset'); 
    Route::post('/opd/post_edit_dataset_tolak', 'OpdController@post_edit_dataset_tolak')->name('opd.postedittolak.dataset'); 

    // Table API
    Route::get('/opd/get_dataset_baru', 'OpdController@get_dataset_baru')->name('opd.get.dataset.baru'); 
    Route::get('/opd/get_dataset_tolak', 'OpdController@get_dataset_tolak')->name('opd.get.dataset.tolak'); 
    Route::get('/opd/get_dataset_terima', 'OpdController@get_dataset_terima')->name('opd.get.dataset.terima'); 

});