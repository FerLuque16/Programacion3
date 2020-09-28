<?php
require_once './Clases/Usuario.php';
require_once './Clases/Archivos.php';
require_once './Clases/Profesor.php';
require_once './Clases/Materia.php';
require_once './Clases/Asignacion.php';

    

    $metodo = $_SERVER['REQUEST_METHOD'];
    $path=$_SERVER['PATH_INFO'];

    switch ($path) {
        case '/usuario':

            Usuario::PeticionesUsuario($metodo);            
            break;
        
        case '/profesor':

            Profesor::PeticionesProfesor($metodo);               
            break;
                
        case '/materia':
            Materia::PeticionesMateria($metodo);
            break;
        
        case '/asignacion':
            Asignacion::PeticionesAsignacion($metodo);
        break;

        default:
            //Materia::PeticionesMateria($metodo);

            # code...
            break;
    }


?>