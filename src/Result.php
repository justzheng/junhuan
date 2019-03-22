<?php
/**
 * User: leeyifiei
 * Date: 17/4/14
 */

namespace cyr\junhuan;

class Result
{
    public $respCode;

    public $respData;

    public $respMsg;

    public $signature;

    public function validate($public_key_path)
    {
        $decode_respdata = base64_decode($this->respData);

        $sign_str = $this->respCode . $this->respMsg . $decode_respdata;

        if (RsaHelper::validate($sign_str, base64_decode(base64_decode($this->signature)), $public_key_path)) {
            $this->respData = $decode_respdata;

            return true;
        }

        return false;
    }
}
