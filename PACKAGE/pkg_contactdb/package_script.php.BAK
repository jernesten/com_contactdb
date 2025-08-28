<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\Installer;
use Joomla\CMS\Version;

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
        echo '<p>Iniciando desinstalación del package ContactDB...</p>';

        $this->uninstallExtension('component', 'com_contactdb');
        $this->uninstallExtension('module', 'mod_contactdb');
        $this->cleanAdminMenu();

        echo '<p>✓ Package ContactDB desinstalado correctamente.</p>';
        return true;
    }

    public function update($parent)
    {
        $manifest = method_exists($parent, 'getManifest') ? $parent->getManifest() : null;
        $version = ($manifest && isset($manifest->version)) ? (string) $manifest->version : 'desconocida';

        echo '<p>✓ Package ContactDB actualizado a la versión ' . htmlspecialchars($version, ENT_QUOTES, 'UTF-8') . '.</p>';
        return true;
    }

    public function preflight($type, $parent)
    {
        $jv = new Version();
        $current = $jv->getShortVersion();

        if (version_compare($current, '4.0.0', '<')) {
            Factory::getApplication()->enqueueMessage(
                'Este package requiere Joomla 4.0 o superior (actual: ' . $current . ')',
                'warning'
            );
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
                ->select($db->quoteName('extension_id'))
                ->from($db->quoteName('#__extensions'))
                ->where($db->quoteName('type') . ' = ' . $db->quote($type))
                ->where($db->quoteName('element') . ' = ' . $db->quote($element));
            $db->setQuery($query);
            $extensionId = (int) $db->loadResult();

            if ($extensionId) {
                $installer = new Installer();
                $result = $installer->uninstall($type, $extensionId);

                if ($result) {
                    echo '<p>✓ ' . ucfirst($type) . ' ' . htmlspecialchars($element, ENT_QUOTES, 'UTF-8') . ' desinstalado correctamente.</p>';
                    return true;
                } else {
                    echo '<p>✗ Error desinstalando ' . htmlspecialchars($element, ENT_QUOTES, 'UTF-8') . '. Intentando limpieza manual...</p>';
                    $this->cleanExtensionResidues($type, $element);
                    return false;
                }
            } else {
                echo '<p>ℹ️ ' . htmlspecialchars($element, ENT_QUOTES, 'UTF-8') . ' no encontrado en la base de datos.</p>';
                return true;
            }
        } catch (\Throwable $e) {
            echo '<p>✗ Error desinstalando ' . htmlspecialchars($element, ENT_QUOTES, 'UTF-8') . ': ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
            return false;
        }
    }

    private function cleanExtensionResidues($type, $element)
    {
        $db = Factory::getDbo();

        try {
            $query = $db->getQuery(true)
                ->delete($db->quoteName('#__extensions'))
                ->where($db->quoteName('type') . ' = ' . $db->quote($type))
                ->where($db->quoteName('element') . ' = ' . $db->quote($element));
            $db->setQuery($query);
            $db->execute();

            echo '<p>✓ Registros residuales de ' . htmlspecialchars($element, ENT_QUOTES, 'UTF-8') . ' eliminados.</p>';
            return true;
        } catch (\Throwable $e) {
            echo '<p>✗ Error limpiando residuos de ' . htmlspecialchars($element, ENT_QUOTES, 'UTF-8') . ': ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
            return false;
        }
    }

    private function cleanAdminMenu()
    {
        $db = Factory::getDbo();

        try {
            $query = $db->getQuery(true)
                ->delete($db->quoteName('#__menu'))
                ->where($db->quoteName('title') . ' = ' . $db->quote('ContactDB'))
                ->where($db->quoteName('client_id') . ' = 1')
                ->where($db->quoteName('link') . ' LIKE ' . $db->quote('%option=com_contactdb%'));
            $db->setQuery($query);
            $db->execute();

            echo '<p>✓ Menú administrativo eliminado.</p>';
            return true;
        } catch (\Throwable $e) {
            echo '<p>✗ Error eliminando menú administrativo: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
            return false;
        }
    }
}

