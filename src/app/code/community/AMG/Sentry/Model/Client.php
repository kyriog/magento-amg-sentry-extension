<?php

$ravenDir = Mage::getBaseDir() . DS . 'lib' . DS . 'Raven';
require_once($ravenDir . DS . 'Autoloader.php');
Raven_Autoloader::register();

class AMG_Sentry_Model_Client extends Raven_Client {
    private $userData = array();

    function __construct() {
        $options = array('logger' => Mage::getStoreConfig('dev/amg-sentry/logger'));

        $cacert = trim(Mage::getStoreConfig('dev/amg-sentry/cacert'));
        if($cacert) {
            $options['ca_cert'] = Mage::getBaseDir() . DS . $cacert;
        }

        return parent::__construct(Mage::getStoreConfig('dev/amg-sentry/dsn'), $options);
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

    public function setUserData($key, $value)
    {
        $this->userData[$key] = $value;
        return $this;
    }

    public function get_user_data(){
        $data = parent::get_user_data();
        $email = $this->getCustomerEmail();

        reset($data);
        $k = key($data);

        $data[$k]['email'] = $email;

        foreach($this->userData as $key => $value)
        {
            $data[$k][$key] = $value;
        }

        return $data;
    }
}
