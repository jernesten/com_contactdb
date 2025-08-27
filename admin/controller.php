<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;

class ContactDBController extends BaseController
{
    protected $default_view = 'messages';
    
    public function display($cachable = false, $urlparams = array())
    {
        $view   = $this->input->get('view', $this->default_view);
        $layout = $this->input->get('layout', 'default');
        $id     = $this->input->getInt('id');
        
        return parent::display($cachable, $urlparams);
    }
}
