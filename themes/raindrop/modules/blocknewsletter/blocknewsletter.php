<?php

if (!defined('_PS_VERSION_'))
    exit;

class BlocknewsletterBSK extends Blocknewsletter {

    private function isNewsletterRegistered($customerEmail) {
        $sql = 'SELECT `email`
				FROM ' . _DB_PREFIX_ . 'newsletter
				WHERE `email` = \'' . pSQL($customerEmail) . '\'
				AND id_shop = ' . $this->context->shop->id;

        if (Db::getInstance()->getRow($sql))
            return self::GUEST_REGISTERED;

        $sql = 'SELECT `newsletter`
				FROM ' . _DB_PREFIX_ . 'customer
				WHERE `email` = \'' . pSQL($customerEmail) . '\'
				AND id_shop = ' . $this->context->shop->id;

        if (!$registered = Db::getInstance()->getRow($sql))
            return self::GUEST_NOT_REGISTERED;

        if ($registered['newsletter'] == '1')
            return self::CUSTOMER_REGISTERED;

        return self::CUSTOMER_NOT_REGISTERED;
    }

    /**
     * Register in block newsletter
     */
    private function newsletterRegistration() {
        if (empty($_POST['email']) || !Validate::isEmail($_POST['email']))
            return $this->error = $this->l('Invalid email address.');

        /* Unsubscription */
        else if ($_POST['action'] == '1') {
            $register_status = $this->isNewsletterRegistered($_POST['email']);

            if ($register_status < 1)
                return $this->error = $this->l('This email address is not registered.');

            if (!$this->unregister($_POST['email'], $register_status))
                return $this->error = $this->l('An error occurred while attempting to unsubscribe.');

            return $this->valid = $this->l('Unsubscription successful.');
        }
        /* Subscription */
        else if ($_POST['action'] == '0') {
            $register_status = $this->isNewsletterRegistered($_POST['email']);
            if ($register_status > 0)
                return $this->error = $this->l('This email address is already registered.');

            $email = pSQL($_POST['email']);
            if (!$this->isRegistered($register_status)) {
                if (Configuration::get('NW_VERIFICATION_EMAIL')) {
                    // create an unactive entry in the newsletter database
                    if ($register_status == self::GUEST_NOT_REGISTERED)
                        $this->registerGuest($email, false);

                    if (!$token = $this->getToken($email, $register_status))
                        return $this->error = $this->l('An error occurred during the subscription process.');

                    $this->sendVerificationEmail($email, $token);

                    return $this->valid = $this->l('A verification email has been sent. Please check your inbox.');
                }
                else {
                    if ($this->register($email, $register_status))
                        $this->valid = $this->l('You have successfully subscribed to this newsletter.');
                    else
                        return $this->error = $this->l('An error occurred during the subscription process.');

                    if ($code = Configuration::get('NW_VOUCHER_CODE'))
                        $this->sendVoucher($email, $code);

                    if (Configuration::get('NW_CONFIRMATION_EMAIL'))
                        $this->sendConfirmationEmail($email);
                }
            }
        }
    }

    public function hookDisplayBlueBlock($params) {
        if (Tools::isSubmit('submitNewsletter')) {
            $this->newsletterRegistration();
            if ($this->error) {
                $this->smarty->assign(array(
                    'color' => 'red',
                    'msg' => $this->error,
                    'nw_value' => isset($_POST['email']) ? pSQL($_POST['email']) : false,
                    'nw_error' => true,
                    'action' => $_POST['action']
                ));
            } else if ($this->valid) {
                $this->smarty->assign(array(
                    'color' => 'green',
                    'msg' => $this->valid,
                    'nw_error' => false
                ));
            }
        }
        $this->smarty->assign('this_path', $this->_path);

        return $this->display(__FILE__, 'blocknewsletter_home.tpl');
    }

}
