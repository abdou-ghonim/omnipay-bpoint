# omnipay bpoint
**Commonwealth bank BPOINT payment processing payment using omnipay**

```php

        $gateway = Omnipay::create('BPoint');
        $gateway->setUsername('abc123');
        $gateway->setPassword('abc123');
        $gateway->setMerchantNumber('abc123');
   
        // testing 
        $gateway->setTestMode(true);
        
        
        $card = new CreditCard(array(
            'firstName' => 'Example',
            'lastName' => 'Customer',
            'number' => '5123456789012346',
            'expiryMonth' => '02',
            'expiryYear' => '25',
            'cvv' => '1213'
        ));
        
        $response = $gateway->purchase(
            [
                'amount' => '10.10',
                'currency' => 'AUD',
                'card' => $card,
            ]
        )->setCrn1('ABD')->send();

      
        if ($response->isSuccessful()) {
            // payment was successful: update database
            $sale_id = $response->getTransactionReference();
            $message = $response->getMessage();

        } else {
            // payment failed: display message to customer
            $msgData =  $response->getData();
            $msgMessage =  $response->getMessage();
            $msgCode =  $response->getCode();
            $msgErrors=  $response->getErrors();
        }

```

## Installation

add to your composer.json file  

"abdou-ghonim/omnipay-bpoint": "dev-master"


"repositories": [
    {
        "type": "vcs",
        "url":  "https://github.com/abdou-ghonim/omnipay-bpoint"
    }
]



