<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Installer\Installer;
use Joomla\Database\DatabaseDriver;

class Pkg_ContactDBInstallerScript
{
    public function install($parent)
    {
        echo '<p>✓ Package ContactDB instalado correctamente.</p>';
        echo '<p>Incluye el componente ContactDB y el módulo de formulario.</p>';
        return true;
    }

    public function uninstall($parent)
    {
        $db = Factory::getDbo();
        echo '<p>Iniciando desinstalación del package ContactDB...</p>';

        $this->uninstallExtension('component', 'com_contactdb');
        $this->uninstallExtension('module', 'mod_contactdb');
        $this->cleanAdminMenu();

        echo '<p>✓ Package ContactDB desinstalado correctamente.</p>';
        return true;
    }

    public function update($parent)
    {
        echo '<p>✓ Package ContactDB actualizado a la versión ' . $parent->get('manifest')->version . '.</p>';
        return true;
    }

    public function preflight($type, $parent)
    {
        if (version_compare(JVERSION, '3.8.0', '<')) {
            Factory::getApplication()->enqueueMessage('Este package requiere Joomla 3.8 o superior', 'warning');
            return false;
        }
        return true;
    }

    public function postflight($type, $parent)
    {
        if ($type === 'install') {
            echo '<p>✓ Package instalado completamente. Puedes publicar el módulo desde el Gestor de Módulos.</p>';
        }
        return true;
    }

    private function uninstallExtension($type, $element)
    {
        $db = Factory::getDbo();

        try {
            $query = $db->getQuery(true)
                ->select('extension_id')
                ->from('#__extensions')
                ->where('type = ' . $db->quote($type))
                ->where('element = ' . $db->quote($element));
            $db->setQuery($query);
            $extensionId = $db->loadResult();

            if ($extensionId) {
                $installer = new Installer();
                $result = $installer->uninstall($type, $extensionId);

                if ($result) {
                    echo '<p>✓ ' . ucfirst($type) . ' ' . $element . ' desinstalado correctamente.</p>';
                    return true;
                } else {
                    echo '<p>✗ Error desinstalando ' . $element . '. Intentando limpieza manual...</p>';
                    $this->cleanExtensionResidues($type, $element);
                    return false;
                }
            } else {
                echo '<p>ℹ️ ' . $element . ' no encontrado en la base de datos.</p>';
                return true;
            }
        } catch (Exception $e) {
            echo '<p>✗ Error desinstalando ' . $element . ': ' . $e->getMessage() . '</p>';
            return false;
        }
    }

    private function cleanExtensionResidues($type, $element)
    {
        $db = Factory::getDbo();

        try {
            $query = $db->getQuery(true)
                ->delete('#__extensions')
                ->where('type = ' . $db->quote($type))
                ->where('element = ' . $db->quote($element));
            $db->setQuery($query);
            $db->execute();

            echo '<p>✓ Registros residuales de ' . $element . ' eliminados.</p>';
            return true;
        } catch (Exception $e) {
            echo '<p>✗ Error limpiando residuos de ' . $element . ': ' . $e->getMessage() . '</p>';
            return false;
        }
    }

    private function cleanAdminMenu()
    {
        $db = Factory::getDbo();

        try {
            $query = $db->getQuery(true)
                ->delete('#__menu')
                ->where('title = ' . $db->quote('ContactDB'))
                ->where('client_id = 1')
                ->where('link LIKE ' . $db->quote('%option=com_contactdb%'));
            $db->setQuery($query);
            $db->execute();

            echo '<p>✓ Menú administrativo eliminado.</p>';
            return true;
        } catch (Exception $e) {
            echo '<p>✗ Error eliminando menú administrativo: ' . $e->getMessage() . '</p>';
            return false;
        }
    }
}

