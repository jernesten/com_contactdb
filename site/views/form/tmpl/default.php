<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

$app = Factory::getApplication();
$input = $app->input;

// Mostrar mensajes
if ($app->getMessageQueue()) {
    foreach ($app->getMessageQueue() as $message) {
        echo '<div class="alert alert-' . $message['type'] . '">' . $message['message'] . '</div>';
    }
}
?>

<form action="<?php echo Route::_('index.php?option=com_contactdb&task=form.save'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">
    
    <div class="control-group">
        <div class="control-label">
            <label for="jform_name"><?php echo Text::_('COM_CONTACTDB_NAME_LABEL'); ?></label>
        </div>
        <div class="controls">
            <input type="text" name="jform[name]" id="jform_name" value="" class="required" size="30" required aria-required="true" />
        </div>
    </div>
    
    <div class="control-group">
        <div class="control-label">
            <label for="jform_email"><?php echo Text::_('COM_CONTACTDB_EMAIL_LABEL'); ?></label>
        </div>
        <div class="controls">
            <input type="email" name="jform[email]" id="jform_email" value="" class="required validate-email" size="30" required aria-required="true" />
        </div>
    </div>
    
    <div class="control-group">
        <div class="control-label">
            <label for="jform_subject"><?php echo Text::_('COM_CONTACTDB_SUBJECT_LABEL'); ?></label>
        </div>
        <div class="controls">
            <input type="text" name="jform[subject]" id="jform_subject" value="" class="required" size="30" required aria-required="true" />
        </div>
    </div>
    
    <div class="control-group">
        <div class="control-label">
            <label for="jform_message"><?php echo Text::_('COM_CONTACTDB_MESSAGE_LABEL'); ?></label>
        </div>
        <div class="controls">
            <textarea name="jform[message]" id="jform_message" rows="10" cols="30" class="required" required aria-required="true"></textarea>
        </div>
    </div>
    
    <div class="control-group">
        <div class="controls">
            <button type="submit" class="btn btn-primary validate"><?php echo Text::_('COM_CONTACTDB_SUBMIT_BUTTON'); ?></button>
        </div>
    </div>
    
    <input type="hidden" name="option" value="com_contactdb" />
    <input type="hidden" name="task" value="form.save" />
    <?php echo JHtml::_('form.token'); ?>
</form>
