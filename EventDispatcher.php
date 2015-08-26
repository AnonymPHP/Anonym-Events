<?php
/**
 * This file belongs to the AnoynmFramework
 *
 * @author vahitserifsaglam <vahit.serif119@gmail.com>
 * @see http://gemframework.com
 *
 * Thanks for using
 */

namespace Anonym\Components\Event;

use Anonym\Components\Event\Event as EventDispatch;
use Anonym\Components\Event\EventCollector;
use Anonym\Components\Event\EventListener;
use Closure;

/**
 *
 * Class Event
 * @package Anonym
 */
class EventDispatcher
{
    /**
     * store the list of fired events
     *
     * @var EventDispatch
     */
    private $firing;


    /**
     * create a new instance and registerde event collector
     *
     */
    public function __construct()
    {

        $this->listeners = EventCollector::getListeners();
    }

    /**
     * execute the event
     *
     * @param string|EventDispatch $event the name of event.
     * @param array|null $parameters the parameters for closure events
     * @return array the response
     * @throws EventException
     * @throws EventListenerException
     * @throws EventNameException
     * @throws EventNotFoundException
     */
    public function fire($event = null, array $parameters = null)
    {

        list($listeners, $event) = $this->resolveEventAndListeners($event);


    }

    /**
     * resolve the event and listener
     *
     * @param mixed $event
     * @throws EventListenerException
     * @return null|string|EventDispatch
     */
    private function resolveEventAndListeners($event)
    {

        if (is_object($event) && $event instanceof EventDispatch) {
            $event = get_class($event);
        }

        $name = $event;
        if (is_string($name)) {
            if ( $this->hasListiner($name) && $listeners = $this->getListeners($name)) {
                if (count($listeners) === 1) {
                    $listeners = $listeners[0];
                    $listeners = $listeners instanceof Closure ?  $listeners : new $listeners;
                }
            }else{
                throw new EventListenerException(sprintf('Your %s event havent got listener'));
            }
        }
        $listeners = (array) $listeners;
        return [$listeners, $event];
    }
    /**
     * register a new listener
     *
     * @param string|Event $name the name or instance of event
     * @param string|EventListener $listener the name or instance of event listener
     * @return $this
     */
    public function listen($name, $listener)
    {
        EventCollector::addListener($name, $listener);
        return $this;
    }


    /**
     * return the registered listeners
     *
     * @param string $eventName get the event listeners
     * @return mixed
     * @throws EventNameException
     */
    public function getListeners($eventName = '')
    {
        if (!is_string($eventName)) {
            throw new EventNameException('Event adı geçerli bir string değeri olmalıdır');
        }

        return EventCollector::getListeners()[$eventName];
    }

    /**
     * check the isset any listener
     *
     * @param string $eventName the name of event
     * @return bool
     */
    public function hasListiner($eventName = '')
    {
        $listeners = EventCollector::getListeners();
        return isset($listeners[$eventName]);
    }

    /**
     * get the last fired event response
     *
     * @return mixed
     */
    public function firing()
    {
        return end($this->firing);
    }
}