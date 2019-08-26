<?php
/**
 * Created by PhpStorm.
 * User: cyr
 * Date: 2019/8/26
 * Time: 14:56
 */

namespace cyr\junhuan;


class SearchService extends PaybaseService
{
    public $request_url = PAY_XF_SEARCH;
    public $transac_code = TRANSAC_XF_QUERY;

    public $lsh;
    public $jszhm;
    public $hphm;
    public $fdjh;
    public $hpzl;

    public function getQuery(){
        if(!$this->lsh || $this->jszhm || $this->hphm || $this->fdjh || $this->hpzl )
        $data= [
            'lsh' => $this->lsh,
            'jszhm' => $this->jszhm,
            'hphm' => $this->hphm,
            'fdjh' => $this->fdjh,
            'hpzl' => $this->hpzl,
            ];
        $resp = $this->request($data);
        return $resp;
    }
}