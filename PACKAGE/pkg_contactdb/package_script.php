<?php
defined('_JEXEC') or die;

class pkg_ContactDBInstallerScript
{
    public function preflight($type, $parent)
    {
        // Verifica versión mínima de Joomla (opcional)
        if (version_compare(JVERSION, '3.9', '<'))
        {
            JFactory::getApplication()->enqueueMessage(
                'Este paquete requiere Joomla 3.9 o superior.',
                'error'
            );
            return false;
        }
    }

    public function install($parent)
    {
        JFactory::getApplication()->enqueueMessage(
            'Paquete ContactDB instalado correctamente. Recuerda publicar el módulo desde el Gestor de Módulos.',
            'message'
        );
    }

    public function update($parent)
    {
        JFactory::getApplication()->enqueueMessage(
            'Paquete ContactDB actualizado correctamente.',
            'message'
        );
    }

    public function uninstall($parent)
    {
        JFactory::getApplication()->enqueueMessage(
            'Paquete ContactDB desinstalado.',
            'message'
        );
    }
}

