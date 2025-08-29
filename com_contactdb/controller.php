<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;

class ContactDBController extends BaseController
{
    public function display($cachable = false, $urlparams = array())
    {
        // Establecer la vista por defecto
        $view = $this->input->get('view', 'form');
        $this->input->set('view', $view);
        
        return parent::display($cachable, $urlparams);
    }
}
