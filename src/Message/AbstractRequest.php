<?php

/**
 * Stripe Abstract Request.
 */
namespace Omnipay\Bpoint\Message;

/**
 * You can use any of the cards listed at https://stripe.com/docs/testing
 * for testing.
 *
 * @see \Omnipay\Bpoint\Gateway
 * @link https://bpoint.com/docs/api
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /**
     * Live or Test Endpoint URL.
     *
     * @var string URL
     */
    protected $endpoint = 'https://www.bpoint.com.au/webapi/v2/';

    public function getMerchantReference()
    {
        return $this->getParameter('merchantReference');
    }

    public function setMerchantReference($value)
    {
        return $this->setParameter('merchantReference', $value);
    }

    public function getEndpoint() {
      return self::$endPoint;
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
            null,
            $data
        );
        $httpResponse = $httpRequest
            ->setHeader('Authorization', 'Basic '.base64_encode($this->getUsername()."|".$this->getMerchantNumber().':'.$this->getPassword()))
            ->send();

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
        $card->validate();

        $data = [];
        $data["CardHolderName"] = $card->getName();
        $data["CardNumber"] = $card->getNumber();
        if ($card->getCvv()) {
            $data['CVN'] = $card->getCvv();
        }
        $data["ExpiryDate"] = $card->getExpiryMonth().$card->getExpiryYear();

        return $data;
    }

    abstract function getAction();


    public function getData()
    {
        $this->validate('amount', 'currency');

        $data = [];

        $data["Action"] = $this->getAction();
        $data["Amount"] = $this->getAmountInteger();
        $data["AmountOriginal"] = $this->getAmountOriginal();
        $data["AmountSurcharge"] = $this->getAmountSurcharge();

        $card = $this->getCard();

        $data["CardDetails"] = $this->getCardData();
        $data["Customer"] = [];

        $data["Customer"]["PersonalDetails"] = [
          "FirstName" => $card->getFirstName(),
          "LastName" => $card->getLastName()
        ];

        $data["Customer"]["Address"] = [
          "AddressLine1" : "123 Fake Street",
          "AddressLine2" : "",
          "AddressLine3" : "",
          "City" : "Melbourne",
          "CountryCode" : "AUS",
          "PostCode" : "3000",
          "State" : "VIC"
        ];
        $data["Customer"]["ContactDetails"] = [
          "EmailAddress" : "john.smith@email.com",
          "FaxNumber" : "",
          "HomePhoneNumber" : "",
          "MobilePhoneNumber" : "",
          "WorkPhoneNumber" : ""
        ];

        $data["Currency"] = $this->getCurrency();

        $data["OriginalTxnNumber"] = null;
       #$data["Crn1"] = "test crn1";
       #$data["Crn2"] = "test crn2";
       #$data["Crn3"] = "test crn3";
        $data["EmailAddress"] = $card->getEmail();
        $data["BillerCode"] = null;
        $data["TestMode"] = $this->getTestMode();
        $data["TokenisationMode"] = 0;
        $data["StoreCard"] = false;
        $data["SubType"] = "single";
        $data["Type"] = "internet";


        $data["MerchantReference"] = $this->getMerchantReference();

        #$data["Order"] = [];

        return $data;




        $data = array();

        $data['amount'] = $this->getAmountInteger();
        $data['currency'] = strtolower($this->getCurrency());
        $data['description'] = $this->getDescription();
        $data['metadata'] = $this->getMetadata();
        $data['capture'] = 'false';

        if ($this->getStatementDescriptor()) {
            $data['statement_descriptor'] = $this->getStatementDescriptor();
        }
        if ($this->getDestination()) {
            $data['destination'] = $this->getDestination();
        }

        if ($this->getApplicationFee()) {
            $data['application_fee'] = $this->getApplicationFeeInteger();
        }

        if ($this->getReceiptEmail()) {
            $data['receipt_email'] = $this->getReceiptEmail();
        }

        if ($this->getSource()) {
            $data['source'] = $this->getSource();
        } elseif ($this->getCardReference()) {
            $data['source'] = $this->getCardReference();
            if ($this->getCustomerReference()) {
                $data['customer'] = $this->getCustomerReference();
            }
        } elseif ($this->getToken()) {
            $data['source'] = $this->getToken();
            if ($this->getCustomerReference()) {
                $data['customer'] = $this->getCustomerReference();
            }
        } elseif ($this->getCard()) {
            $data['source'] = $this->getCardData();
        } elseif ($this->getCustomerReference()) {
            $data['customer'] = $this->getCustomerReference();
        } else {
            // one of cardReference, token, or card is required
            $this->validate('source');
        }

        return $data;
    }
}

