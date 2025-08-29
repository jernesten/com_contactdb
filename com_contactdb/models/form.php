<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseModel;

class ContactDBModelForm extends BaseModel
{
    public function validateForm($data)
    {
        $errors = array();

        // Validar nombre
        if (empty($data['name'])) {
            $errors[] = 'El nombre es obligatorio';
        }

        // Validar email
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'El email no es válido';
        }

        // Validar asunto
        if (empty($data['subject'])) {
            $errors[] = 'El asunto es obligatorio';
        }

        // Validar mensaje
        if (empty($data['message'])) {
            $errors[] = 'El mensaje es obligatorio';
        }

        return $errors;
    }
    
    public function save($data)
    {
        $db = Factory::getDbo();
        
        try {
            $object = new stdClass();
            $object->name = $data['name'];
            $object->email = $data['email'];
            $object->subject = $data['subject'];
            $object->message = $data['message'];
            $object->created = Factory::getDate()->toSql();
            $object->published = 1;

            $result = $db->insertObject('#__contactdb_messages', $object);
            return $result;
            
        } catch (Exception $e) {
            Factory::getApplication()->enqueueMessage('Error de base de datos: ' . $e->getMessage(), 'error');
            return false;
        }
    }
}
