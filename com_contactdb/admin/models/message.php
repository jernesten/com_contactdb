<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Factory;

class ContactDBModelMessage extends AdminModel
{
    protected $text_prefix = 'COM_CONTACTDB';
    
    public function getTable($name = 'Message', $prefix = 'ContactDBTable', $config = array())
    {
        return parent::getTable($name, $prefix, $config);
    }
    
    public function getForm($data = array(), $loadData = true)
    {
        // Obtener el formulario
        $form = $this->loadForm('com_contactdb.message', 'message', array('control' => 'jform', 'load_data' => $loadData));
        
        if (empty($form)) {
            return false;
        }
        
        return $form;
    }
    
    protected function loadFormData()
    {
        // Verificar la sesión para datos previamente introducidos
        $app = Factory::getApplication();
        $data = $app->getUserState('com_contactdb.edit.message.data', array());
        
        if (empty($data)) {
            $data = $this->getItem();
        }
        
        return $data;
    }
    
    public function sendAnswer($data)
    {
        $id = $data['id'];
        $answer = $data['answer'];
        
        // Obtener el mensaje original
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')
              ->from($db->quoteName('#__contactdb_messages'))
              ->where($db->quoteName('id') . ' = ' . $id);
        $db->setQuery($query);
        $message = $db->loadObject();
        
        if (!$message) {
            return false;
        }
        
        // Construir el email de respuesta
        $subject = "Re: " . $message->subject;
        $body = "Hola " . $message->name . ",\n\n";
        $body .= "Gracias por contactarnos. Aquí está nuestra respuesta:\n\n";
        $body .= $answer . "\n\n";
        $body .= "Atentamente,\nEl equipo de soporte";
        
        $mailer = Factory::getMailer();
        $mailer->setSender(array(Factory::getConfig()->get('mailfrom'), Factory::getConfig()->get('fromname')));
        $mailer->addRecipient($message->email);
        $mailer->setSubject($subject);
        $mailer->setBody($body);
        
        try {
            $result = $mailer->Send();
            
            if ($result === true) {
                // Marcar como respondido en la base de datos
                $query = $db->getQuery(true);
                $fields = array(
                    $db->quoteName('answered') . ' = 1',
                    $db->quoteName('answer') . ' = ' . $db->quote($answer),
                    $db->quoteName('answered_date') . ' = ' . $db->quote(Factory::getDate()->toSql())
                );
                $query->update($db->quoteName('#__contactdb_messages'))
                      ->set($fields)
                      ->where($db->quoteName('id') . ' = ' . $id);
                $db->setQuery($query);
                $db->execute();
                
                return true;
            }
        } catch (Exception $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }
        
        return false;
    }
}
