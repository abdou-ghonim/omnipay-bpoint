<?php

namespace Omnipay\BPoint\Message;

class RefundRequest extends AbstractRequest
{
    public function getAction() {
      return "refund";
    }

    public function getData()
    {
        $this->validate('amount', "crn1", "originalTxnNumber");

        $data = parent::getData();
        $data["OriginalTxnNumber"] = $this->getOriginalTxnNumber() ?: $this->getTransactionReference();

        return $data;
    }
}

