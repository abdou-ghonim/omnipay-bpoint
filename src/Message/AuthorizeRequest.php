<?php

namespace Omnipay\BPoint\Message;

class AuthorizeRequest extends AbstractRequest
{
    public function getAction() {
      return "preauth";
    }

    public function getData()
    {
        $this->validate('amount', 'currency');

        $data = parent::getData();
        $data["CardDetails"] = $this->getCardData();

        return $data;
    }
}

