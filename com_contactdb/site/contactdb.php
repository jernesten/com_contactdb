<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

// Acceso al controlador principal
$controller = BaseController::getInstance('ContactDB');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
