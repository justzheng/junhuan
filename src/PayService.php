<?php
/**
 * User: leeyifiei
 * Date: 17/4/14
 */

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


    public function createFeeData()
    {
        return new FeeData();
    }

    public function setFeeData($feeData)
    {
        $this->feeData[] = (array)$feeData;
    }

}

class FeeData
{

    public $eBillCode;
    public $orgUnicode;
    public $note1;
    public $note2;
    public $sum;
    public $payerName;
    public $chrgDetail;

    public function setNote1($jdsbh, $hphm, $wfsj, $fkje, $znj, $cjjg, $cljg, $dsrxm)
    {
        $args = func_get_args();

        $this->note1 = implode('|', $args);
    }

    public function setNote2($jkbh, $hphm, $wfsj, $fkje, $znj, $cjjg, $cljg, $dsrxm)
    {
        $args = func_get_args();

        $this->note2 = implode('|', $args);
    }
}

