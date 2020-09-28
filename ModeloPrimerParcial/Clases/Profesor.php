<?php
    require_once __DIR__.'./Archivos.php';

    class Profesor extends ManejadorArchivos
    {

        public $nombre;
        public $legajo;

        public function __construct($nombre, $legajo)
        {
            $this->nombre=$nombre;
            $this->legajo=$legajo;
        }

        public function __toString()
        {
            return $this->nombre.'*'.$this->legajo;
        }

        public function __get($name)
        {
            echo $this->$name;
        }

        public function __set($name, $value)
        {
            $this->$name = $value;
        }

        static function PeticionesProfesor($metodo)
        {
            switch ($metodo) 
            {
                case 'POST':
                    $nombre=$_POST['nombre']??'';
                    $legajo=$_POST['legajo']??'';

                    $profesor= new Profesor($nombre, $legajo);

                    $lista = array();

                    $listaProfesoresJSON=parent::LeerJSON('./Archivos/listaProfesoresJSON.json');

                    $listaProfesores=Profesor::CrearProfesorJSON($listaProfesoresJSON);

                    if(Profesor::ValidarProfesor($listaProfesores,$profesor))
                    {
                        array_push($lista,$profesor);

                        parent::GuardarJSON('./Archivos/listaProfesoresJSON.json',$lista);
    
                        parent::Serializar('./Archivos/listaProfesoresSerializadio.txt',$lista);
    
                        parent::GuardarArchivo('./Archivos/listaProfesores.txt',$profesor);
                    }
                    else
                    {
                        echo "Ya existe un profesor con el mismo legajo";
                    }

                   

                    break;
                
                case 'GET':

                    //$listaProfesores=parent::Deserializar('./Archivos/listaProfesoresSerializadio.txt');

                    //$listaProfesores=parent::LeerArchivo('./Archivos/listaProfesores.txt');

                    //$lista=Profesor::CrearProfesor($listaProfesores);

                    $listaJSON=parent::LeerJSON('./Archivos/listaProfesoresJSON.json');

                    $listaProfesores=Profesor::CrearProfesor($listaJSON);

                    Profesor::MostrarTodos($listaProfesores);
                    # code...
                    break; 

                default:
                    echo "Metodo no permitido";
                    # code...
                    break;
            }

        }

        static function CrearProfesor($listaDatosProfesor)
        {
            $listaProfesores=array();

            foreach ($listaDatosProfesor as $key => $value) 
            {
                
                $profesor = new Profesor($value[0], $value[1]);

                array_push($listaProfesores,$profesor);
                
            }

            return $listaProfesores;
        }

        static function CrearProfesorJSON($listaDatosProfesor)
        {
            $listaProfesores=array();

            foreach ($listaDatosProfesor as $value) 
            {
                
                $profesor = new Profesor($value->nombre, $value->legajo);

                array_push($listaProfesores,$profesor);
                
            }

            return $listaProfesores;
        }

        static function MostrarProfesor($profesor)
        {
            echo 'Nombre:'.$profesor->nombre.'||'.'Legajo:'.$profesor->legajo.PHP_EOL;
        }

        static function MostrarTodos($listaProfesores)
        {
            foreach ($listaProfesores as $value) 
            {
                Profesor::MostrarProfesor($value);
            }
            
        }

        static function ValidarProfesor($listaProfesores, $profesor)
        {
            $esValido=true;

            foreach ($listaProfesores as $value) 
            {
                if($profesor->legajo == $value->legajo)
                {
                    $esValido=false;
                }
            }

            return $esValido;
        }
    }

?>