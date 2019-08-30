<?php
/**
 * User: leeyifiei
 * Date: 17/5/9
 */

namespace cyr\junhuan;

class OrderQueryService extends PaybaseService
{
    public $request_url = PAY_XF_SEARCH;
    public $transac_code = TRANSAC_XF_QUERY_RES;

    public function query($orderNo)
    {
        return $this->request([
            'orderNo' => $orderNo,
        ]);
    }

}
