<?php

class AMG_Sentry_Model_Observer {

    public $error_handler;

    public function __construct()
    {
        if (Mage::getStoreConfigFlag('dev/amg-sentry/active')) {
            // Instantiate a new client
            $client = Mage::getSingleton('amg-sentry/client');

            // Install error handlers and shutdown function to catch fatal errors
            $this->error_handler = new Raven_ErrorHandler($client);
        }

        return $this;
    }

    public function initSentryLogger($observer){
        if ($error_handler = $this->error_handler) {
            // Check if we should log errors, warnings etc.
            if (Mage::getStoreConfigFlag('dev/amg-sentry/php-errors')) {
                $error_handler->registerErrorHandler();
            }

            // Check if we should log exceptions
            if (Mage::getStoreConfigFlag('dev/amg-sentry/php-exceptions')) {
                $error_handler->registerExceptionHandler();
            }

            // Check if we should log fatal errors
            if (Mage::getStoreConfigFlag('dev/amg-sentry/php-fatal-errors')) {
                $error_handler->registerShutdownFunction();
            }
        }

        return $this;
    }

    public function mageRunException($observer)
    {
        if ($error_handler = $this->error_handler) {
            $exception = $observer->getException();
            $error_handler->handleException($exception);
        }
        return $this;
    }
}
