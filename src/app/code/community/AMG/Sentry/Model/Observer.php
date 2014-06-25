<?php

class AMG_Sentry_Model_Observer {

    public $error_handler;

    public function initErrorHandler()
    {
        // Instantiate a new client
        $client = Mage::getSingleton('amg-sentry/client');

        // Install error handlers and shutdown function to catch fatal errors
        $error_handler = new Raven_ErrorHandler($client);

        return $error_handler;
    }

    public function initSentryLogger($observer)
    {
        if (!Mage::getStoreConfigFlag('dev/amg-sentry/active')) {
            return $this;
        }

        // Init only once
        if ($this->error_handler) {
            return $this;
        }

        // Create Error handler and store it
        $error_handler = $this->initErrorHandler();
        $this->error_handler = $error_handler;

        if (!$error_handler) {
            return $this;
        }

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
