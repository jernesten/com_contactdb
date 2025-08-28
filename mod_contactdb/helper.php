<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;

class ModContactDBHelper
{
    public static function getForm($params)
    {
        // Aquí procesaríamos el formulario si se ha enviado
        $app = Factory::getApplication();
        
        // Si el formulario se ha enviado
        if ($app->input->get('mod_contactdb_submit', false)) {
            self::processForm($app->input->post);
        }
        
        return true;
    }
    
    public static function processForm($data)
    {
        $app = Factory::getApplication();
        $db = Factory::getDbo();
        
        // Validar los datos
        $name = filter_var($data->get('name', '', 'STRING'), FILTER_SANITIZE_STRING);
        $email = filter_var($data->get('email', '', 'STRING'), FILTER_SANITIZE_EMAIL);
        $subject = filter_var($data->get('subject', '', 'STRING'), FILTER_SANITIZE_STRING);
        $message = filter_var($data->get('message', '', 'STRING'), FILTER_SANITIZE_STRING);
        
        // Validaciones
        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            $app->enqueueMessage('Por favor, complete todos los campos obligatorios.', 'error');
            return false;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $app->enqueueMessage('Por favor, ingrese un email válido.', 'error');
            return false;
        }
        
        // Insertar en la base de datos
        $object = new stdClass();
        $object->name = $name;
        $object->email = $email;
        $object->subject = $subject;
        $object->message = $message;
        $object->created = Factory::getDate()->toSql();
        $object->published = 1;
        
        try {
            $result = $db->insertObject('#__contactdb_messages', $object);
            $app->enqueueMessage('Su mensaje ha sido enviado correctamente. Le responderemos pronto.', 'message');
            return true;
        } catch (Exception $e) {
            $app->enqueueMessage('Error al enviar el mensaje: ' . $e->getMessage(), 'error');
            return false;
        }
    }
}
