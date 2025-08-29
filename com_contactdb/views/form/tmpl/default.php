<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;

$app = Factory::getApplication();
$data = $app->getUserState('com_contactdb.form.data', array());
?>

<?php if ($app->getMessageQueue()) : ?>
    <?php foreach ($app->getMessageQueue() as $message) : ?>
        <div class="alert alert-<?php echo $message['type']; ?>">
            <?php echo $message['message']; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<form action="<?php echo Route::_('index.php?option=com_contactdb&task=form.save'); ?>" method="post" id="adminForm">
    
    <div class="form-group">
        <label for="jform_name">Nombre completo *</label>
        <input type="text" name="jform[name]" id="jform_name" class="form-control" 
               value="<?php echo isset($data['name']) ? htmlspecialchars($data['name']) : ''; ?>" required />
    </div>
    
    <div class="form-group">
        <label for="jform_email">Correo electrónico *</label>
        <input type="email" name="jform[email]" id="jform_email" class="form-control" 
               value="<?php echo isset($data['email']) ? htmlspecialchars($data['email']) : ''; ?>" required />
    </div>
    
    <div class="form-group">
        <label for="jform_subject">Asunto *</label>
        <input type="text" name="jform[subject]" id="jform_subject" class="form-control" 
               value="<?php echo isset($data['subject']) ? htmlspecialchars($data['subject']) : ''; ?>" required />
    </div>
    
    <div class="form-group">
        <label for="jform_message">Mensaje *</label>
        <textarea name="jform[message]" id="jform_message" rows="5" class="form-control" required><?php 
            echo isset($data['message']) ? htmlspecialchars($data['message']) : ''; 
        ?></textarea>
    </div>
    
    <button type="submit" class="btn btn-primary">Enviar mensaje</button>
    
    <input type="hidden" name="option" value="com_contactdb" />
    <input type="hidden" name="task" value="form.save" />
    <?php echo JHtml::_('form.token'); ?>
</form>
