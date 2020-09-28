<?php

require_once __DIR__.'./Archivos.php';

 class Usuario extends ManejadorArchivos
 {
     public $email;
     public $clave;

     public function __construct($email, $clave)
     {
         $this->email=$email;
         $this->clave=$clave;
     }

     public function __toString()
     {
        return $this->email.'*'.$this->clave; 
     }

     public function __get($name)
     {
         echo $this->$name;
     }

     public function __set($name, $value)
     {
         $this->$name=$value;
     }

     static function PeticionesUsuario($metodo)
     {
         switch($metodo)
         {
             case 'POST':

                $email=$_POST['email']??'';
                $clave=$_POST['clave']??'';

                $usuario = new Usuario($email, $clave);

                //parent::GuardarArchivo('./Archivos/listaUsuarios.txt', $usuario);

                $lista=array();

                array_push($lista,$usuario);

                parent::GuardarJSON('./Archivos/listaUsuariosJSON.json',$lista);

                parent::Serializar('./Archivos/listaUsuariosSerializado.txt',$lista);

                return $usuario;

                break;

             case 'GET':

                $listaDatosUsuarios=parent::LeerArchivo('./Archivos/listaUsuarios.txt');

                $listaUsuarios = Usuario::CrearUsuario($listaDatosUsuarios);

                /*foreach ($listaUsuarios as $value) 
                {
                    echo $value;
                }*/
                //var_dump($listaUsuarios);

                Usuario::MostrarTodos($listaUsuarios);

                var_dump($listaUsuarios);


                break;
         }
     }
     
     static function CrearUsuario($listaDatosUsuario)
     {
        $listaUsuarios=array();

        foreach ($listaDatosUsuario as $key => $value) 
        {
            $usuario = new Usuario($value[0],$value[1]);

            array_push($listaUsuarios, $usuario);
        }

         return $listaUsuarios;
     }

     static function CrearUsuarioJSON($listaDatosUsuario)
     {
        $listaUsuarios=array();

        foreach ($listaDatosUsuario as $value) 
        {
            $usuario = new Usuario($value->email,$value->clave);

            array_push($listaUsuarios, $usuario);
        }

         return $listaUsuarios;
     }

     static function MostrarUsuario($usuario)
     {
        echo 'Email:'.$usuario->email.'||'.'Clave:'.$usuario->clave.PHP_EOL;
     }

     static function MostrarTodos($listaUsuarios)
     {
        foreach ($listaUsuarios as $value) 
        {
            Usuario::MostrarUsuario($value);
        }
     }

 }





