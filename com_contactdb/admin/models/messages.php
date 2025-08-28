<?php
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Factory;

class ContactDBModelMessages extends ListModel
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'name', 'a.name',
                'email', 'a.email',
                'subject', 'a.subject',
                'created', 'a.created',
                'answered', 'a.answered',
                'published', 'a.published'
            );
        }

        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query->select(
            $this->getState(
                'list.select',
                'a.id, a.name, a.email, a.subject, a.message, a.created, a.answered, a.answer, a.answered_date, a.published'
            )
        );
        $query->from($db->quoteName('#__contactdb_messages') . ' AS a');

        // Filtro por estado de publicación
        $published = $this->getState('filter.published');
        if (is_numeric($published)) {
            $query->where('a.published = ' . (int) $published);
        } elseif ($published === '') {
            $query->where('(a.published IN (0, 1))');
        }

        // Filtro por estado de respuesta
        $answered = $this->getState('filter.answered');
        if (is_numeric($answered)) {
            $query->where('a.answered = ' . (int) $answered);
        }

        // Ordenar
        $orderCol = $this->state->get('list.ordering', 'a.created');
        $orderDirn = $this->state->get('list.direction', 'desc');
        $query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));

        return $query;
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
        
        $headers = "From: " . Factory::getConfig()->get('mailfrom') . "\r\n";
        $headers .= "Reply-To: " . Factory::getConfig()->get('mailfrom') . "\r\n";
        $headers .= "Content-Type: text/plain; charset=utf-8\r\n";
        
        // Enviar el email usando mail()
        $result = mail($message->email, $subject, $body, $headers);
        
        if ($result) {
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
        
        return false;
    }
}
