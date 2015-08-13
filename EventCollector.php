<?php
    /**
     * Bu sınıf Eventler'i denl

     */
    namespace Anonym\Event;

    use Anonym\Application;

    /**
     * Class EventCollector
     * @package Anonym\Event
     */

    class EventCollector
    {

        /**
         * Uygulama nesnesini tutar
         *
         * @var Application
         */
        protected $application;

        /**
         * Dinleyicilerin listesini tutar
         *
         * @var array
         */
        private $listeners;


        /**
         * Uygulamayı atar
         *
         * @param Application $application AnonymFramework Application sınıfı
         * @param array $listeners Dinleyiciler
         */

        public function __construct(Application $application, array $listeners = [])
        {
            $this->setListeners($listeners);
            $this->application = $application;
        }

        /**
         * Dinleyicileri $this->listeners değerine atar
         *
         * @param array $listeners
         * @return $this
         */
        public function setListeners(array $listeners = [])
        {

            $this->listeners = $listeners;

            return $this;
        }

        /**
         * Dinleyicileri Döndürür
         *
         * @return mixed
         */
        public function getListeners()
        {

            return $this->listeners;
        }

        /**
         * Uygulama' yı döndürür
         *
         * @return Application
         */
        public function getApplication()
        {

            return $this->application;
        }
    }
