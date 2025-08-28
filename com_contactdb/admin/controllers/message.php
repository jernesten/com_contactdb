<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\FormController;

class ContactDBControllerMessage extends FormController
{
    public function sendAnswer()
    {
        // Lógica para enviar respuesta
        $app = JFactory::getApplication();
        $data = $app->input->post->get('jform', array(), 'array');
        $model = $this->getModel('Message');
        
        if ($model->sendAnswer($data)) {
            $app->enqueueMessage('Respuesta enviada correctamente');
        } else {
            $app->enqueueMessage('Error al enviar la respuesta', 'error');
        }
        
        $this->setRedirect(JRoute::_('index.php?option=com_contactdb&view=messages', false));
    }
}
