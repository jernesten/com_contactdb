<?php
defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\HTML\HTMLHelper;

// Ya no necesitas estas líneas:
// HTMLHelper::_('behavior.tooltip');
// HTMLHelper::_('behavior.multiselect');
?>
<form action="<?php echo Route::_('index.php?option=com_contactdb&view=messages'); ?>" method="post" name="adminForm" id="adminForm">
    <?php if (!empty($this->items)) : ?>
    <table class="table table-striped" id="messageList">
        <thead>
            <tr>
                <th width="1%">
                    <?php echo HTMLHelper::_('grid.checkall'); ?>
                </th>
                <th width="20%"><?php echo Text::_('Nombre'); ?></th>
                <th width="20%"><?php echo Text::_('Email'); ?></th>
                <th width="20%"><?php echo Text::_('Asunto'); ?></th>
                <th width="15%"><?php echo Text::_('Fecha'); ?></th>
                <th width="10%"><?php echo Text::_('Respondido'); ?></th>
                <th width="5%"><?php echo Text::_('Estado'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($this->items as $i => $item) : ?>
            <tr class="row<?php echo $i % 2; ?>">
                <td class="center">
                    <?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
                </td>
                <td>
                    <a href="<?php echo Route::_('index.php?option=com_contactdb&task=message.edit&id=' . $item->id); ?>">
                        <?php echo $this->escape($item->name); ?>
                    </a>
                </td>
                <td>
                    <?php echo $this->escape($item->email); ?>
                </td>
                <td>
                    <?php echo $this->escape($item->subject); ?>
                </td>
                <td>
                    <?php echo HTMLHelper::_('date', $item->created, Text::_('DATE_FORMAT_LC4')); ?>
                </td>
                <td class="center">
                    <?php if ($item->answered) : ?>
                        <span class="icon-checkbox-checked" aria-hidden="true"></span>
                    <?php else : ?>
                        <span class="icon-checkbox-unchecked" aria-hidden="true"></span>
                    <?php endif; ?>
                </td>
                <td class="center">
                    <?php echo HTMLHelper::_('jgrid.published', $item->published, $i, 'messages.'); ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php else : ?>
    <div class="alert alert-info">
        <span class="icon-info-circle" aria-hidden="true"></span>
        <span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
        <?php echo Text::_('No hay mensajes de contacto'); ?>
    </div>
    <?php endif; ?>
    
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <?php echo HTMLHelper::_('form.token'); ?>
</form>
