<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

class ContactDBControllerMessages extends AdminController
{
    protected $text_prefix = 'COM_CONTACTDB_MESSAGES';
    
    public function getModel($name = 'Message', $prefix = 'ContactDBModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }
}
