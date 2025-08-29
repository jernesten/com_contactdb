<?php
defined('_JEXEC') or die;

class pkg_ContactDBInstallerScript
{
    public function preflight($type, $parent)
    {
        if (version_compare(JVERSION, '3.9', '<')) {
            JFactory::getApplication()->enqueueMessage(
                'Este paquete requiere Joomla 3.9 o superior.',
                'error'
            );
            return false;
        }
        return true;
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
        // Desinstalar módulo y componente automáticamente
        $this->uninstallExtension('module', 'mod_contactdb');
        $this->uninstallExtension('component', 'com_contactdb');
        
        JFactory::getApplication()->enqueueMessage(
            'Paquete ContactDB y todos sus componentes desinstalados correctamente.',
            'message'
        );
    }
    
    private function uninstallExtension($type, $element)
    {
        $db = JFactory::getDbo();
        
        try {
            $query = $db->getQuery(true)
                ->select('extension_id')
                ->from('#__extensions')
                ->where('type = ' . $db->quote($type))
                ->where('element = ' . $db->quote($element));
                
            $db->setQuery($query);
            $extensionId = $db->loadResult();
            
            if ($extensionId) {
                $installer = new JInstaller();
                $result = $installer->uninstall($type, $extensionId);
                
                if ($result) {
                    JFactory::getApplication()->enqueueMessage(
                        '✅ ' . ucfirst($type) . ' ' . $element . ' desinstalado correctamente.',
                        'message'
                    );
                }
            }
        } catch (Exception $e) {
            // Error silencioso
        }
    }
}
