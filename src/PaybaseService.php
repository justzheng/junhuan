<?php
/**
 * User: leeyifiei
 * Date: 17/4/14
 */

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

                if ($result->respCode != '200') {
                    throw new \Exception($result->respMsg);
                }
            } else {
                $result = $response->getContent();
            }

            if ($result == null) {
                throw new \Exception('reponse null');
            }

            if ($doverify && !$result->validate($this->public_key_path)) {
                throw new \Exception('sign check fail');
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

        $json_str = json_encode($data);
        $sign_data = $this->site_code . $this->version . $this->transac_code . $json_str;

        $result['req_data'] = base64_encode($json_str);
        $result['signed'] = base64_encode(base64_encode(RsaHelper::sign($sign_data, $this->private_key_path)));

        return $result;
    }
}
