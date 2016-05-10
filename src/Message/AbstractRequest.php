<?php

/**
 * Stripe Abstract Request.
 */
namespace Omnipay\BPoint\Message;

/**
 * You can use any of the cards listed at https://stripe.com/docs/testing
 * for testing.
 *
 * @see \Omnipay\Bpoint\Gateway
 * @link https://bpoint.com/docs/api
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    public function getEndpoint() {
      return 'https://www.bpoint.com.au/webapi/v2/txns/';
    }

    public function getMerchantReference()
    {
        return $this->getParameter('merchantReference');
    }

    public function setMerchantReference($value)
    {
        return $this->setParameter('merchantReference', $value);
    }

    public function getAmountOriginal() {
      return $this->getParameter("amountOriginal");
    }

    public function setAmountOriginal($value) {
      return $this->setParameter('amountOriginal', $value);
    }

    public function getAmountSurcharge() {
      return $this->getParameter("amountSurcharge");
    }

    public function setAmountSurcharge($value) {
      return $this->setParameter('amountSurcharge', $value);
    }


    public function setCrn1($value) {
      return $this->setParameter('crn1', $value);
    }

    function setTransactionReference($value) {
      return $this->setParameter("transactionReference", $value);
    }

    function getTransactionReference() {
      return $this->getParameter("transactionReference");
    }

    function setOriginalTxnNumber($value) {
      return $this->setParameter("originalTxnNumber", $value);
    }

    function getOriginalTxnNumber() {
      return $this->getParameter("originalTxnNumber");
    }

    public function getCrn1() {
      return $this->getParameter('crn1');
    }


    /**
     * Get HTTP Method.
     *
     * This is nearly always POST but can be over-ridden in sub classes.
     *
     * @return string
     */
    public function getHttpMethod()
    {
        return 'POST';
    }

    public function sendData($data)
    {
        // don't throw exceptions for 4xx errors
       $this->httpClient->getEventDispatcher()->addListener(
           'request.error',
           function ($event) {
               if ($event['response']->isClientError()) {
                   $event->stopPropagation();
              }
           }
       );

        $httpRequest = $this->httpClient->createRequest(
            $this->getHttpMethod(),
            $this->getEndpoint(),
            null
        );

        $encoded_data = json_encode(["TxnReq" => $data]);

        $httpRequest->setBody($encoded_data, "application/json");

        $auth_string = $this->getUsername()."|".$this->getMerchantNumber().':'.$this->getPassword();
        $httpResponse = $httpRequest
            ->setHeader('Authorization', base64_encode($auth_string))
            ->send();

        var_dump((string)$httpResponse->getBody());

        return $this->response = new Response($this, $httpResponse->json());
    }

    /**
     * Get the card data.
     *
     * Because the stripe gateway uses a common format for passing
     * card data to the API, this function can be called to get the
     * data from the associated card object in the format that the
     * API requires.
     *
     * @return array
     */
    protected function getCardData()
    {
        $card = $this->getCard();
        if($card) $card->validate();

        $data = [];
        $data["CardHolderName"] = $card->getName();
        $data["CardNumber"] = $card->getNumber();
        if ($card->getCvv()) {
            $data['CVN'] = $card->getCvv();
        }
        $data["ExpiryDate"] = $card->getExpiryDate("my");

        return $data;
    }

    abstract function getAction();


    public function getData()
    {
        $this->validate("merchantNumber", "password", "username", 'amount', 'currency');

        $data = [];

        $data["Action"] = $this->getAction();
        $data["Amount"] = $this->getAmountInteger();

        $data["AmountOriginal"] = (int)$this->getAmountOriginal();
        $data["AmountSurcharge"] = (int)$this->getAmountSurcharge();

        $card = $this->getCard();

        if($card) {
          $data["Customer"] = [];
          $data["Customer"]["PersonalDetails"] = [
            "FirstName" => $card->getFirstName(),
            "LastName" => $card->getLastName()
          ];
          $data["EmailAddress"] = $card->getEmail();

          $data["Customer"]["ContactDetails"] = [
            "EmailAddress" => $card->getEmail(),
          ];
        }

        $data["Currency"] = $this->getCurrency();

      #$data["OriginalTxnNumber"] = null;
        $data["Crn1"] = $this->getCrn1();
       #$data["Crn2"] = "test crn2";
       #$data["Crn3"] = "test crn3";
        $data["BillerCode"] = null;
        $data["TestMode"] = $this->getTestMode();
        $data["TokenisationMode"] = 0;
        $data["StoreCard"] = false;
        $data["SubType"] = "single";
        $data["Type"] = "internet";


        $data["MerchantReference"] = $this->getMerchantReference();

        return $data;
    }

    public function getUsername()
    {
        return $this->getParameter('username');
    }

    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    public function getMerchantNumber()
    {
        return $this->getParameter('merchantNumber');
    }

    public function setMerchantNumber($value)
    {
        return $this->setParameter('merchantNumber', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }
}


