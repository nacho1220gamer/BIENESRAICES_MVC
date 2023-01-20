<?php

namespace Controllers;

use Error;
use Exception;
use MVC\Router;
use Model\Vendedor;

class VendedorController {
    public static function crear(Router $router) {
        
        $vendedor = new Vendedor;

        // Arreglo con mensaje de errores
        $errores = Vendedor::getErrores();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $vendedor = new Vendedor($_POST['vendedor']);
    
            // Validar
            $errores = $vendedor->validar();
    
            if( empty($errores)) {
                // Guarda en la base de datos
                $vendedor->guardar();
            }
        }

        $router->render('vendedores/crear', [
           'vendedor' => $vendedor,
           'errores' => $errores
        ]);
    }

    public static function actualizar(Router $router) {

        $id = validarORedireccionar('/admin');
        $vendedor = Vendedor::find($id);

        $errores = Vendedor::getErrores();

        // Método POST para actualizar
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
        
            // Asignar los atributos
            $args = $_POST['vendedor']; 
            $vendedor->sincronizar( $args );
    
            // Validación
            $errores = $vendedor->validar();
    
            // Revisar que el array de errores este vacio
            if( empty($errores)) {
                $vendedor->actualizar();
            }
        }

        $router->render('/vendedores/actualizar', [
            'vendedor' => $vendedor,
            'errores' => $errores,
        ]);
    }
    
    public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //  Validar la id
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if($id) {

                $tipo = $_POST['tipo'];

                if(validarTipoContenido($tipo)) {
                    // Valida el tipo a eliminar
                    $tipo = $_POST['tipo'];

                    if(validarTipoContenido($tipo)) {
                        $vendedor = Vendedor::find($id);
                        try {
                            $vendedor->eliminar();
                        } catch(Exception $e) {
                            echo $e;
                        }
                        
                    }
                } 
                
            }
        }
    }
}