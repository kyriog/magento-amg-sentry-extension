<?php

class AMG_Sentry_Model_Observer {

    public function initSentryLogger($observer){
        if (Mage::getStoreConfigFlag('dev/amg-sentry/active')) {
            // Instantiate a new client
            $client = Mage::getSingleton('amg-sentry/client');

            // Install error handlers and shutdown function to catch fatal errors
            $error_handler = new Raven_ErrorHandler($client);

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
    }
}
