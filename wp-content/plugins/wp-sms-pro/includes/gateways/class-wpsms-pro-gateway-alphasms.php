<?php

namespace WP_SMS\Gateway;

use Exception;
use WP_Error;
use WP_SMS\Gateway;
class alphasms extends Gateway
{
    private $wsdl_link = "https://alphasms.com.ua/api/json.php";
    public $unitrial = \false;
    public $unit;
    public $flash = "disable";
    public $isflash = \false;
    public function __construct()
    {
        parent::__construct();
        $this->bulk_send = \false;
        $this->supportMedia = \false;
        $this->supportIncoming = \false;
        $this->gatewayFields = ['key' => ['id' => 'key', 'name' => __('API Key', 'wp-sms'), 'desc' => __('Enter your API KEY', 'wp-sms')]];
    }
    public function SendSMS()
    {
        /**
         * Modify sender number
         *
         * @param string $this - >from sender number.
         *
         * @since 3.4
         *
         */
        $this->from = apply_filters('wp_sms_from', $this->from);
        /**
         * Modify Receiver number
         *
         * @param array $this - >to receiver number
         *
         * @since 3.4
         *
         */
        $this->to = apply_filters('wp_sms_to', $this->to);
        /**
         * Modify text message
         *
         * @param string $this - >msg text message.
         *
         * @since 3.4
         *
         */
        $this->msg = apply_filters('wp_sms_msg', $this->msg);
        try {
            $params = array('headers' => array('Content-Type' => 'application/json'), 'body' => wp_json_encode(['auth' => $this->key, 'data' => array(array('type' => 'sms', 'id' => 100500, 'phone' => \implode(',', $this->to), 'sms_message' => $this->msg, 'sms_signature' => 'SMSTest'))]));
            $response = $this->request('POST', $this->wsdl_link, [], $params, \false);
            if (isset($response->error)) {
                throw new Exception($response->error);
            }
            if (isset($response->data[0]->error)) {
                throw new Exception($response->data[0]->error);
            }
            $this->log($this->from, $this->msg, $this->to, $response);
            /**
             * Run hook after send sms.
             *
             * @param string $result result output.
             *
             * @since 2.4
             *
             */
            do_action('wp_sms_send', $response);
            return $response;
        } catch (Exception $e) {
            $this->log($this->from, $this->msg, $this->to, $e->getMessage(), 'error');
            return new WP_Error('send-sms', $e->getMessage());
        }
    }
    public function GetCredit()
    {
        try {
            $params = array('headers' => array('Content-Type' => 'application/json'), 'body' => wp_json_encode(['auth' => $this->key, 'data' => array(array('type' => 'balance'))]));
            $response = $this->request('POST', $this->wsdl_link, [], $params, \false);
            if (isset($response->error)) {
                throw new Exception($response->error);
            }
            return $response->data[0]->data->amount;
        } catch (Exception $e) {
            return new WP_Error('account-credit', $e->getMessage());
        }
    }
}
