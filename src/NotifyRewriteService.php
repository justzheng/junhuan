<?php
/**
 * User: leeyifiei
 * Date: 17/4/14
 */

namespace cyr\junhuan;

class NotifyRewriteService extends PaybaseService
{

    public $request_url = PAY_XF_SEARCH;
    public $transac_code = TRANSAC_XF_JGTZ;

    public $orderNo;
    public $xfksbh;
    public $xfksrq;
    public $jszhm;
    public $jkbh;
    public $jdsbh;
    public $result;
    public $msg;

    public function notifyConfirm()
    {
        return $this->request([
            'orderNo' => $this->orderNo,
            'xfksbh' => $this->xfksbh,
            'xfksrq' => $this->xfksrq,
            'jszhm' => $this->jszhm,
            'jkbh' => $this->jkbh,
            'jdsbh' => $this->jdsbh,
            'result' => $this->result,
            'msg' => $this->msg,
        ], true, false);
    }

}
