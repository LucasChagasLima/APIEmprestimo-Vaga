<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\EmprestimoController;



// Rota para retornar as instituições disponíveis (GET)
Route::get('/instituicoes', [EmprestimoController::class, 'getInstituicoes']);

// Rota para retornar os convênios disponíveis (GET)
Route::get('/convenios', [EmprestimoController::class, 'getConvenios']);

// Rota para realizar a simulação de emprestimo (POST)
Route::post('/simular', [EmprestimoController::class, 'simularEmprestimo']);
