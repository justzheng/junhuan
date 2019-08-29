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

    public function validate($public_key_path,$data)
    {
        $decode_respdata = $data;
        $sign_str = $this->respCode . $this->respMsg . $decode_respdata;
        if (RsaHelper::validate($sign_str, base64_decode(base64_decode($this->signature)), $public_key_path)) {
            $this->respData = $decode_respdata;

            return true;
        }

        return false;
    }

    public function xfvalidate($public_key_path,$data)
    {
        //用公钥对接口signature验签
        //数据返回的签名;
        //私钥解密，获得加密前原数据
        //$res = RsaHelper::decsign($this->respData,$private_key_path);
        $str = $this->respCode.$this->respMsg.$data;
        if(RsaHelper::validate($str,base64_decode(base64_decode($this->signature)),$public_key_path)){
            return true;
        }
        return false;
    }
}
