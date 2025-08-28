<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

// Determinar si estamos en el área administrativa o frontal
if (Factory::getApplication()->isClient('administrator')) {
    // Área administrativa
    JLoader::registerPrefix('ContactDB', JPATH_ADMINISTRATOR . '/components/com_contactdb');
    $controller = BaseController::getInstance('ContactDB');
} else {
    // Sitio frontal
    JLoader::registerPrefix('ContactDB', JPATH_SITE . '/components/com_contactdb');
    $controller = BaseController::getInstance('ContactDB');
}

// Ejecutar la tarea solicitada
$controller->execute(Factory::getApplication()->input->get('task'));

// Redirigir si es necesario
$controller->redirect();
