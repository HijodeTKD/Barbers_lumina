<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\AdminController;
use Controllers\APIController;
use Controllers\CitaController;
use Controllers\LoginController;
use Controllers\ServicioController;
use MVC\Router;

$router = new Router();

//Login/Logout
$router->get('/', [LoginController::class , 'login']);
$router->post('/', [LoginController::class , 'login']);
$router->get('/logout', [LoginController::class , 'logout']);

//Recover/Forget password
$router->get('/olvide-password', [LoginController::class , 'olvide']);
$router->post('/olvide-password', [LoginController::class , 'olvide']);
$router->get('/recuperar', [LoginController::class , 'recuperar']);
$router->post('/recuperar', [LoginController::class , 'recuperar']);

//Create account
$router->get('/crear-cuenta', [LoginController::class , 'crear']);
$router->post('/crear-cuenta', [LoginController::class , 'crear']);

//Confirm account
$router->get('/confirmar-cuenta', [LoginController::class , 'confirmar']);
$router->get('/mensaje', [LoginController::class , 'mensaje']);

//Private area, must be logged
$router->get('/cita', [CitaController::class , 'index']);
$router->get('/mis-citas', [CitaController::class , 'miscitas']);
$router->post('/mis-citas', [CitaController::class , 'miscitas']);
$router->get('/admin', [AdminController::class , 'index']);

//Cita-API
$router->get('/api/servicios', [APIController::class, 'index']); //No es una ruta para visitar, la utilizamos para exportar los datos a .json
$router->post('/api/citas', [APIController::class, 'guardar']); //Submit desde citas a traves de formdata
$router->post('/api/eliminar', [APIController::class, 'eliminar']); //Submit desde citas a traves de formdata

//"Servicios" CRUD 
$router->get('/servicios', [ServicioController::class, 'index']);
$router->get('/servicios/crear', [ServicioController::class, 'crear']); //Lee los datos de la db
$router->post('/servicios/crear', [ServicioController::class, 'crear']); //Escribe los datos en la db
$router->get('/servicios/actualizar', [ServicioController::class, 'actualizar']); //Lee los datos de la db
$router->post('/servicios/actualizar', [ServicioController::class, 'actualizar']); //escribe los datos de la db
$router->post('/servicios/eliminar', [ServicioController::class, 'eliminar']); //escibe los datos de la db

//Checks and validates routes, if exist execute controller functions
$router->comprobarRutas();