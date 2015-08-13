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
use Anonym\Components\Event\EventCollector;
use App\Events\Event as EventDispatch;
use App\Events\EventListener;
/**
 *
 * Class Event
 * @package Anonym
 */
class Event
{

    /**
     * Çağrılan eventlerin listesini tutar
     *
     * @var EventDispatch
     */
    private $firing;


    /**
     * @var EventCollector
     */
    private $collector;


    /**
     * Ön tanımlı gelen event isimlerini tutar
     *
     * @var array
     */

    private $list;

    /**
     * Sınıfı başlatır
     *
     * @param \Anonym\Components\Event\EventCollector|null $collector
     */
    public function __construct(EventCollector $collector = null)
    {
        $this->setCollector($collector);
        $this->listeners = $this->getCollector()->getListeners();
    }

    /**
     * Event'i yürütür
     * $eventName 'sınıfın ismi veya direk sınıfın instance i olabilir
     *
     * @param string $eventName
     * @return array
     * @throws Exception
     */
    public function fire($eventName = '')
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
                        throw new Exception(sprintf('%s isimli bir ön tanımlı event bulunamadı', $eventName));
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

                throw new EventListenerException(sprintf('%s adındaki Event\' in herhangi bir dinleyicisi yok', $eventName));
            }
        } else {
            throw new EventException('Girdiğiniz Event, geçerli bir event değil');
        }
    }

    /**
     * Listener'ları yürütür
     *
     * @param array $listeners
     * @param null $eventName
     * @throws Exception
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

                throw new EventListenerException(sprintf('%s listener sınıfı EventListenerInterface\' e sahip olmalıdır',
                    get_class($listener)));
            }
        }

        return $response;
    }

    public function getListeners($eventName = '')
    {

        if (!is_string($eventName)) {
            throw new EventNameException('Event adı geçerli bir string değeri olmalıdır');
        }

        return $this->listeners[$eventName];
    }

    /**
     * Girilen event'in bir dinleyicisi varmı diye bakar
     *
     * @param string $eventName
     * @return bool
     */
    public function hasListiner($eventName = '')
    {

        return isset($this->listeners[$eventName]);
    }

    /**
     * En son çağrılan event'i döndürür
     *
     * @return mixed
     */
    public function firing()
    {
        return end($this->firing);
    }

    /**
     * @return EventDispatch
     */
    public function getFiring()
    {
        return $this->firing;
    }

    /**
     * @param EventDispatch $firing
     * @return Event
     */
    public function setFiring(EventDispatch $firing)
    {
        $this->firing = $firing;
        return $this;
    }

    /**
     * @return \Anonym\Components\Event\EventCollector
     */
    public function getCollector()
    {
        return $this->collector;
    }

    /**
     * @param \Anonym\Components\Event\EventCollector $collector
     * @return Event
     */
    public function setCollector(EventCollector $collector)
    {
        $this->collector = $collector;
        return $this;
    }

    /**
     * @return array
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @param array $list
     * @return Event
     */
    public function setList(array $list)
    {
        $this->list = $list;
        return $this;
    }


}
