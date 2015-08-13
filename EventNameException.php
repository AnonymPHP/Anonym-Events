<?php
/**
 * Bu Dosya AnonymFramework'e ait bir dosyadır.
 *
 * @author vahitserifsaglam <vahit.serif119@gmail.com>
 * @see http://gemframework.com
 *
 */


namespace Anonym\Components\Event;
use Exception;

/**
 * Class EventNameException
 * @package Anonym\Components\Event
 */
class EventNameException extends Exception
{


    /**
     * İstisnayı oluşturur
     *
     * @param string $message
     */
    public function __construct($message = '')
    {
        $this->message = $message;
    }
}
