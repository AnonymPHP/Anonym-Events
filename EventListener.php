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

/**
 * the abstract class of event
 *
 * Class EventListener
 * @package Anonym\Components\Event
 */
abstract class EventListener
{

    /**
     * handle the event instance
     *
     * @return mixed
     */
    abstract public function handle();

}