<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;

$app = Factory::getApplication();
?>

<div class="mod-contactdb<?php echo $moduleclass_sfx; ?>">
    <?php if ($show_title) : ?>
        <h3><?php echo $module->title; ?></h3>
    <?php endif; ?>
    
    <?php if (!empty($pretext)) : ?>
        <div class="pretext"><?php echo $pretext; ?></div>
    <?php endif; ?>
    
    <?php
    // Mostrar mensajes
    if ($app->getMessageQueue()) {
        foreach ($app->getMessageQueue() as $message) {
            echo '<div class="alert alert-' . $message['type'] . '">' . $message['message'] . '</div>';
        }
    }
    ?>
    
    <form action="<?php echo Route::_('index.php?option=com_contactdb&task=form.save'); ?>" method="post" class="form-validate">
        <div class="form-group">
            <label for="jform_name"><?php echo Text::_('Nombre'); ?> *</label>
            <input type="text" name="jform[name]" id="jform_name" class="form-control required" required aria-required="true" />
        </div>
        
        <div class="form-group">
            <label for="jform_email"><?php echo Text::_('Email'); ?> *</label>
            <input type="email" name="jform[email]" id="jform_email" class="form-control required validate-email" required aria-required="true" />
        </div>
        
        <div class="form-group">
            <label for="jform_subject"><?php echo Text::_('Asunto'); ?> *</label>
            <input type="text" name="jform[subject]" id="jform_subject" class="form-control required" required aria-required="true" />
        </div>
        
        <div class="form-group">
            <label for="jform_message"><?php echo Text::_('Mensaje'); ?> *</label>
            <textarea name="jform[message]" id="jform_message" rows="5" class="form-control required" required aria-required="true"></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary"><?php echo Text::_('Enviar mensaje'); ?></button>
        
        <input type="hidden" name="option" value="com_contactdb" />
        <input type="hidden" name="task" value="form.save" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
