<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;

$app = Factory::getApplication();
$input = $app->input;
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
    
    <form action="<?php echo Route::_('index.php'); ?>" method="post" class="form-validate">
        <div class="form-group">
            <label for="mod_contactdb_name">Nombre *</label>
            <input type="text" name="name" id="mod_contactdb_name" class="form-control required" required aria-required="true" />
        </div>
        
        <div class="form-group">
            <label for="mod_contactdb_email">Email *</label>
            <input type="email" name="email" id="mod_contactdb_email" class="form-control required validate-email" required aria-required="true" />
        </div>
        
        <div class="form-group">
            <label for="mod_contactdb_subject">Asunto *</label>
            <input type="text" name="subject" id="mod_contactdb_subject" class="form-control required" required aria-required="true" />
        </div>
        
        <div class="form-group">
            <label for="mod_contactdb_message">Mensaje *</label>
            <textarea name="message" id="mod_contactdb_message" rows="5" class="form-control required" required aria-required="true"></textarea>
        </div>
        
        <button type="submit" name="mod_contactdb_submit" value="1" class="btn btn-primary">Enviar mensaje</button>
        
        <input type="hidden" name="option" value="com_contactdb" />
        <input type="hidden" name="task" value="form.save" />
    </form>
</div>
