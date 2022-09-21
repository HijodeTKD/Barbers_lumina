<?php 

namespace Controllers;

use Model\Cita;
use Model\MiCita;
use MVC\Router;

//La que renderiza la vista de cita, obtiene de session algunos datos

class CitaController{
    public static function index(Router $router){ // *View:cita/index.php - Part of this view is created by JS

        //IsAuth?
        estaAutenticado();

        //Render view
        $router->render('/cita/index', [
            'id' => $_SESSION['id'] ?? '',
            'nombre' => $_SESSION['nombre'] ?? '',
        ]);
    }

    
    public static function miscitas(Router $router){ //*View:mis-citas/index.php - Part of this view is created by JS
        
        //IsAuth?
        estaAutenticado();

        $id = $_SESSION['id'];
        $fecha = date('Y-m-d');
        $alertas = [];
        
        //Show delete alert
        if(isset($_GET['deleted'])){
            $cita = new Cita();
            $alertas = $cita->setAlerta('exito','Cita anulada correctamente');
            $alertas = $cita->getAlertas();
        }

        //Query citas with date from today
        $consulta = "SELECT citas.id, citas.fecha, citas.hora, ";
        $consulta .= " servicios.nombre as servicio, servicios.precio  ";
        $consulta .= " FROM citas  ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON citas.usuarioid=usuarios.id  ";
        $consulta .= " LEFT OUTER JOIN citas_servicios ";
        $consulta .= " ON citas_servicios.citaid=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citas_servicios.servicioid ";
        $consulta .= " WHERE usuarioid =  '${id}' ";
        $consulta .= " and fecha >= '${fecha}' ";
        $consulta .= " ORDER BY fecha ASC ";

        $citas = MiCita::SQL($consulta);

        //Render view
        $router->render('/cita/mis-citas', [
            'nombre' => $_SESSION['nombre'] ?? '',
            'citas' => $citas,
            'alertas' => $alertas
        ]);
    }
}
