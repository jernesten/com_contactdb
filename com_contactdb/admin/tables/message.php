<?php
defined('_JEXEC') or die;

use Joomla\CMS\Table\Table;
use Joomla\CMS\Factory;

class ContactDBTableMessage extends Table
{
    public function __construct(&$db)
    {
        parent::__construct('#__contactdb_messages', 'id', $db);
    }
    
    public function store($updateNulls = false)
    {
        $date = Factory::getDate();
        $user = Factory::getUser();
        
        if (empty($this->id)) {
            // Nuevo elemento
            $this->created = $date->toSql();
        } else {
            // Elemento existente
            $this->modified = $date->toSql();
            $this->modified_by = $user->get('id');
        }
        
        return parent::store($updateNulls);
    }
}
