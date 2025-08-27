<?php
defined('_JEXEC') or die;

class Com_ContactDBInstallerScript
{
    public function install($parent)
    {
        $this->installModule();
        echo '<p>Componente ContactDB instalado correctamente.</p>';
        echo '<p>El módulo de formulario de contacto ha sido instalado y puede ser publicado en cualquier posición.</p>';
        return true;
    }
    
    public function uninstall($parent)
    {
        $this->uninstallModule();
        echo '<p>Componente ContactDB desinstalado correctamente.</p>';
        return true;
    }
    
    public function update($parent)
    {
        echo '<p>Componente ContactDB actualizado a la versión ' . $parent->get('manifest')->version . '.</p>';
        return true;
    }
    
    public function preflight($type, $parent)
    {
        if (version_compare(JVERSION, '3.8.0', 'lt')) {
            Jerror::raiseWarning(null, 'Este componente requiere Joomla 3.8 o superior');
            return false;
        }
        return true;
    }
    
    public function postflight($type, $parent)
    {
        if ($type == 'install') {
            $this->createAdminMenu();
        }
        return true;
    }
    
    private function installModule()
    {
        $db = JFactory::getDbo();
        
        // Verificar si el módulo ya existe
        $query = $db->getQuery(true)
            ->select('id')
            ->from('#__modules')
            ->where('module = ' . $db->quote('mod_contactdb'));
        $db->setQuery($query);
        
        if (!$db->loadResult()) {
            $module = (object) [
                'title' => 'Formulario de Contacto',
                'note' => '',
                'content' => '',
                'position' => '',
                'module' => 'mod_contactdb',
                'access' => 1,
                'showtitle' => 1,
                'params' => '{"show_title":"1","pretext":"Póngase en contacto con nosotros","moduleclass_sfx":""}',
                'client_id' => 0,
                'language' => '*',
                'published' => 0
            ];
            
            try {
                $db->insertObject('#__modules', $module);
                $moduleId = $db->insertid();
                
                // Asignar a todas las páginas
                $assignment = (object) [
                    'moduleid' => $moduleId,
                    'menuid' => 0
                ];
                $db->insertObject('#__modules_menu', $assignment);
                
                return true;
            } catch (Exception $e) {
                JFactory::getApplication()->enqueueMessage('Error instalando módulo: ' . $e->getMessage(), 'error');
                return false;
            }
        }
        return true;
    }
    
    private function uninstallModule()
    {
        $db = JFactory::getDbo();
        
        try {
            // Eliminar módulos
            $query = $db->getQuery(true)
                ->delete('#__modules')
                ->where('module = ' . $db->quote('mod_contactdb'));
            $db->setQuery($query);
            $db->execute();
            
            return true;
        } catch (Exception $e) {
            JFactory::getApplication()->enqueueMessage('Error desinstalando módulo: ' . $e->getMessage(), 'error');
            return false;
        }
    }
    
    private function createAdminMenu()
    {
        $db = JFactory::getDbo();
        
        try {
            // Verificar si el menú ya existe
            $query = $db->getQuery(true)
                ->select('id')
                ->from('#__menu')
                ->where('title = ' . $db->quote('ContactDB'))
                ->where('client_id = 1');
            $db->setQuery($query);
            
            if (!$db->loadResult()) {
                $componentId = $this->getComponentId();
                
                if ($componentId) {
                    $menu = (object) [
                        'menutype' => 'main',
                        'title' => 'ContactDB',
                        'alias' => 'contactdb',
                        'path' => 'contactdb',
                        'link' => 'index.php?option=com_contactdb',
                        'type' => 'component',
                        'published' => 1,
                        'parent_id' => 1,
                        'component_id' => $componentId,
                        'access' => 1,
                        'client_id' => 1,
                        'params' => '{}'
                    ];
                    
                    $db->insertObject('#__menu', $menu);
                }
            }
            return true;
        } catch (Exception $e) {
            // Error no crítico
            return false;
        }
    }
    
    private function getComponentId()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('extension_id')
            ->from('#__extensions')
            ->where('element = ' . $db->quote('com_contactdb'))
            ->where('type = ' . $db->quote('component'));
        $db->setQuery($query);
        return $db->loadResult();
    }
}
