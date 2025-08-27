<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\FormModel;
use Joomla\CMS\Factory;

class ContactDBModelForm extends FormModel
{
    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm('com_contactdb.form', 'form', array('control' => 'jform', 'load_data' => $loadData));
        
        if (empty($form)) {
            return false;
        }
        
        return $form;
    }
    
    public function save($data)
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        
        $columns = array('name', 'email', 'subject', 'message', 'created');
        $values = array(
            $db->quote($data['name']),
            $db->quote($data['email']),
            $db->quote($data['subject']),
            $db->quote($data['message']),
            $db->quote(Factory::getDate()->toSql())
        );
        
        $query
            ->insert($db->quoteName('#__contactdb_messages'))
            ->columns($db->quoteName($columns))
            ->values(implode(',', $values));
            
        $db->setQuery($query);
        
        try {
            $db->execute();
            return true;
        } catch (Exception $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
            return false;
        }
    }
}
