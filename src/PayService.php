<?php

namespace cyr\junhuan;

class PayService extends PaybaseService
{
    public $request_url = PAY_XF_PAY;
    public $transac_code = TRANSAC_XF_JF;

    public $orderNo;
    public $xfksbh;
    public $xfksrq;
    public $openid;
    public $jszhm;
    public $jkbh;
    public $returnUrl;
    public $notifyUrl;

    public function getPayorderHtml()
    {
        return $this->request((array)$this, false, false);
    }

}

