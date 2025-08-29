<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class ContactdbViewMessage extends JViewLegacy
{
    protected $form;
    protected $item;
    protected $state;

    public function display($tpl = null)
    {
        $this->form  = $this->get('Form');
        $this->item  = $this->get('Item');
        $this->state = $this->get('State');

        // Check for errors
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors), 500);
        }

        $this->addToolbar();
        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JFactory::getApplication()->input->set('hidemainmenu', true);

        $isNew = ($this->item->id == 0);

        JToolbarHelper::title($isNew ? JText::_('COM_CONTACTDB_MANAGER_MESSAGE_NEW') : JText::_('COM_CONTACTDB_MANAGER_MESSAGE_EDIT'), 'contactdb');

        JToolbarHelper::apply('message.apply');
        JToolbarHelper::save('message.save');
        
        if (!$isNew) {
            JToolbarHelper::save2copy('message.save2copy');
        }
        
        JToolbarHelper::cancel('message.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
    }
}
