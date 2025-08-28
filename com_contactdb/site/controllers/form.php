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
        // Check for request forgeries.
        $this->checkToken();

        $app   = Factory::getApplication();
        $model = $this->getModel('Form');
        $data  = $this->input->post->get('jform', array(), 'array');
        
        // Validate the posted data.
        $form = $model->getForm($data, false);

        if (!$form) {
            $app->enqueueMessage($model->getError(), 'error');
            return false;
        }

        // Validate the posted data.
        $validData = $model->validate($form, $data);

        // Check for validation errors.
        if ($validData === false) {
            // Get the validation messages.
            $errors = $model->getErrors();

            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
                if ($errors[$i] instanceof Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            // Save the data in the session.
            $app->setUserState('com_contactdb.form.data', $data);

            // Redirect back to the form.
            $this->setRedirect(Route::_('index.php?option=com_contactdb&view=form', false));
            return false;
        }

        // Attempt to save the data.
        if (!$model->save($validData)) {
            // Save the data in the session.
            $app->setUserState('com_contactdb.form.data', $validData);

            // Redirect back to the form.
            $app->enqueueMessage(Text::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()), 'error');
            $this->setRedirect(Route::_('index.php?option=com_contactdb&view=form', false));
            return false;
        }

        // Clear the data from the session.
        $app->setUserState('com_contactdb.form.data', null);

        // Redirect to the success page.
        $app->enqueueMessage(Text::_('COM_CONTACTDB_SAVE_SUCCESS'));
        $this->setRedirect(Route::_('index.php?option=com_contactdb&view=form', false));
        return true;
    }
}
