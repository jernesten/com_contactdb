<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

class ContactDBControllerForm extends FormController
{
    public function save($key = null, $urlVar = null)
    {
        $this->checkToken();

        $app   = Factory::getApplication();
        $model = $this->getModel('Form');
        $data  = $this->input->post->get('jform', [], 'array');

        // Cargar el formulario y validar estructura
        $form = $model->getForm($data, false);
        if (!$form) {
            $app->enqueueMessage($model->getError(), 'error');
            $this->setRedirect(Route::_('index.php?option=com_contactdb&view=form', false));
            return false;
        }

        // Validar datos
        $validData = $model->validate($form, $data);
        if ($validData === false) {
            foreach (array_slice($model->getErrors(), 0, 3) as $error) {
                $msg = $error instanceof \Exception ? $error->getMessage() : $error;
                $app->enqueueMessage($msg, 'warning');
            }
            $app->setUserState('com_contactdb.form.data', $data);
            $this->setRedirect(Route::_('index.php?option=com_contactdb&view=form', false));
            return false;
        }

        // Guardar datos
        if (!$model->save($validData)) {
            $app->setUserState('com_contactdb.form.data', $validData);
            $app->enqueueMessage(Text::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()), 'error');
            $this->setRedirect(Route::_('index.php?option=com_contactdb&view=form', false));
            return false;
        }

        // Éxito: limpiar datos y mostrar mensaje
        $app->setUserState('com_contactdb.form.data', null);
        $app->enqueueMessage('✅ ' . Text::_('COM_CONTACTDB_SAVE_SUCCESS'), 'message');
        $this->setRedirect(Route::_('index.php?option=com_contactdb&view=form', false));

        return true;
    }
}

