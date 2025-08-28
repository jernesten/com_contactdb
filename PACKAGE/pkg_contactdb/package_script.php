<?php
defined('_JEXEC') or die;

class Pkg_ContactDBInstallerScript
{
    public function install($parent)
    {
        echo '<p>Package ContactDB instalado correctamente.</p>';
        echo '<p>El package incluye el componente ContactDB y el módulo de formulario.</p>';
        return true;
    }
    
    public function uninstall($parent)
    {
        // Obtener la base de datos
        $db = JFactory::getDbo();
        
        echo '<p>Iniciando desinstalación del package ContactDB...</p>';
        
        // Desinstalar el componente si existe
        $this->uninstallExtension('component', 'com_contactdb');
        
        // Desinstalar el módulo si existe
        $this->uninstallExtension('module', 'mod_contactdb');
        
        // Limpiar cualquier menú administrativo residual
        $this->cleanAdminMenu();
        
        echo '<p>Package ContactDB desinstalado correctamente.</p>';
        return true;
    }
    
    public function update($parent)
    {
        echo '<p>Package ContactDB actualizado a la versión ' . $parent->get('manifest')->version . '.</p>';
        return true;
    }
    
    public function preflight($type, $parent)
    {
        if (version_compare(JVERSION, '3.8.0', 'lt')) {
            Jerror::raiseWarning(null, 'Este package requiere Joomla 3.8 o superior');
            return false;
        }
        return true;
    }
    
    public function postflight($type, $parent)
    {
        if ($type == 'install') {
            echo '<p>Package instalado completamente. Ahora puedes publicar el módulo desde el Gestor de Módulos.</p>';
        }
        return true;
    }
    
    private function uninstallExtension($type, $element)
    {
        $db = JFactory::getDbo();
        
        try {
            // Buscar la extensión
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
                    echo '<p>✓ ' . ucfirst($type) . ' ' . $element . ' desinstalado correctamente.</p>';
                    return true;
                } else {
                    echo '<p>✗ Error desinstalando ' . $element . '. Intentando limpieza manual...</p>';
                    // Limpieza manual de extensiones residuales
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
        $db = JFactory::getDbo();
        
        try {
            // Eliminar registro de extensión si existe
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
        $db = JFactory::getDbo();
        
        try {
            // Eliminar menú administrativo del componente
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
