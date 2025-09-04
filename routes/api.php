<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controladores
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\ProyectoController;
use App\Http\Controllers\MiembroController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\EtapaController;
use App\Http\Controllers\TareaEtapaController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\AuthMaestroController;

use App\Models\Miembro;
use Illuminate\Support\Facades\Hash;

Route::get('/ping', function () {
    return response()->json([
        'message' => 'API funcionando correctamente 🚀'
    ]);
});


Route::get('/reset-password', function () {
    $u = \App\Models\Miembro::where('correo', 'heinerlandero@gmail.com')->first();
    $u->password = bcrypt('admin123');
    $u->save();
    return 'Password reset';
});

Route::get('/test-hash', function () {
    $m = Miembro::where('correo', 'heinerlandero@gmail.com')->first();

    // Hash nuevo
    $newHash = Hash::make('admin123');
    $m->password = $newHash;
    $m->save();

    // Verifica inmediatamente
    $check = Hash::check('admin123', $m->password);

    // Recarga desde DB
    $m2 = Miembro::where('correo', 'heinerlandero@gmail.com')->first();
    $check2 = Hash::check('admin123', $m2->password);

    return [
        'new_hash' => $newHash,
        'check_in_memory' => $check,
        'check_after_reload' => $check2,
        'stored_password' => $m2->password,
    ];
});



Route::post('/registro-maestro', [AuthMaestroController::class, 'registro']);
Route::post('/login-maestro', [AuthMaestroController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me-maestro', [AuthMaestroController::class, 'me']);
    Route::post('/logout-maestro', [AuthMaestroController::class, 'logout']);
});


//
// CLIENTES
//





Route::post('/miembros/{id}/cambiar-password', [MiembroController::class, 'cambiarPassword']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    // Aquí puedes agregar tus rutas protegidas
    // Route::get('/proyectos', [ProyectoController::class, 'index']);
    Route::get('/clientes', [ClienteController::class, 'index']);
    Route::post('/clientes', [ClienteController::class, 'store']);
    Route::get('/clientes/{id}', [ClienteController::class, 'show']);
    Route::put('/clientes/{id}', [ClienteController::class, 'update']);
    Route::delete('/clientes/{id}', [ClienteController::class, 'destroy']);
    Route::get('/clientes/{id}/proyectos', [ProyectoController::class, 'porCliente']);

    //
    // PROYECTOS
    //
    Route::get('/proyectos', [ProyectoController::class, 'index']);
    Route::post('/proyectos', [ProyectoController::class, 'store']);
    Route::get('/proyectos/{id}', [ProyectoController::class, 'show']);
    Route::put('/proyectos/{id}', [ProyectoController::class, 'update']);
    Route::delete('/proyectos/{id}', [ProyectoController::class, 'destroy']);
    Route::get('/proyectos/{id}/miembros', [ProyectoController::class, 'miembros']);
    Route::post('/proyectos/{id}/miembros', [ProyectoController::class, 'asignarMiembro']);
    Route::put('/proyectos/{id}/miembros/{miembroId}', [ProyectoController::class, 'actualizarRolMiembro']);
    Route::delete('/proyectos/{id}/miembros/{miembroId}', [ProyectoController::class, 'quitarMiembro']);
    Route::post('/proyectos/{id}/asignar-encargado', [ProyectoController::class, 'asignarEncargado']);

    // Relación proyecto - miembros
    Route::get('/proyectos/{id}/miembros', [ProyectoController::class, 'miembros']);
    Route::post('/proyectos/{id}/miembros', [ProyectoController::class, 'asignarMiembro']);

    //
    // MIEMBROS
    //
    Route::get('/miembros', [MiembroController::class, 'index']);
    Route::post('/miembros', [MiembroController::class, 'store']);
    Route::get('/miembros/{id}', [MiembroController::class, 'show']);
    Route::put('/miembros/{id}', [MiembroController::class, 'update']);
    Route::delete('/miembros/{id}', [MiembroController::class, 'destroy']);

    //
    // TAREAS
    //
    Route::get('/tareas', [TareaController::class, 'index']);
    Route::post('/tareas', [TareaController::class, 'store']);
    Route::get('/tareas/{id}', [TareaController::class, 'show']);
    Route::put('/tareas/{id}', [TareaController::class, 'update']);
    Route::delete('/tareas/{id}', [TareaController::class, 'destroy']);
    Route::post('/tareas/{id}/asignar-miembro', [TareaController::class, 'asignarMiembro']);

    // Tareas filtradas
    Route::get('/proyectos/{id}/tareas', [TareaController::class, 'porProyecto']);
    Route::get('/etapas/{id}/tareas', [TareaController::class, 'porEtapa']);

    // Mover tarea entre etapas (estilo Kanban)
    Route::post('/tareas/{id}/mover-etapa', [TareaEtapaController::class, 'mover']);

    //
    // ETAPAS (Kanban Columns)
    //
    Route::get('/etapas', [EtapaController::class, 'index']);
    Route::post('/etapas', [EtapaController::class, 'store']);
    Route::get('/etapas/{id}', [EtapaController::class, 'show']);
    Route::put('/etapas/{id}', [EtapaController::class, 'update']);
    Route::delete('/etapas/{id}', [EtapaController::class, 'destroy']);
    Route::post('/etapas/reordenar', [EtapaController::class, 'reordenar']);

    // Etapas de un proyecto (Kanban por proyecto)
    // Route::get('/proyectos/{id}/etapas', [EtapaController::class, 'porProyecto']);
    Route::get('/proyectos/{id}/etapas', [EtapaController::class, 'etapasPorProyecto']);
    Route::get('/proyectos/{proyectoId}/etapas', [EtapaController::class, 'etapasPorProyecto']);
});
