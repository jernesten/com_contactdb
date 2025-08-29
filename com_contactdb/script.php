<?php
defined('_JEXEC') or die;

class Com_ContactDBInstallerScript
{
    public function install($parent)
    {
        if ($this->createTable()) {
            echo '<p>✅ Tabla de mensajes creada correctamente.</p>';
        }
        echo '<p>Componente ContactDB instalado correctamente.</p>';
        return true;
    }
    
    public function uninstall($parent)
    {
        if ($this->dropTable()) {
            echo '<p>✅ Tabla de mensajes eliminada correctamente.</p>';
        }
        echo '<p>Componente ContactDB desinstalado correctamente.</p>';
        return true;
    }
    
    public function update($parent)
    {
        echo '<p>Componente ContactDB actualizado.</p>';
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
    
    private function createTable()
    {
        try {
            $db = JFactory::getDbo();
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
            $query = "DROP TABLE IF EXISTS `#__contactdb_messages`";
            $db->setQuery($query);
            $db->execute();
            return true;
            
        } catch (Exception $e) {
            JFactory::getApplication()->enqueueMessage('Error eliminando tabla: ' . $e->getMessage(), 'error');
            return false;
        }
    }
}
