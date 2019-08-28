<?php

namespace cyr\junhuan;

use yii\base\Object;
use yii\httpclient\Client;
use yii;

class PaybaseService extends Object
{
    public $site_code;
    public $version;
    public $device_type;
    public $charset;
    public $private_key_path;
    public $public_key_path;
    public $purl;

    public $request_url;
    public $transac_code;

    public function request($data, $doconvert = true, $doverify = true)
    {
        $request_data = $this->_build_paybase_requestdata($data);
        $client = new Client();
        !isset(Yii::$app->charset) && Yii::$app->charset = 'UTF-8';
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($this->request_url)
            ->setData($request_data)
            ->send();

        if ($response->isOk) {
            if ($doconvert) {
                $result = $this->_getResult($response->getContent());
                $resp = RsaHelper::decsign($result->respData,$this->private_key_path);
                if ($result->respCode != '200') {
                    throw new \Exception($result->respMsg);
                }
            } else {
                $result = $response->getContent();
            }

            if ($result == null) {
                throw new \Exception('reponse null');
            }
            //返回数据验签
            //解密返回数据
            if ($doverify) {
                if(!$result->validate($this->purl,$resp)){
                    throw new \Exception('sign check fail');
                }
            }
            return $result;
        } else {
            throw new \Exception($response->getContent());
        }
    }

    private function _getResult($response)
    {
        $decoded = json_decode($response, true);

        if (!$decoded) {
            return null;
        }
        $result = new Result();
        $result->respCode = $decoded['respCode'];
        $result->respData = $decoded['respData'];
        $result->respMsg = $decoded['respMsg'];
        $result->signature = $decoded['signature'];

        return $result;
    }

    public function _build_paybase_requestdata($data)
    {
        $signed = $this->_sign($data);

        $request_data = [
            'siteCode' => $this->site_code,
            'version' => $this->version,
            'deviceType' => $this->device_type,
            'transacCode' => $this->transac_code,
            'reqdata' => $signed['req_data'],
            'signature' => $signed['signed'],
            'charset' => $this->charset,
        ];

        return $request_data;
    }

    private function _sign($data)
    {

        $result = [];

        $json_str = json_encode($data,JSON_UNESCAPED_UNICODE);
        $sign_data = $this->site_code . $this->version . $this->transac_code . $json_str;

        //先用对方公钥加密,再采用base64编码
        $result['req_data'] = base64_encode(RsaHelper::reqSign($json_str,$this->public_key_path));
        //用已方私钥MD5withRSA算法签名，签名内容顺序为：siteCode、version、transacCode和reqdata(加密前的reqdata数据)；再采用Base64编码,（两次）
        $result['signed'] = base64_encode(base64_encode(RsaHelper::sign($sign_data, $this->private_key_path)));

        return $result;
    }
}
