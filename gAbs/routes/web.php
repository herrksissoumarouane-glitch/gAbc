<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ComplexeController;
use App\Http\Controllers\EfpController;
use App\Http\Controllers\FiliereController;
use App\Http\Controllers\GroupeController;
use App\Http\Controllers\FormateurController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\StagiaireController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\CustomPasswordResetController;


// Show the custom password reset form
Route::get('/custom-reset-password', [CustomPasswordResetController::class, 'showResetForm'])
    ->name('password.reset.view');

// Handle the form submission
Route::post('/custom-reset-password', [CustomPasswordResetController::class, 'updatePassword'])
    ->name('password.custom.update');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::controller(HomeController::class)->group(function(){
    Route::get('/', 'index')->name('/');
    Route::get('/home', 'index')->name('home');
    });


    Route::controller(RoleController::class)->group(function(){

        Route::get('/all/permission' , 'AllPermission')->name('all.permission');
        Route::get('/add/permission' , 'AddPermission')->name('add.permission');
        Route::post('/store/permission' , 'StorePermission')->name('store.permission');
        Route::post('/store/permission' , 'StorePermission')->name('store.permission');
        Route::get('/edit/permission/{id}' , 'EditPermission')->name('edit.permission');
        Route::post('/update/permission' , 'UpdatePermission')->name('update.permission');
        Route::get('/delete/permission/{id}' , 'DeletePermission')->name('delete.permission');
       

        Route::get('/all/roles' , 'AllRoles')->name('all.roles');
        Route::get('/add/roles' , 'AddRoles')->name('add.roles');
        Route::post('/store/roles' , 'StoreRoles')->name('store.roles');
        Route::get('/edit/roles/{id}' , 'EditRoles')->name('edit.roles');
        Route::post('/update/roles' , 'UpdateRoles')->name('update.roles');
        Route::get('/delete/roles/{id}' , 'DeleteRoles')->name('delete.roles');

        // add role permission
        Route::get('/add/roles/permission' , 'AddRolesPermission')->name('add.roles.permission');
        Route::post('/role/permission/store' , 'RolePermissionStore')->name('role.permission.store');
        Route::get('/all/roles/permission' , 'AllRolesPermission')->name('all.roles.permission');
        Route::get('/admin/edit/roles/{id}' , 'AdminRolesEdit')->name('admin.edit.roles');
        Route::post('/admin/roles/update/{id}' , 'AdminRolesUpdate')->name('admin.roles.update');
        Route::get('/admin/delete/roles/{id}' , 'AdminRolesDelete')->name('admin.delete.roles');

    });


    Route::controller(AdminController::class)->group(function(){

        Route::get('/all/admin' , 'AllAdmin')->name('all.admin');
       
       });


    Route::controller(ComplexeController::class)->group(function(){

        Route::get('/all/complexes' , 'AllComplexes')->name('all.complexes');
    
    });

    Route::controller(EfpController::class)->group(function(){

        Route::get('/all/efps' , 'AllEfps')->name('all.efps');

        Route::post('/all/efps' , 'AllEfps')->name('all.efps');
        Route::post('/efps/import/{complexe_id}' , 'import')->name('efps.import');
        
        
    });
    
    Route::controller(FiliereController::class)->group(function(){

        Route::get('/filieres' ,'index')->name('filieres.index');
        Route::post('/groupes/import','import')->name('groupes.import');
        
    });


    Route::controller(GroupeController::class)->group(function(){

        Route::get('/groups', 'index')->name('groupes.index');
        Route::get('/get-efps/{complexId}', 'getEfps');
        Route::get('/get-filieres/{efpId}/{complexId}', 'getFilieres');
        Route::get('/get-groups/{filiereId}', 'getGroups');
        
    });


    Route::controller(FormateurController::class)->group(function(){

        Route::get('/formateurs-groupes', 'index')->name('formateurs.groupes.index');
        Route::get('/formateurs-groupes/{id}', 'affecterGroupes')->name('formateurs.affecter.groupes');
        Route::post('/formateurs/groupes/import', 'import')->name('formateurs.groupes.import');
        Route::get('/get-complexes/', 'getComplexes');
        Route::get('/get-efps/{complexId}/{idFormateur}', 'getEfps');
        Route::get('/get-groups-by-complexe/{complexeId}', 'getGroupsByComplexe');
        Route::get('/get-filieres/{efpId}', 'getFilieres');
        Route::get('/get-groupes/{filiereId}/{idFormateur}', 'getGroupes');
        Route::post('/Affect-groups/', 'AffectGroups')->name('Affect.groups');
        
    });

    Route::controller(StagiaireController::class)->group(function(){
        Route::post('/stagiaires/import', 'import')->name('stagiaires.import');
        Route::get('/get-stagiaires/{groupId}', 'getByGroup');
        Route::get('/stagiaires',  'index')->name('stagiaires');
        Route::get('/stagiaires/by-group/{groupId}','stagiaireByGroup');
        Route::get('/stagiaires/by-groupe/{groupId}','stagiaireByGroupe');
    });

    Route::controller(AbsenceController::class)->group(function(){

        Route::get('/absences', 'index')->name('absences.index');
        Route::get('/get-efps/{complexId}', 'getEfps');
        Route::get('/get-filieres/{efpId}/{complexId}', 'getFilieres');
        Route::get('/get-groups/{filiereId}', 'getGroups');
        Route::post('/add/absences', 'addAbsences')->name('add.absences');;
        
    });

    Route::controller(DownloadController::class)->group(function(){

        Route::get('/downloadGroups' , 'index')->name('download.groups');
        Route::post('/download' , 'Download')->name('download');
       
       });

});







