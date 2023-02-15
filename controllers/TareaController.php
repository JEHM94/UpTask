<?php

namespace Controllers;

use Model\Proyecto;
use Model\Tarea;

class TareaController
{
    public static function index()
    {
        $proyectoUrl = $_GET['url'];

        // Verifica que exista una Url de proyecto
        if (!$proyectoUrl) header('Location: /dashboard');

        // Busca el Proyecto con la Url ingresada
        $proyecto = Proyecto::where('url', $proyectoUrl);

        // Verifica que exista un proyecto con la Url ingresada
        // Y que el usuario que la consulta sea el Creadordel proyecto
        if (!$proyecto || $proyecto->usuarios_id !== $_SESSION['id']) header('Location: /404');

        // Busca las tareas del Proyecto
        $tareas = Tarea::belongsTo('proyectos_id', $proyecto->id);

        echo json_encode(['tareas' => $tareas]);
    }

    public static function crear()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $proyecto = Proyecto::where('url', $_POST['proyectoUrl']);

            // Verifica si existe un proyecto con la Url ingresada
            // Y si el usuario que intenta agregar la tarea es el mismo
            // creador del proyecto
            if (!$proyecto || $proyecto->usuarios_id !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Ha ocurrido un error al agregar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            }

            // Instancia una nueva tarea con los Datos de la petición
            $tarea = new Tarea($_POST);
            // Asigna el ID del proyecto a la tarea
            $tarea->proyectos_id = $proyecto->id;
            // Guarda la nueva Tarea
            $resultado = $tarea->guardar();
            if ($resultado) {
                $respuesta = [
                    'tipo' => 'exito',
                    'mensaje' => 'Tarea agregada exitosamente',
                    'id' => $resultado['id'],
                    'proyectoId' => $proyecto->id
                ];
                echo json_encode($respuesta);
            }
        }
    }

    public static function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $proyecto = Proyecto::where('url', $_POST['proyectoUrl']);

            // Verifica si existe un proyecto con la Url ingresada
            // Y si el usuario que intenta Editar la tarea es el mismo
            // creador del proyecto
            if (!$proyecto || $proyecto->usuarios_id !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Ha ocurrido un error al actualizar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            }

            // Instancia una nueva tarea con los Datos de la petición
            $tarea = new Tarea($_POST);
            // Asigna el ID del proyecto a la tarea
            $tarea->proyectos_id = $proyecto->id;
            // Actualiza la nueva Tarea
            $resultado = $tarea->guardar();
            if ($resultado) {
                $respuesta = [
                    'tipo' => 'exito',
                    'mensaje' => 'Tarea actualizada exitosamente',
                    'id' => $tarea->id,
                    'proyectoId' => $proyecto->id
                ];
                echo json_encode($respuesta);
            }
        }
    }

    public static function eliminar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $proyecto = Proyecto::where('url', $_POST['proyectoUrl']);

            // Verifica si existe un proyecto con la Url ingresada
            // Y si el usuario que intenta Eliminar la tarea es el mismo
            // creador del proyecto
            if (!$proyecto || $proyecto->usuarios_id !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Ha ocurrido un error al actualizar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            }

            // Instancia una nueva tarea con los Datos de la petición
            $tarea = new Tarea($_POST);

            // Elimina la tarea
            $resultado = $tarea->eliminar();

            if ($resultado) {
                $respuesta = [
                    'tipo' => 'exito',
                    'mensaje' => 'Tarea Eliminada',
                    'id' => $tarea->id,
                    'proyectoId' => $proyecto->id
                ];

                echo json_encode($respuesta);
            }
        }
    }
}
