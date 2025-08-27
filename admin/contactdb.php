<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

// Importar las clases necesarias
JLoader::registerPrefix('ContactDB', JPATH_COMPONENT_ADMINISTRATOR);

// Obtener la aplicación
$app = Factory::getApplication();

// Obtener el controlador
$controller = BaseController::getInstance('ContactDB');

// Ejecutar la tarea solicitada
$controller->execute($app->input->get('task'));

// Redirigir si es necesario
$controller->redirect();
