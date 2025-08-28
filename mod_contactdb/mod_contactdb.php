<?php
defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Factory;

// Incluir el CSS del módulo
$document = Factory::getDocument();
$document->addStyleSheet(JURI::root(true) . '/modules/mod_contactdb/assets/style.css');

// Obtener parámetros
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');
$pretext = $params->get('pretext');
$show_title = $params->get('show_title', 1);

// Incluir el helper
require_once __DIR__ . '/helper.php';

// Obtener el formulario
$form = ModContactDBHelper::getForm($params);

// Mostrar la plantilla
require ModuleHelper::getLayoutPath('mod_contactdb', $params->get('layout', 'default'));
