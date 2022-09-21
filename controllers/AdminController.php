<?php

namespace Controllers;

use Model\AdminCita;
use MVC\Router;

class AdminController{

    public static function index(Router $router){ //Render: admin/index.php

        //Needs to be an admin
        isAdmin();

        $citas = [];
        $alertas = [];

        //Get date from url, else get date from today
        $fecha = $_GET['fecha'] ?? date('Y-m-d');

        //Returns 3 position array defined by "-"
        $fechaCheck = explode('-', $fecha);

        //If array exist...
        if(isset($fechaCheck[1], $fechaCheck[2], $fechaCheck[0])){

            //Is a valid date? [month] [day] [year]
            if(!checkdate($fechaCheck[1], $fechaCheck[2], $fechaCheck[0])){
                header('Location: /404');
            }

            //SQL custom query
            $consulta = "SELECT citas.id, citas.fecha, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
            $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
            $consulta .= " FROM citas  ";
            $consulta .= " LEFT OUTER JOIN usuarios ";
            $consulta .= " ON citas.usuarioid=usuarios.id  ";
            $consulta .= " LEFT OUTER JOIN citas_servicios ";
            $consulta .= " ON citas_servicios.citaid=citas.id ";
            $consulta .= " LEFT OUTER JOIN servicios ";
            $consulta .= " ON servicios.id=citas_servicios.servicioid ";
            $consulta .= " WHERE fecha =  '${fecha}' ";
            $consulta .= " ORDER BY fecha ASC ";

            //Query the custom SQL query ($consulta)
            $citas = AdminCita::SQL($consulta);

        }else{
            //Shows an alert
            $admincita = new AdminCita();
            $alertas = $admincita->setAlerta('error','Introduce una fecha valida');
            $alertas = $admincita->getAlertas();
        }

        //If get deleted on url, shows an alert
        if(isset($_GET['deleted'])){
            $admincita = new AdminCita();
            $alertas = $admincita->setAlerta('exito','Cita anulada correctamente');
            $alertas = $admincita->getAlertas();
        }

        //Render the view
        $router->render('admin/index', [
            'nombre' => $_SESSION['nombre'],
            'citas' => $citas,
            'fecha' => $fecha,
            'alertas' => $alertas
        ]);
    }
}