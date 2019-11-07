<?php

// Guest Routes...
Route::view('/', 'core::guest.welcome');

Route::prefix('auth')->group(function () {
    Auth::routes([
        'register' => config('core.user_registration'),
        'reset' => true,
        'confirm' => false,
        'verify' => true,
    ]);
});

// Common Routes...
Route::middleware('auth', 'core.enabled')->prefix('common')->name('common.')->group(function () {
    Route::post('sidebar-toggle', 'Common\SidebarController@toggle')->name('sidebar.toggle');
});

// Backend Routes...
Route::middleware('auth', 'core.enabled', 'core.backend')
->prefix(config('core.backend_routes_prefix'))->name('backend.')->group(function () {
    Route::middleware('verified')->group(function () {
        Route::view('/', 'core::backend.index')->name('index');
    });

    // Personal Settings Routes...
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::prefix('account')->name('account')->group(function () {
            Route::get('/', 'Backend\Settings\AccountController@edit');
            Route::patch('/', 'Backend\Settings\AccountController@update');
        });
    });

    Route::middleware('verified', 'core.permission')->group(function () {
        // Security Routes...
        Route::prefix('security')->name('security.')->group(function () {
            // Permission Routes...
            Route::prefix('permission')->name('permission.')->group(function () {
                Route::get('index', 'Backend\Security\PermissionController@index')->name('index');
                Route::get('index/data', 'Backend\Security\PermissionController@indexData')->name('index.data');
            });

            // Role Routes...
            Route::prefix('role')->name('role.')->group(function () {
                Route::get('index', 'Backend\Security\RoleController@index')->name('index');
                Route::get('index/data', 'Backend\Security\RoleController@indexData')->name('index.data');
                Route::get('create', 'Backend\Security\RoleController@create')->name('create');
                Route::post('store', 'Backend\Security\RoleController@store')->name('store');
                Route::get('edit/{role}', 'Backend\Security\RoleController@edit')->name('edit');
                Route::patch('update/{role}', 'Backend\Security\RoleController@update')->name('update');
                Route::delete('destroy/{role}', 'Backend\Security\RoleController@destroy')->name('destroy');
            });

            // Account Routes...
            Route::prefix('account')->name('account.')->group(function () {
                Route::get('index', 'Backend\Security\AccountController@index')->name('index');
                Route::get('index/data', 'Backend\Security\AccountController@indexData')->name('index.data');
                Route::get('create', 'Backend\Security\AccountController@create')->name('create');
                Route::post('store', 'Backend\Security\AccountController@store')->name('store');
                Route::get('edit/{account}', 'Backend\Security\AccountController@edit')->name('edit');
                Route::patch('update/{account}', 'Backend\Security\AccountController@update')->name('update');
                Route::delete('destroy/{account}', 'Backend\Security\AccountController@destroy')->name('destroy');
            });
        });
    });
});

// Frontend Routes...
Route::middleware('auth', 'core.enabled', 'core.frontend')
->prefix(config('core.frontend_routes_prefix'))->name('frontend.')->group(function () {
    Route::middleware('verified')->group(function () {
        Route::view('/', 'core::frontend.index')->name('index');
    });

    // Personal Settings Routes...
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::prefix('account')->name('account')->group(function () {
            Route::get('/', 'Frontend\Settings\AccountController@edit');
            Route::patch('/', 'Frontend\Settings\AccountController@update');
        });
    });
});
