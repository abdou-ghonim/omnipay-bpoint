<?php

namespace Omnipay\Bpoint\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Bpoint\Gateway;

/**
 * BPOINT Response
 *
 * Generic BPOINT response message. 
 */
class Response extends AbstractResponse implements ResponseInterface
{
    public static $MESSAGES = array(
        '0' => 'Success',
        '1' => 'Invalid parameter in_merchant_number',
        '2' => 'Invalid parameter in_merchant_username',
        '3' => 'Invalid parameter in_merchant_password',
        '4' => 'Invalid parameter in_ip_address',
        '5' => 'Invalid parameter in_amount',
        '6' => 'Amount cannot be zero or less than zero',
        '7' => 'Invalid parameter in_merchant_reference',
        '8' => 'Invalid parameter in_crn1 (Transaction ID)',
        '9' => 'Invalid parameter in_crn2',
        '10' => 'Invalid parameter in_crn3',
        '11' => 'Invalid parameter in_credit_card',
        '12' => 'Invalid parameter in_expiry_month',
        '13' => 'Invalid parameter in_expiry_year',
        '14' => 'Invalid parameter in_cvv',
        '15' => 'Invalid parameter in_receipt_page_url',
        '16' => 'Invalid parameter in_response_code',
        '17' => 'Invalid parameter in_bank_response_code',
        '18' => 'Invalid parameter in_auth_result',
        '19' => 'Invalid parameter in_txn_number',
        '20' => 'Invalid parameter in_receipt_number',
        '21' => 'Invalid parameter in_settlement_date',
        '22' => 'Invalid parameter in_expiry_date',
        '23' => 'Invalid parameter in_account_number',
        '24' => 'Invalid parameter in_payment_date',
        '25' => 'Invalid parameter in_pay_token / Invalid parameter in_token',
        '26' => 'Invalid parameter in_verify_token',
        '27' => 'The merchant number supplied is not present in the system',
        '28' => 'The biller code supplied is not present in the system',
        '29' => 'Invalid login details supplied',
        '30' => 'Signature verification failed (Invalid signature / data supplied)',
        '31' => 'Invalid session request (The session details not found in the system)',
        '32' => 'Invalid parameter in_dvtoken',
        '33' => 'The data vault token number supplied is not present in the system',
        '34' => 'Invalid parameter in_account_name',
        '35' => 'Invalid parameter in_bsb_number',
        '36' => 'Invalid parameter in_account_number',
        '37' => 'User has not accepted Direct Debit terms and conditions',
        '100' => 'System error'
    );
    
    public function __construct($request, $data)
    {
        // Parse response from poor new-line separated format into a nice array instead. 
        $data_parsed = json_decode($data);

        // Send newly massaged data off to the parent constructor as per usual.
        parent::__construct($request,$data_parsed);
    }

    public function isSuccessful()
    {
        return ($this->data['APIResponse']["ResponseCode"] == '0');
    }

    public function getTransactionReference()
    {
        return isset($this->data['TxnResp']["TxnNumber"]) ? (string) $this->data['TxnResp']["TxnNumber"] : null;
    }

    public function getToken()
    {
        return isset($this->data['out_pay_token']) ? (string) $this->data['out_pay_token'] : null;   
    }

    public function getMessage()
    {
        return static::$MESSAGES[$this->getCode()];
    }

    public function getAmount()
    {
        return $this->data['out_amount'];
    }

    public function getCode()
    {
        return $this->data['out_request_resp_code'];
    }

    public function getData()
    {
        return $this->data;
    }

    public function isRedirect() {
        return false;
    }


}
