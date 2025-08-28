<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Application\CMSApplication;

class Com_ContactDBInstallerScript
{
    /**
     * Ejecutado durante la instalación
     */
    public function install($parent)
    {
        if ($this->createTable()) {
            echo '<p>✓ Tabla de mensajes creada correctamente.</p>';
        } else {
            echo '<p>⚠️ Error al crear la tabla de mensajes.</p>';
        }

        echo '<p>Componente ContactDB instalado correctamente.</p>';
        return true;
    }

    /**
     * Ejecutado durante la desinstalación
     */
    public function uninstall($parent)
    {
        if ($this->dropTable()) {
            echo '<p>✓ Tabla de mensajes eliminada correctamente.</p>';
        } else {
            echo '<p>⚠️ Error al eliminar la tabla de mensajes.</p>';
        }

        echo '<p>Componente ContactDB desinstalado correctamente.</p>';
        return true;
    }

    /**
     * Ejecutado durante la actualización
     */
    public function update($parent)
    {
        echo '<p>Componente ContactDB actualizado a la versión ' . $parent->get('manifest')->version . '.</p>';
        return true;
    }

    /**
     * Ejecutado antes de instalar, actualizar o desinstalar
     */
    public function preflight($type, $parent)
    {
        if (version_compare(JVERSION, '3.8.0', 'lt')) {
            Factory::getApplication()->enqueueMessage('Este componente requiere Joomla 3.8 o superior', 'warning');
            return false;
        }
        return true;
    }

    /**
     * Crea la tabla de mensajes
     */
    private function createTable()
    {
        try {
            $db = Factory::getDbo();

            $query = "
                CREATE TABLE IF NOT EXISTS `#__contactdb_messages` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `name` VARCHAR(255) NOT NULL,
                    `email` VARCHAR(255) NOT NULL,
                    `subject` VARCHAR(255) NOT NULL,
                    `message` TEXT NOT NULL,
                    `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `answered` TINYINT(1) NOT NULL DEFAULT 0,
                    `answer` TEXT,
                    `answered_date` DATETIME DEFAULT NULL,
                    `published` TINYINT(1) NOT NULL DEFAULT 1,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ";

            $db->setQuery($query);
            $db->execute();

            return true;
        } catch (Exception $e) {
            Factory::getApplication()->enqueueMessage('Error creando tabla: ' . $e->getMessage(), 'error');
            return false;
        }
    }

    /**
     * Elimina la tabla de mensajes
     */
    private function dropTable()
    {
        try {
            $db = Factory::getDbo();

            $query = "DROP TABLE IF EXISTS `#__contactdb_messages`";
            $db->setQuery($query);
            $db->execute();

            return true;
        } catch (Exception $e) {
            Factory::getApplication()->enqueueMessage('Error eliminando tabla: ' . $e->getMessage(), 'error');
            return false;
        }
    }
}

