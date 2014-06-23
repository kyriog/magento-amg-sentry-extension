<?php

$ravenDir = Mage::getBaseDir() . DS . 'lib' . DS . 'Raven';
require_once($ravenDir . DS . 'Autoloader.php');
Raven_Autoloader::register();

class AMG_Sentry_Model_Client extends Raven_Client {

    function __construct() {
        $logger = Mage::getStoreConfig('dev/amg-sentry/logger');

        return parent::__construct(
            Mage::getStoreConfig('dev/amg-sentry/dsn'),
            array('logger' => $logger)
        );
    }

    /**
     * Get customer email.
     *
     * @return string Emailaddress
     */
    public function getCustomerEmail()
    {
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        return $customer->getEmail();
    }

    public function get_user_data(){
        $data = parent::get_user_data();
        $email = $this->getCustomerEmail();

        reset($data);
        $k = key($data);

        $data[$k]['email'] = $email;
        return $data;
    }
}
