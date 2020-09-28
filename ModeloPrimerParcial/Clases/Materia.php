<?php
    require_once __DIR__.'./Archivos.php';

    class Materia extends ManejadorArchivos
    {
        public $nombre;
        public $cuatrimestre;
        public $id;

        public function __construct($nombre, $cuatrimestre, $id=0)
        {
            $this->nombre = $nombre;
            $this->cuatrimestre = $cuatrimestre;
            $this->id = $id;
        }

        public function __toString()
        {
            return $this->nombre.'*'.$this->cuatrimestre.'*'.$this->id;
        }

        public function __get($name)
        {
            echo $this->$name;
        }

        public function __set($name, $value)
        {
            $this->$name=$value;
        }

        static function PeticionesMateria($metodo)
        {
            switch ($metodo) 
            {
                case 'POST':

                    $nombre = $_POST['nombre'] ??'';
                    $cuatrimestre = $_POST['cuatrimestre']??'';
                   // $id = $_POST['id']??0;

                    //$materia = new Materia($nombre, $cuatrimestre, $id);
                    $materia = new Materia($nombre, $cuatrimestre);


                    $lista=array();

                    $listaMateriasJSON=parent::LeerJSON('./Archivos/listaMateriasJSON.json');

                    $listaMaterias=Materia::CrearMateriaJSON($listaMateriasJSON);

                    if(Materia::ValidarMateria($listaMaterias,$materia))
                    {
                        $nuevaMateria=Materia::AsignarID($listaMaterias, $materia);

                        array_push($lista,$nuevaMateria);

                        parent::GuardarJSON('./Archivos/listaMateriasJSON.json',$lista);//GUARDA JSON 

                        parent::Serializar('./Archivos/listaMateriasSerializado.txt',$lista);//SERIALIZA UNA LISTA

                        parent::GuardarArchivo('./Archivos/listaMaterias.txt',$nuevaMateria);// GUARDA UN DATO EN UN ARCHIVO
                    }
                    else
                    {
                        echo "Ya existe una materia con los mismos datos";
                    }

                    

                    # code...
                    break;
                
                case 'GET':
                    
                    $listaJSON=parent::LeerJSON('./Archivos/listaMateriasJSON.json');

                    if(count($listaJSON)>0)
                    {
                        $listaMaterias=Materia::CrearMateriaJSON($listaJSON);

                        Materia::MostrarTodo($listaMaterias);
                    }
                    else
                    {
                        echo "No hay datos cargados";
                    }
                   

                    break;


                default:
                    # code...
                    break;
            }
        }

        static function CrearMateria($listaDatosMateria)
        {
            $listaMaterias=array();
            
            foreach ($listaDatosMateria as $key => $value) 
            {
                $materia = new Materia($value[0], $value[1], $value[3]);

                array_push($listaMaterias, $materia);
                    
            }
            
                return $listaMaterias;
        }

        static function CrearMateriaJSON($listaDatosMateria)
        {
            $listaMaterias=array();
            
            foreach ($listaDatosMateria as $value) 
            {
                $materia = new Materia($value->nombre, $value->cuatrimestre, $value->id);

                array_push($listaMaterias, $materia);
                    
            }
            
                return $listaMaterias;
        }

        static function MostrarMateria($materia)
        {
           echo 'Nombre de la materia: '.$materia->nombre.'||'.'Cuatrimestre: '.$materia->cuatrimestre.'||'.'Id: '.$materia->id.PHP_EOL;
        }

        static function MostrarTodo($listaMaterias)
        {   
            foreach ($listaMaterias as $value) 
            {
                Materia::MostrarMateria($value);
            }
            
        }

        static function AsignarID($listaMaterias, $materia)
        {
            if(count($listaMaterias)==0)
            {
                $materia->id=1;
            }
            else
            {
                $ultimaMateria=end($listaMaterias);

                $materia->id=$ultimaMateria->id+1;
            }

            return $materia;
        }

        static function ValidarMateria($listaMaterias, $materia)
        {
            $esValido=true;

            foreach ($listaMaterias as $value) 
            {
                if($materia->nombre == $value->nombre && $materia->cuatrimestre == $value->cuatrimestre)
                {
                    $esValido=false;
                }
            }

            return $esValido;

        }
    }

?>
