<?php

namespace Omnipay\BPoint\Message;

class CaptureRequest extends AbstractRequest
{

    public function getData()
    {
        $this->validate('amount', 'currency', "originalTxnNumber");

        $data = parent::getData();
        $data["OriginalTxnNumber"] = $this->getOriginalTxnNumber();

        return $data;
    }
}

