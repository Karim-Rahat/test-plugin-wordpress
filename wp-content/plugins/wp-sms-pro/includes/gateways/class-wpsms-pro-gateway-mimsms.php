<?php

namespace WP_SMS\Gateway;

use Exception;
use WP_Error;
class mimsms extends \WP_SMS\Gateway
{
    private $wsdl_link = "https://rapidapi.mimsms.com/smsapiv3";
    public $tariff = "http://www.mimsms.com/";
    public $unitrial = \false;
    public $unit;
    public $flash = "false";
    public $isflash = \false;
    public function __construct()
    {
        parent::__construct();
        $this->has_key = \true;
        $this->bulk_send = \true;
        $this->validateNumber = "e.g. 88017XXXXXXXX";
    }
    public function SendSMS()
    {
        /**
         * Modify sender number
         *
         * @param string $this ->from sender number.
         * @since 3.4
         *
         */
        $this->from = apply_filters('wp_sms_from', $this->from);
        /**
         * Modify Receiver number
         *
         * @param array $this ->to receiver number
         * @since 3.4
         *
         */
        $this->to = apply_filters('wp_sms_to', $this->to);
        /**
         * Modify text message
         *
         * @param string $this ->msg text message.
         * @since 3.4
         *
         */
        $this->msg = apply_filters('wp_sms_msg', $this->msg);
        // Get the credit.
        $credit = $this->GetCredit();
        // Check gateway credit
        if (is_wp_error($credit)) {
            // Log the result
            $this->log($this->from, $this->msg, $this->to, $credit->get_error_message(), 'error');
            return $credit;
        }
        $api_key = $this->has_key;
        $senderid = $this->from;
        $mobile = \implode(',', $this->to);
        $msg = \urlencode($this->msg);
        $response = wp_remote_get(add_query_arg(['apikey' => $api_key, 'sender' => $senderid, 'msisdn' => $mobile, 'smstext' => $msg], $this->wsdl_link));
        // Check gateway credit
        if (is_wp_error($response)) {
            // Log the result
            $this->log($this->from, $this->msg, $this->to, $response->get_error_message(), 'error');
            return new \WP_Error('send-sms', $response->get_error_message());
        }
        $response_code = wp_remote_retrieve_response_code($response);
        $response_status = \json_decode($response['body'])->response[0]->status;
        if ($response_code == '200') {
            // Check for errors
            if ($response_status !== 0) {
                throw new Exception($this->getErrorMessage($response_status));
            }
            $result = \sprintf(__("Successfully delivered with id %s", 'wp-sms'), \json_decode($response['body'])->response[0]->id);
            // Log the result
            $this->log($this->from, $this->msg, $this->to, $result);
            /**
             * Run hook after send sms.
             *
             * @param string $result result output.
             * @since 2.4
             *
             */
            do_action('wp_sms_send', $result);
            return $result;
        } else {
            // Log the result
            $this->log($this->from, $this->msg, $this->to, $response['body'], 'error');
            return new \WP_Error('send-sms', $response['body']);
        }
    }
    public function GetCredit()
    {
        // Check username and password
        if (!$this->username || !$this->password) {
            return new \WP_Error('account-credit', __('The User Name and Password for this gateway is not set', 'wp-sms-pro'));
        }
        $response = wp_remote_get(\sprintf('https://rapidapi.mimsms.com/getbalance?user=%s&password=%s', $this->username, $this->password));
        if (is_wp_error($response)) {
            return new \WP_Error('account-credit', $response->get_error_message());
        }
        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code == '200') {
            return \json_decode($response['body'])->response;
        } else {
            return new \WP_Error('account-credit', $response['body']);
        }
    }
    private function getErrorMessage($status)
    {
        switch ($status) {
            case 101:
                return 'Message length error';
            case 102:
                return 'Invalid sender';
            case 103:
                return 'Authentication Failure';
            case 104:
                return 'User Invalid';
            case 105:
                return 'Wrong MSISDN';
            case 106:
                return 'Wrong API key';
            case 107:
                return 'User Suspended';
            case 1000:
                return 'Low balance';
            case 2300:
                return 'Destination Route Error';
            case 3300:
                return 'Internal Error';
            case 2000:
            case 3000:
            case 4000:
                return 'Destination provider not available';
            default:
                return 'Unknown Error!';
        }
    }
}
