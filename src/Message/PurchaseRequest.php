<?php

namespace Omnipay\BPoint\Message;

class PurchaseRequest extends AbstractRequest
{
    public function getAction() {
      return "payment";
    }

    public function getData()
    {
        $this->validate('amount', 'currency');

        $data = parent::getData();
        $data["CardDetails"] = $this->getCardData();
        return $data;
    }
}

