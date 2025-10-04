<?php

use Illuminate\Support\Facades\Route;
use Modules\Contacts\Http\Controllers\ContactsController;

Route::prefix('contacts')->group(function () {
    Route::get('/', [ContactsController::class, 'index']);      // GET /api/contacts/contacts
    Route::post('/', [ContactsController::class, 'store']);     // POST /api/contacts/contacts
    Route::get('/{contact}', [ContactsController::class, 'show']); // GET /api/contacts/contacts/{id}
    Route::put('/{contact}', [ContactsController::class, 'update']); // PUT /api/contacts/contacts/{id}
    Route::delete('/{contact}', [ContactsController::class, 'destroy']); // DELETE /api/contacts/contacts/{id}
});
