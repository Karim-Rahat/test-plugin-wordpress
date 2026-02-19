<?php

namespace WP_SMS\Gateway;

use Exception;
use WP_Error;
use WP_SMS\Gateway;
class awssns extends Gateway
{
    public $wsdl_link = "https://sns.%s.amazonaws.com/";
    public $unitrial = \false;
    public $unit;
    public $flash = "disable";
    public $isflash = \false;
    public function __construct()
    {
        parent::__construct();
        $this->validateNumber = "The destination phone number. Format with a '+' and country code e.g., +16175551212 (E.164 format).";
        $this->bulk_send = \false;
        $this->supportMedia = \false;
        $this->supportIncoming = \false;
        $this->gatewayFields = ['access_key' => ['id' => 'access_key', 'name' => __('Access Key', 'wp-sms'), 'desc' => __('Enter your Amazon Access Key', 'wp-sms')], 'secret_key' => ['id' => 'secret_key', 'name' => __('Secret Key', 'wp-sms'), 'desc' => __('Enter your Amazon Secret Key', 'wp-sms')], 'region' => ['id' => 'region', 'name' => __('Region', 'wp-sms'), 'desc' => __('Enter your Amazon Region e.g. us-east-1', 'wp-sms')]];
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
            $credit = $this->GetCredit();
            if (is_wp_error($credit)) {
                $this->log($this->from, $this->msg, $this->to, $credit->get_error_message(), 'error');
                return $credit;
            }
            $this->to = \implode(',', $this->to);
            $params = array('Message' => $this->msg, 'PhoneNumber' => $this->to);
            $response = $this->sendRequest('Publish', $params);
            if (isset($response->Error)) {
                throw new Exception($response->Error->Message);
            }
            if (!isset($response->PublishResult->MessageId)) {
                throw new Exception('Error: Unable to send message!');
            }
            $this->log($this->from, $this->msg, $this->to, $response->PublishResult->MessageId);
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
        if (empty($this->access_key) || empty($this->secret_key) || empty($this->region)) {
            return new WP_Error('account-credit', 'Please check your Amazon SNS settings');
        }
        return 'Unable to check balance!';
    }
    private function sendRequest($action, $params)
    {
        $params['Action'] = $action;
        $params['X-Amz-Date'] = \gmdate('Ymd\\THis\\Z');
        $params['X-Amz-Algorithm'] = 'AWS4-HMAC-SHA256';
        $params['X-Amz-Credential'] = $this->access_key . '/' . \gmdate('Ymd') . '/' . $this->region . '/sns/aws4_request';
        $params['X-Amz-SignedHeaders'] = 'host';
        $params['X-Amz-Signature'] = $this->generateSignature($params);
        // TODO - Resolve signature incompatibility issue
        $url = \sprintf($this->wsdl_link, $this->region);
        $url = $url . '?' . \http_build_query($params);
        $curl = \curl_init();
        \curl_setopt_array($curl, array(\CURLOPT_URL => $url, \CURLOPT_RETURNTRANSFER => \true, \CURLOPT_ENCODING => '', \CURLOPT_MAXREDIRS => 10, \CURLOPT_TIMEOUT => 0, \CURLOPT_FOLLOWLOCATION => \true, \CURLOPT_HTTP_VERSION => \CURL_HTTP_VERSION_1_1, \CURLOPT_CUSTOMREQUEST => 'POST'));
        $response = \curl_exec($curl);
        \curl_close($curl);
        return \simplexml_load_string($response);
    }
    private function generateSignature($params)
    {
        $canonicalRequest = $this->generateCanonicalRequest($params);
        $stringToSign = $this->generateStringToSign($canonicalRequest, $params);
        $dateKey = \hash_hmac('sha256', \substr($params['X-Amz-Date'], 0, 8), 'AWS4' . $this->secret_key, \true);
        $dateRegion = \hash_hmac('sha256', $this->region, $dateKey, \true);
        $dateService = \hash_hmac('sha256', 'sns', $dateRegion, \true);
        $signingKey = \hash_hmac('sha256', 'aws4_request', $dateService, \true);
        return \hash_hmac('sha256', $stringToSign, $signingKey);
    }
    private function generateCanonicalRequest($params)
    {
        $canonicalRequest = array();
        $canonicalRequest[] = 'POST';
        $canonicalRequest[] = '/';
        $canonicalRequest[] = '';
        \ksort($params);
        foreach ($params as $key => $value) {
            $canonicalRequest[] = $key . '=' . \rawurlencode($value);
        }
        $canonicalRequest = \implode("\n", $canonicalRequest);
        return \hash('sha256', $canonicalRequest);
    }
    private function generateStringToSign($canonicalRequest, $params)
    {
        $stringToSign = array();
        $stringToSign[] = $params['X-Amz-Algorithm'];
        $stringToSign[] = $params['X-Amz-Date'];
        $stringToSign[] = \substr($params['X-Amz-Date'], 0, 8) . '/' . $this->region . '/sns/aws4_request';
        $stringToSign[] = $canonicalRequest;
        return \implode("\n", $stringToSign);
    }
}
