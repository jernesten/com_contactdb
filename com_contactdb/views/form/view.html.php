<?php
defined('_JEXEC') or die;

class contactdbViewform extends JViewLegacy
{
    protected $form;
    protected $item;

    public function display($tpl = null)
    {
        // Obtener datos del modelo
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');

        // Verificar errores
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        // Preparar la vista
        $this->prepareDocument();
        parent::display($tpl);
    }

    protected function prepareDocument()
    {
        $doc = JFactory::getDocument();
        $doc->setTitle(JText::_('Mensaje enviado Gracias'));
    }
}
