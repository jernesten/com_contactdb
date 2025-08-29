<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

class ContactDBViewMessages extends HtmlView
{
    protected $items;
    protected $pagination;
    protected $state;
    
    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        
        // Agregar la barra de herramientas
        $this->addToolbar();
        
        parent::display($tpl);
    }
    
    protected function addToolbar()
    {
        ToolbarHelper::title(Text::_('ContactDB mail manager'), '');
        
        // Botón de preferencias
        ToolbarHelper::preferences('com_contactdb');
    }
}
