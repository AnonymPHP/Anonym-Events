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
use Exception;
use Anonym\Components\Event\EventCollector;
use Anonym\Components\Event\Event as EventDispatch;
use Anonym\Components\Event\EventListener;

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
     * @param string $eventName the name of event.
     * @return array the response
     * @throws EventException
     * @throws EventListenerException
     * @throws EventNameException
     * @throws EventNotFoundException
     */
    public function fire($eventName = null)
    {
        if (is_string($eventName) || $eventName instanceof EventDispatch) {
            if ($eventName instanceof EventDispatch) {
                $eventInstance = $eventName;
                $eventName = get_class($eventName);
            } elseif (is_string($eventName)) {
                if (strstr($eventName, "\\")) {
                    $eventName = new $eventName();
                } else {
                    if (isset($this->list[$eventName])) {
                        $eventName = $this->list[$eventName];
                        $eventName = new $eventName();
                    } else {
                        throw new EventNotFoundException(sprintf('%s isimli bir �n tan�ml� event bulunamad�', $eventName));
                    }
                }
            }
            if ($this->hasListiner($eventName)) {
                $listeners = $this->getListeners($eventName);
                $response = $this->runListenersHandle($listeners, $eventInstance);
                if (count($response) === 1) {
                    $response = $response[0];
                }
                $this->firing[] = $response;
                return $response;
            } else {
                throw new EventListenerException(sprintf('%s ad�ndaki Event\' in herhangi bir dinleyicisi yok', $eventName));
            }
        } else {
            throw new EventException('Girdi�iniz Event, ge�erli bir event de�il');
        }
    }

    /**
     * register a new listener
     *
     * @param string|Event $name the name or instance of event
     * @param string|EventListener $listener the name or instance of event listener
     * @return $this
     */
    public function register($name, $listener)
    {
        EventCollector::addListener($name, $listener);
        return $this;
    }

    /**
     * execute the listeners
     *
     * @param array $listeners the list of listeners
     * @param null $eventName the name of event
     * @throws EventListenerException
     * @return array
     */
    private function runListenersHandle(array $listeners = [], $eventName = null)
    {
        $response = [];
        foreach ($listeners as $listener) {
            $listener = new $listener();
            if ($listener instanceof EventListener) {
                $response[] = call_user_func_array([$listener, 'handle'], [$eventName]);
            } else {
                throw new EventListenerException(sprintf('%s listener s�n�f� EventListenerInterface\' e sahip olmal�d�r',
                    get_class($listener)));
            }
        }
        return $response;
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
            throw new EventNameException('Event ad� ge�erli bir string de�eri olmal�d�r');
        }
        return $this->listeners[$eventName];
    }

    /**
     * check the isset any listener
     *
     * @param string $eventName the name of event
     * @return bool
     */
    public function hasListiner($eventName = '')
    {
        return isset($this->listeners[$eventName]);
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