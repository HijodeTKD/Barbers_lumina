<?php 

namespace Controllers;

use Model\Servicio;
use MVC\Router;

class ServicioController{
    public static function index(Router $router){ //*View:servicios/index

        isAdmin();

        $alertas = [];
        $servicios = Servicio::all();
        
        //Alerts when CRUD actions

        if(isset($_GET['servicio_creado'])){
            $servicio = new Servicio;
            $alertas = $servicio->setAlerta('exito','Servicio creado correctamente');
            $alertas = $servicio->getAlertas();
        }
        if(isset($_GET['servicio_actualizado'])){
            $servicio = new Servicio;
            $alertas = $servicio->setAlerta('exito','Servicio actualizado correctamente');
            $alertas = $servicio->getAlertas();
        }
        if(isset($_GET['servicio_eliminado'])){
            $servicio = new Servicio;
            $alertas = $servicio->setAlerta('exito','Servicio eliminado correctamente');
            $alertas = $servicio->getAlertas();
        }

        //Render view
        $router->render('servicios/index', [
            'nombre' => $_SESSION['nombre'],
            'servicios' => $servicios,
            'alertas' => $alertas
        ]);
    }

    public static function crear(Router $router){ //*View: /servicios/crear

        isAdmin();

        $alertas = [];
        $servicio = new Servicio;

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $servicio->sincronizar($_POST); //POST values to object
            $alertas = $servicio->validar();

            if(empty($alertas)){
                $servicio->guardar();

                header('Location: /servicios?'.'servicio_creado');
            }
        }

        $router->render('servicios/crear', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio ,
            'alertas' => $alertas
        ]);
    }

    public static function actualizar(Router $router){ //*View:/servicios/actualizar

        isAdmin();

        //Valid id from get? 
        if(!is_numeric($_GET['id'])) return;
        $servicio = Servicio::find($_GET['id']);
        $alertas = [];


        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $servicio->sincronizar($_POST);//POST values to object
            $alertas = $servicio->validar();

            if(empty($alertas)){
                $servicio->guardar();
                header('Location: /servicios?'.'servicio_actualizado');
            }
        }

        $router->render('servicios/actualizar', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function eliminar(){ //Buton POST eliminar

        isAdmin();

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = $_POST['id'];
            $servicio = Servicio::find($id);
            $servicio->eliminar();

            header('Location: /servicios?'.'servicio_eliminado');
        }
    }
}