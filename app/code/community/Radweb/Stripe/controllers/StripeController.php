<?php

/**
 * Stripe payment
 *
 * @category    Radweb
 * @package     Radweb_Stripe
 */

class Radweb_Stripe_StripeController extends Mage_Core_Controller_Front_Action
{
    /**
     * Function save customer password to session for OPC
     * @return true|false
     */
    public function saveCustomerPasswordAction()
    {
        $customerPassword = $this->getRequest()->getParam('customer_password');
        if($customerPassword != '') {
            $_SESSION['customer_password'] = $customerPassword;
            $response['result'] = 1;
        } else {
            $response['result'] = 2;
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }

    /**
     * Function get token when use saved card of customer on OPC version 4.0.2
     * @return string Stripe Token
     */
    public function prepareSavePaymentAction()
    {
        if(isset($_POST['stripe_card'])) {
            $stripe_card = $_POST['stripe_card'];
            $customer = Mage::getSingleton('customer/session')->getCustomer();
            $customer_id = $customer->getId();

            $model = Mage::getModel('radweb_stripe/users');
            $stripe_user = $model->loadById($customer_id);

            return $stripe_user->getStripeToken();
        } else {
            return 0;
        }
    }
}
