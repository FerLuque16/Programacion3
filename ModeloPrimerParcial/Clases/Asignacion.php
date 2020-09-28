<?php
    require_once __DIR__.'./Materia.php';
    require_once __DIR__.'./Profesor.php';
    require_once __DIR__.'./Archivos.php';

    class Asignacion extends ManejadorArchivos
    {
        public $legajoProfesor;
        public $idMateria;
        public $turno;

        public function __construct($legajo,$turno,$id)
        {   
            $this->legajoProfesor=$legajo;
            $this->idMateria=$id;
            $this->turno=$turno;
            
        }

        public function __get($name)
        {
            echo $this->$name;
        }

        public function __set($name, $value)
        {
            $this->$name = $value;
        }

        static function PeticionesAsignacion($metodo)
        {
            switch ($metodo) {
                case 'POST':
                    $legajo=$_POST['legajo']??'';
                    $idMateria=$_POST['id']??0;
                    $turno=$_POST['turno']??'';


                    $asignacion=new Asignacion($legajo, $turno, $idMateria);

                    $lista=array();

                    $listaAsignacionesJSON=parent::LeerJSON('./Archivos/materias-profesores.json');

                    $listaAsignaciones=Asignacion::CrearAsignacionJSON($listaAsignacionesJSON);

                    if(Asignacion::ValidarAsignacion($listaAsignaciones,$asignacion))
                    {
                        array_push($lista,$asignacion);

                        parent::GuardarJSON('./Archivos/materias-profesores.json',$lista);

                        echo "Hola";
                    }
                    else
                    {
                        echo "La asignacion no es valida";
                    }


                    break;
                
                case 'GET':
                    break;
                default:
                    # code...
                    break;
            }
        }


        static function CrearAsignacionJSON($listaDatosAsignacion)
        {
            $listaAsignaciones=array();
            
            foreach ($listaDatosAsignacion as $value) 
            {
                $asignacion = new Asignacion($value->legajoProfesor, $value->turno, $value->idMateria);

                array_push($listaAsignaciones, $asignacion);
                    
            }
            
            return $listaAsignaciones;
        }


        static function ValidarProfesorMateria($asignacion)
        {
            $listaMateriasJSON = parent::LeerJSON('./Archivos/listaMateriasJSON.json');
            $listaProfesoresJSON = parent::LeerJSON('./Archivos/listaProfesoresJSON.json');

            $listaMaterias = Materia::CrearMateriaJSON($listaMateriasJSON);           
            $listaProfesores = Profesor::CrearProfesorJSON($listaProfesoresJSON);

            $asignacionValida=false;
            $materiaValida=false;
            $profesorValido=false;

            foreach ($listaProfesores as $value) 
            {
                if($asignacion->legajoProfesor == $value->legajo)
                {
                    $profesorValido=true;
                    break;
                }
            }

            foreach ($listaMaterias as $value) 
            {
                if($asignacion->idMateria == $value->id)
                {
                    $materiaValida=true;
                    break;
                }
            }

            if($profesorValido==true && $materiaValida==true)
            {
                $asignacionValida=true;
            }

            return $asignacionValida;


        }

        static function ValidarAsignacion($listaAsignaciones, $asignacion)
        {
            $esValido=true;

            //$rta=array();


            if(Asignacion::ValidarProfesorMateria($asignacion))
            {
                foreach ($listaAsignaciones as $value) 
                {
                    if($asignacion->legajoProfesor == $value->legajoProfesor &&
                       $asignacion->idMateria == $value->idMateria && 
                       $asignacion->turno == $value->turno)
                       {
                            $esValido = false;                           
                            break;
                       }
                }
            }
            else
            {
                $esValido=false;
            }
           

            return $esValido;
        }

    }

?>