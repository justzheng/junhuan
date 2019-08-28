<?php

namespace cyr\junhuan;

class RsaHelper
{
    /**
     * @param $data
     * @param $rsakeypath
     * @return bool
     */
    public static function encryptPublic($data, $rsakeypath)
    {
        $content = self::getContent($rsakeypath);
        if ($content) {
            $pem = self::transJavaRsaKeyToPhpOpenSSL($content);
            $pem = self::appendFlags($pem, true);
            $res = openssl_pkey_get_public($pem);
            if ($res) {
                $opt = openssl_public_encrypt($data, $result, $res);
                if ($opt) {
                    return $result;
                }
            }
        }

        return false;
    }

    /**
     * @param $data
     * @param $rsakeypath
     * @return bool
     */
    public static function sign($data, $rsakeypath)
    {
        $content = self::getContent($rsakeypath);
        if ($content) {
            $pem = self::transJavaRsaKeyToPhpOpenSSL($content);
            $pem = self::appendFlags($pem, false);
            $res = openssl_pkey_get_private($pem);
            if ($res) {
                $opt = openssl_sign($data, $result, $res, OPENSSL_ALGO_MD5);
                openssl_free_key($res);
                if ($opt) {
                    return $result;
                }
            }
        }

        return false;
    }

    /**
     * @param $data
     * @param $pubkey
     * @return string
     */
    public static function reqSign($data,$pubkey){
        $content = self::getContent($pubkey);
        if ($content) {
            $pem = self::transJavaRsaKeyToPhpOpenSSL($content);
            $pem = self::appendFlags($pem, true);
            //公钥加密
            $encrypted = self::encrypt($data,$pem);
            return $encrypted;
        }

        return false;
    }
    
    public static function decsign($data,$pubkey){
        $content = self::getContent($pubkey);
        if ($content) {
            $pem = self::transJavaRsaKeyToPhpOpenSSL($content);
            $pem = self::appendFlags($pem, false);
 //           openssl_private_decrypt($data,$encrypted,$pem);//私钥解密
//            $encrypted = base64_encode($encrypted);
            $encrypted = self::decrypt_RSA($data,$pem);
            //$encrypted = openssl_public_encrypt($data,$encrypted,$pem);//私钥解密
            return $encrypted;
        }

        return false;
    }

    /**
     * @param $data
     * @param $pubkey
     * @return string
     */
    public static function decrypt_RSA($data,$publicPEMKey)
    {
        $crypto = '';

        foreach (str_split((base64_decode($data)), 128) as $chunk) {

            openssl_private_decrypt($chunk, $decryptData, $publicPEMKey);

            $crypto .= $decryptData;
        }

        return $crypto;
    }

    public static function encrypt($originalData,$pem){

        $crypto = '';

        if(strlen($originalData)<=117){
            openssl_public_encrypt($originalData, $encryptData, $pem);
            $crypto = $encryptData;
        }else{
            foreach (str_split($originalData, 117) as $chunk) {

                openssl_public_encrypt($chunk, $encryptData, $pem);

                $crypto .= $encryptData;
            }
        }

        return $crypto;
    }

    public static function validate($data, $signature, $rsakeypath)
    {
        $content = self::getContent($rsakeypath);
        if ($content) {
            $pem = self::transJavaRsaKeyToPhpOpenSSL($content);
            $pem = self::appendFlags($pem, true);

            $res = openssl_pkey_get_public($pem);
            if ($res) {
                $success = openssl_verify($data, $signature, $res, OPENSSL_ALGO_MD5);
                openssl_free_key($res);

                if ($success == 1) {
                    return 1;
                } else {
                    return 0;
                }
            }
        }

        return false;
    }

    /**
     * @param $data
     * @param $rsakeypath
     * @return bool
     */
    public static function decryptPrivate($data, $rsakeypath)
    {
        $content = self::getContent($rsakeypath);
        if ($content) {
            $pem = self::transJavaRsaKeyToPhpOpenSSL($content);
            $pem = self::appendFlags($pem, false);
            $res = openssl_pkey_get_private($pem);
            if ($res) {
                $opt = openssl_private_decrypt($data, $result, $res);
                if ($opt) {
                    return $result;
                }
            }
        }

        return false;
    }

    /**
     * @param $filepath
     * @return bool|string
     */
    private static function getContent($filepath)
    {
        if (is_file($filepath)) {
            $content = file_get_contents($filepath);

            return strtr($content, array(
                "\r\n" => "",
                "\r" => "",
                "\n" => "",
            ));
        }

        return false;
    }

    /**
     * trans java's rsa key format to php openssl can read
     * @param $content
     * @return string
     */
    private static function transJavaRsaKeyToPhpOpenSSL($content)
    {
        if ($content) {
            return trim(chunk_split($content, 64, "\n"));
        }

        return false;
    }

    /**
     * append Falgs to content
     * @param $content
     * @param $isPublic
     * @return string
     */
    private static function appendFlags($content, $isPublic = true)
    {
        if ($isPublic) {
            return "-----BEGIN PUBLIC KEY-----\n" . $content . "\n-----END PUBLIC KEY-----\n";
        } else {
            return "-----BEGIN PRIVATE KEY-----\n" . $content . "\n-----END PRIVATE KEY-----\n";
        }
    }
}
