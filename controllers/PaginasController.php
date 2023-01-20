<?php 

namespace Controllers;

use MVC\Router;
use Model\Propiedad;
use PHPMailer\PHPMailer\PHPMailer;

class PaginasController {
    public static function index( Router $router) {
        
        $propiedades = Propiedad::get(3);
        $inicio = true;

        $router->render('paginas/index', [
            'propiedades' => $propiedades,
            'inicio' => $inicio
        ]);
    }
    public static function nosotros(Router $router) {
        
        $router->render('paginas/nosotros', []);
    }
    public static function propiedades( Router $router ) {
        
        $propiedades = Propiedad::all();

        $router->render('paginas/propiedades', [
            'propiedades' => $propiedades
        ]);
    }
    public static function propiedad( Router $router) {
        
        $id = validarORedireccionar('/propiedades');

        // Buscar la propiedad por su Id
        $propiedad = Propiedad::find($id);

        $router->render('paginas/propiedad', [
            'propiedad' => $propiedad
        ]);
    }
    public static function blog( Router $router ) {
        
        $router->render('paginas/blog', []);
    }
    public static function entrada( Router $router ) {
        
        $router->render('paginas/entrada', []);
    }
    public static function contacto( Router $router ) {

        $mensaje = null;
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $respuestas = $_POST['contacto'];

            // Crear una instancia de PHP Mailer
            $mail = new PHPMailer();

            // Configurar SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Port = 2525;
            $mail->Username = '4a5f9942357862';
            $mail->Password = 'a1e49c6b8b4048';
            // Configurar el contenido del mail
            $mail->setFrom("admin@bienesraices.com");
            $mail->addAddress("admin@bienesraices.com", "BienesRaices.com");
            $mail->Subject = "Tienes un Nuevo Mensaje";

            // Habilitar HTML
            $mail->isHTML(true);
            $mail->CharSet = "UTF-8";

            // Definir el contenido
            $contenido = "<html>";
            $contenido .= "<p>Tienes un nuevo mensaje</p>";
            $contenido .= "<p>Nombre: " . $respuestas['nombre'] . " </p>";
            $contenido .= "<p>Mensaje: " . $respuestas['mensaje'] . " </p>";
            $contenido .= "<p>Vende o Compra: " . $respuestas['tipo'] . " </p>";
            $contenido .= "<p>Presupuesto o Precio: $" . $respuestas['precio'] . " </p>";

            // Enviar algunos campos de forma condicional de email o teléfono
            if($respuestas['contacto'] === 'telefono') {
                $contenido .= '<p>Eligió ser contactado por Teléfono:</p>';
                $contenido .= "<p>Teléfono: " . $respuestas['telefono'] . " </p>";
                $contenido .= "<p>Fecha contacto: " . $respuestas['fecha'] . " </p>";
                $contenido .= "<p>Hora: " . $respuestas['hora'] . " </p>";
            } else {
                // Es E-Mail, entonces agregamos el campo
                $contenido .= '<p>Eligió ser contactado por E-Mail:</p>';
                $contenido .= "<p>E-Mail: " . $respuestas['email'] . " </p>";
            }
            $contenido .= "<p>Prefiere ser contactado por: " . $respuestas['contacto'] . " </p>";
            $contenido .= "</html>";

            $mail->Body = $contenido;
            $mail->AltBody = "Esto es texto alternativo sin HTML";

            // Enviar el Email
            
            if(  $mail->send() ) {
                $mensaje = "Mensaje enviado Correctamente";
            } else {
                $mensaje = "El Mensaje no se pudo enviar...";
            }

        }

        $router->render('paginas/contacto', [
            'mensaje' => $mensaje
        ]);
    }
}