<?php
/**
 * User: leeyifiei
 * Date: 17/5/9
 */

namespace cyr\junhuan;

class OrderQueryService extends PaybaseService
{
    public $request_url = PAY_QUERY;
    public $transac_code = TRANSAC_QUERY;

    public function query($orderNo)
    {
        return $this->request([
            'orderNo' => $orderNo,
        ]);
    }

}
