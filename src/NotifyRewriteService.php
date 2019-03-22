<?php
/**
 * User: leeyifiei
 * Date: 17/4/14
 */

namespace cyr\junhuan;

class NotifyRewriteService extends PaybaseService
{

    public $request_url = PAY_REWRITE;
    public $transac_code = TRANSAC_REWRITE;

    public function notifyConfirm($orderNo, $transTime)
    {
        return $this->request([
            'orderNo' => $orderNo,
            'transTime' => $transTime,
        ], true, false);
    }

}
