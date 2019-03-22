<?php
/**
 * User: leeyifiei
 * Date: 17/4/14
 */

namespace cyr\junhuan;

use yii\base\InvalidParamException;

class CertService extends PaybaseService
{

    public $request_url = PAY_GETCERT;
    public $transac_code = TRANSAC_GETCERT;

    public function getCert($order_no, $jdsbh, $jkbh)
    {
        if (!$jdsbh && !$jkbh) {
            throw new InvalidParamException('jdsbh and jkbh must have one!');
        }

        $data = [
            'orderNo' => $order_no,
        ];

        if ($jdsbh) {
            $data['decisionNum'] = $jdsbh;
        } else if ($jkbh) {
            $data['surveilNum'] = $jkbh;
        }

        $resp = $this->request($data);

        return $resp;
    }

}
