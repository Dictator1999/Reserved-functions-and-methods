<?php
Route::get("/language/{id?}","Owner\LanguageController@index")->name("language");
Route::get("/language/create/new","Owner\LanguageController@create")->name("language.create");
Route::post("/language/new-key-save","Owner\LanguageController@newkeysave")->name("language.keysave");
Route::post("/language/new-key-exitchk","Owner\LanguageController@newkeyexischk")->name("language.keyexitchk");

Route::get('/key-list', 'Owner\LanguageController@geKeytDatatable')->name('key.datatable.list');
Route::post('/key-list-store', 'Owner\LanguageController@keyValStore')->name('key.list.store');
Route::post('/language-default-cng', 'Owner\LanguageController@changeDefaultLang')->name('language.default.cng');
Route::post("language/val/change","Owner\LanguageController@changeValue")->name("language.val.change");