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
#       '0' => 'Success',
#       '1' => 'Invalid parameter in_merchant_number',
#       '2' => 'Invalid parameter in_merchant_username',
#       '3' => 'Invalid parameter in_merchant_password',
#       '4' => 'Invalid parameter in_ip_address',
#       '5' => 'Invalid parameter in_amount',
#       '6' => 'Amount cannot be zero or less than zero',
#       '7' => 'Invalid parameter in_merchant_reference',
#       '8' => 'Invalid parameter in_crn1 (Transaction ID)',
#       '9' => 'Invalid parameter in_crn2',
#       '10' => 'Invalid parameter in_crn3',
#       '11' => 'Invalid parameter in_credit_card',
#       '12' => 'Invalid parameter in_expiry_month',
#       '13' => 'Invalid parameter in_expiry_year',
#       '14' => 'Invalid parameter in_cvv',
#       '15' => 'Invalid parameter in_receipt_page_url',
#       '16' => 'Invalid parameter in_response_code',
#       '17' => 'Invalid parameter in_bank_response_code',
#       '18' => 'Invalid parameter in_auth_result',
#       '19' => 'Invalid parameter in_txn_number',
#       '20' => 'Invalid parameter in_receipt_number',
#       '21' => 'Invalid parameter in_settlement_date',
#       '22' => 'Invalid parameter in_expiry_date',
#       '23' => 'Invalid parameter in_account_number',
#       '24' => 'Invalid parameter in_payment_date',
#       '25' => 'Invalid parameter in_pay_token / Invalid parameter in_token',
#       '26' => 'Invalid parameter in_verify_token',
#       '27' => 'The merchant number supplied is not present in the system',
#       '28' => 'The biller code supplied is not present in the system',
#       '29' => 'Invalid login details supplied',
#       '30' => 'Signature verification failed (Invalid signature / data supplied)',
#       '31' => 'Invalid session request (The session details not found in the system)',
#       '32' => 'Invalid parameter in_dvtoken',
#       '33' => 'The data vault token number supplied is not present in the system',
#       '34' => 'Invalid parameter in_account_name',
#       '35' => 'Invalid parameter in_bsb_number',
#       '36' => 'Invalid parameter in_account_number',
#       '37' => 'User has not accepted Direct Debit terms and conditions',
#       '100' => 'System error'

        "0" =>	"Success",
        "1" =>	"Invalid credentials",
        "2" =>	"Invalid permissions",
        "3" =>	"User not found",
        "100" =>	"Invalid field: original amount",
        "101" =>	"Invalid field: action",
        "102" =>	"Invalid field: type",
        "103" =>	"Invalid field: subtype",
        "104" =>	"Invalid field: merchant number",
        "105" =>	"Invalid field: biller code",
        "106" =>	"Invalid field: Crn1",
        "107" =>	"Invalid field: Crn2",
        "108" =>	"Invalid field: Crn3",
        "109" =>	"Invalid field: currency",
        "110" =>	"Invalid field: amount",
        "111" =>	"Invalid field: merchant reference",
        "112" =>	"Invalid field: card number",
        "113" =>	"Invalid field: cardholder name",
        "114" =>	"Invalid field: expiry date",
        "115" =>	"Invalid field: CVN",
        "116" =>	"Invalid field: web hook URL",
        "117" =>	"Invalid field: redirection URL",
        "118" =>	"Invalid field: transaction number",
        "119" =>	"Invalid field: original transaction number",
        "120" =>	"Invalid field: receipt number",
        "121" =>	"Invalid field: settlement date",
        "122" =>	"Invalid field: masked card number",
        "123" =>	"Invalid field: DVToken",
        "124" =>	"Invalid field: bank account number",
        "125" =>	"Invalid field: BSB number",
        "126" =>	"Invalid field: bank account name",
        "127" =>	"Invalid field: email address",
        "128" =>	"Invalid field: store card",
        "131" =>	"Invalid field: surcharge amount",
        "201" =>	"Transaction not found",
        "202" =>	"DVToken not found",
        "203" =>	"Transaction type cannot be tokenised",
        "204" =>	"Transactions cannot be tokenised because cardholder has not given permission",
        "205" =>	"Biller code not found",
        "206" =>	"Session not found",
        "207" =>	"Invalid session",
        "208" =>	"Transaction must be approved to be tokenised",
        "209" =>	"Search returned no results",
        "210" =>	"Merchant details not found",
        "211" =>	"Merchant account settings not found",
        "300" =>	"Follow redirection",
        "900" =>	"One or more sub-systems are currently unavailable",
        "999" =>	"Fatal error",
    );
    
    public function isSuccessful()
    {
        return ($this->data['TxnResp']["ResponseCode"] == '0');
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
        return $this->data["TxnResp"]["ResponseText"];
    }

    public function getAmount()
    {
        return $this->data['TxnResp']["Amount"];
    }

    public function getCode()
    {
        return $this->data['TxnResp']["ResponseCode"];
    }

    public function getData()
    {
        return $this->data;
    }

    public function isRedirect() {
        return false;
    }


}
