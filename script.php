<?php
defined('_JEXEC') or die;

class Com_ContactDBInstallerScript
{
    public function install($parent)
    {
        // Crear la tabla manualmente
        if ($this->createTable()) {
            echo '<p>✓ Tabla de mensajes creada correctamente.</p>';
        } else {
            echo '<p>✗ Error al crear la tabla de mensajes.</p>';
        }
        
        // Instalar el módulo
        if ($this->installModule()) {
            echo '<p>✓ Módulo de contacto instalado.</p>';
        } else {
            echo '<p>✗ Error al instalar el módulo.</p>';
        }
        
        echo '<p>Componente ContactDB instalado correctamente.</p>';
        echo '<p>El módulo de formulario de contacto ha sido instalado y puede ser publicado en cualquier posición.</p>';
        return true;
    }
    
    public function uninstall($parent)
    {
        // Eliminar la tabla manualmente
        if ($this->dropTable()) {
            echo '<p>✓ Tabla de mensajes eliminada correctamente.</p>';
        } else {
            echo '<p>✗ Error al eliminar la tabla de mensajes.</p>';
        }
        
        // Desinstalar el módulo
        if ($this->uninstallModule()) {
            echo '<p>✓ Módulo de contacto desinstalado.</p>';
        } else {
            echo '<p>✗ Error al desinstalar el módulo.</p>';
        }
        
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
    
    private function createTable()
    {
        try {
            $db = JFactory::getDbo();
            
            // Crear tabla de mensajes
            $query = "CREATE TABLE IF NOT EXISTS `#__contactdb_messages` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                `email` varchar(255) NOT NULL,
                `subject` varchar(255) NOT NULL,
                `message` text NOT NULL,
                `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `answered` tinyint(1) NOT NULL DEFAULT 0,
                `answer` text,
                `answered_date` datetime DEFAULT NULL,
                `published` tinyint(1) NOT NULL DEFAULT 1,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            $db->setQuery($query);
            $db->execute();
            
            return true;
        } catch (Exception $e) {
            JFactory::getApplication()->enqueueMessage('Error creando tabla: ' . $e->getMessage(), 'error');
            return false;
        }
    }
    
    private function dropTable()
    {
        try {
            $db = JFactory::getDbo();
            
            // Eliminar tabla de mensajes
            $query = "DROP TABLE IF EXISTS `#__contactdb_messages`";
            
            $db->setQuery($query);
            $db->execute();
            
            return true;
        } catch (Exception $e) {
            JFactory::getApplication()->enqueueMessage('Error eliminando tabla: ' . $e->getMessage(), 'error');
            return false;
        }
    }
    
    private function installModule()
    {
        try {
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
                
                $db->insertObject('#__modules', $module);
                $moduleId = $db->insertid();
                
                // Asignar a todas las páginas
                $assignment = (object) [
                    'moduleid' => $moduleId,
                    'menuid' => 0
                ];
                $db->insertObject('#__modules_menu', $assignment);
            }
            return true;
        } catch (Exception $e) {
            JFactory::getApplication()->enqueueMessage('Error instalando módulo: ' . $e->getMessage(), 'error');
            return false;
        }
    }
    
    private function uninstallModule()
    {
        try {
            $db = JFactory::getDbo();
            
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
        try {
            $db = JFactory::getDbo();
            
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
