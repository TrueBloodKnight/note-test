<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;

/**
 * @OA\Info(
 *     title="Notes API",
 *     version="1.0.0",
 *     description="API for managing notes",
 *     @OA\Contact(
 *         email="your-truebloodknight@gmail.com"
 *     )
 * )
 */

Route::apiResource('notes', NoteController::class);
