<?php

/*That controller query and sends data via json to non-visiting urls 
Javascript fetch those url to admin the data. */

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController{

    //When javascript fetch method GET 'http://localhost:3000/api/servicios' then calls that function

    public static function index(){ //From cita index

        $servicios = Servicio::all(); //Query all servicios
        echo json_encode($servicios); //Encode all data to json, now javascript can read

    }

    //When javascript fetch method POST 'http://localhost:3000/api/citas' - thought formdata (Like a submit) then calls that function

    public static function guardar(){
        //That commented code can be used for check the data sended from JS. 
        // $respuesta = [
        //     'datos' => $_POST //Key: Datos Value: POST from app.js formdata
        // ];
        // debuguear($respuesta);

        $id = $_SESSION['id'];
        $numerocitas = Cita::findValues('usuarioid',$id);

        //Max 3 citas for user
        if(count($numerocitas) < 3){
            //Create a new cita with $_POST values sended by js
            $cita = new Cita($_POST);
            $resultado = $cita->guardar();

            $id = $resultado['id']; //citaservicio id = cita id
            
            //Obtain and divide servicios by ','
            $idServicios = explode(',', $_POST['servicios']);

            //Foreach servicio... 
            foreach($idServicios as $idServicio){

                //Create a key=>value array with citaid and servicioid. 
                $args = [
                    'citaid' => $id,
                    'servicioid' => $idServicio
                ];

                //Create a new object with args
                $citaServicio = new CitaServicio($args);
                //Save citaServicio
                $citaServicio->guardar();
            };

            //When cita is saved, return [true/false, id] and encode into json
            echo json_encode($resultado);

        }else{
            //If has 3 citas, return 3citasmax as result and fail the process
            $resultado = '3citasmax';
            echo json_encode($resultado);
        }
    }

    public static function eliminar(){ // When http://localhost:3000/api/eliminar ...
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $id = $_POST['id'];
            $cita = Cita::find($id);
            $cita->eliminar();
            
            //Url has a "?" then...
            if (false !== stripos($_SERVER['HTTP_REFERER'], "?")){
                header('Location:' . $_SERVER['HTTP_REFERER'] . "&deleted=true"); //Add other parameter with & and redirect
            }else if(false !== stripos($_SERVER['HTTP_REFERER'], "deleted=true")){
                header('Location:' . $_SERVER['HTTP_REFERER']); //Do nothing to url and redirect
            }else{
                header('Location:' . $_SERVER['HTTP_REFERER'] . "?deleted=true"); //Add parameter and redirect
            }
        }
    }
}