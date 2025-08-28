<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

// Detectar contexto
$app = Factory::getApplication();

if ($app->isClient('administrator')) {
    JLoader::registerPrefix('ContactDB', JPATH_ADMINISTRATOR . '/components/com_contactdb');
} else {
    JLoader::registerPrefix('ContactDB', JPATH_SITE . '/components/com_contactdb');
}

// Obtener instancia del controlador
$controller = BaseController::getInstance('ContactDB');

// Ejecutar la tarea solicitada
try {
    $controller->execute($app->input->get('task'));
} catch (\Throwable $e) {
    // Capturar errores no controlados para mostrar un mensaje amigable
    $app->enqueueMessage('❌ Error: ' . $e->getMessage(), 'error');
}

// Redirigir si procede
$controller->redirect();

