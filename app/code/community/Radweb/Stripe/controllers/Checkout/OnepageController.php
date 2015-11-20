<?php

require_once(Mage::getModuleDir('controllers','Mage_Checkout').DS.'OnepageController.php');
class Radweb_Stripe_Checkout_OnepageController extends Mage_Checkout_OnepageController
{
	/**
     * Save payment ajax action
     *
     * Sets either redirect or a JSON response
     */
    public function savePaymentAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        try {
            if (!$this->getRequest()->isPost()) {
                $this->_ajaxRedirectResponse();
                return;
            }

            $data = $this->getRequest()->getPost('payment', array());
            $result = $this->getOnepage()->savePayment($data);

            // get section and redirect data
            $redirectUrl = $this->getOnepage()->getQuote()->getPayment()->getCheckoutRedirectUrl();
            if (empty($result['error']) && !$redirectUrl) {
                $this->loadLayout('checkout_onepage_review');
                $result['goto_section'] = 'review';
                $result['update_section'] = array(
                    'name' => 'review',
                    'html' => $this->_getReviewHtml()
                );
            }
            if ($redirectUrl) {
                $result['redirect'] = $redirectUrl;
            }
        } catch (Mage_Payment_Exception $e) {
            if ($e->getFields()) {
                $result['fields'] = $e->getFields();
            }
            $result['error'] = $e->getMessage();
        } catch (Mage_Core_Exception $e) {
            $result['error'] = $e->getMessage();
        } catch (Exception $e) {
            Mage::logException($e);
            $result['error'] = $this->__('Unable to set Payment Method.');
        }

        if($data['method'] == 'radweb_stripe_stripe') {
        	if(isset($_POST['stripeToken'])) {
            	$token = $_POST['stripeToken'];
	        } else {
	            $token = '';
	        }

	        if(!empty($token)) {
	            try {
	                $transaction = Stripe_Token::retrieve($token);
	            } catch (Exception $e) {
	                print_r($e);
	            }

	            $card = $transaction->card;
	            $checks = $card->__toJSON();
	            $obj = json_decode($checks);
	            $last = $obj->{'last4'};
	            $type = $obj->{'brand'};
	            $exp_month = $obj->{'exp_month'};
	            $exp_year = $obj->{'exp_year'};
	            $owner = $obj->{'name'};
	        } else {
	            if(isset($_POST['stripe_card'])) {
	                $stripe_card = $_POST['stripe_card'];
	                $customer = Mage::getSingleton('customer/session')->getCustomer();
	                $customer_id = $customer->getId();

	                $model = Mage::getModel('radweb_stripe/users');
	                $stripe_user = $model->loadById($customer_id);
	                $customer_token = $stripe_user->getCustomerToken();
	                if($customer_token != null) {
	                    $stripeCustomer = Stripe_Customer::retrieve($customer_token, Stripe::getApiKey());
	                    $cards = $stripeCustomer->cards->data;
	                    if($stripe_card != '-1') {
	                        $card = $cards[$stripe_card];
	                        $exp_year = $card->exp_year;
	                        $exp_month = $card->exp_month;
	                        $owner = $card->name;
	                        $last = $card->last4;
	                        $type = $card->brand;
	                    }
	                }
	            }
	        }
	        $payment = Mage::getSingleton('checkout/session')->getQuote()->getPayment();
	        $payment->addData(
	            array('cc_exp_year' => $exp_year,
	                  'cc_exp_month' => $exp_month,
	                  'cc_owner' => $owner,
	                  'cc_last4' => $last,
	                  'cc_type' => $type
	            )
	        );
        	$payment->save();
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }
	
	 /**
     * Save checkout billing address
     */
    public function saveBillingAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('billing', array());
            if($data['customer_password'] != '') {
            	$_SESSION['customer_password'] = $data['customer_password'];
            }
            $customerAddressId = $this->getRequest()->getPost('billing_address_id', false);

            if (isset($data['email'])) {
                $data['email'] = trim($data['email']);
            }
            $result = $this->getOnepage()->saveBilling($data, $customerAddressId);

            if (!isset($result['error'])) {
                if ($this->getOnepage()->getQuote()->isVirtual()) {
                    $result['goto_section'] = 'payment';
                    $result['update_section'] = array(
                        'name' => 'payment-method',
                        'html' => $this->_getPaymentMethodsHtml()
                    );
                } elseif (isset($data['use_for_shipping']) && $data['use_for_shipping'] == 1) {
                    $result['goto_section'] = 'shipping_method';
                    $result['update_section'] = array(
                        'name' => 'shipping-method',
                        'html' => $this->_getShippingMethodsHtml()
                    );

                    $result['allow_sections'] = array('shipping');
                    $result['duplicateBillingInfo'] = 'true';
                } else {
                    $result['goto_section'] = 'shipping';
                }
            }

            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }
}


