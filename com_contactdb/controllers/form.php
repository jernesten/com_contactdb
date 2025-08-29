<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

class ContactDBControllerForm extends BaseController
{
    public function save()
    {
        // Check for request forgeries
        $this->checkToken();

        $app = Factory::getApplication();
        $model = $this->getModel('Form');
        $data = $this->input->post->get('jform', array(), 'array');
        
        // Validar datos
        $errors = $model->validateForm($data);
        
        if (!empty($errors)) {
            foreach ($errors as $error) {
                $app->enqueueMessage($error, 'error');
            }
            
            $app->setUserState('com_contactdb.form.data', $data);
            $this->setRedirect(Route::_('index.php?option=com_contactdb&view=form', false));
            return false;
        }

        // Intentar guardar
        if ($model->save($data)) {
            $app->enqueueMessage(Text::_('Mensaje enviado correctamente'));
            $app->setUserState('com_contactdb.form.data', null);
        } else {
            $app->enqueueMessage(Text::_('Error al enviar el mensaje'), 'error');
            $app->setUserState('com_contactdb.form.data', $data);
        }

        $this->setRedirect(Route::_('index.php?option=com_contactdb&view=form', false));
        return true;
    }
}
