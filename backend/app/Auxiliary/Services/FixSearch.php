<?php

namespace App\Auxiliary\Services;

use function App\Auxiliary\access;
use function App\Auxiliary\active;
use function App\Auxiliary\status;

class FixSearch
{
    private $text;
    public function setText($text)
    {
        $this->text = is_null($text) ? '' : trim($text);
        return $this;
    }

    public function get(){
        return trim($this->text);
    }

    public function fixStatus(){
        switch ($this->text){
            case 'پرداخت شد': $this->text = 'paid'; break;
            case 'فعال':
            case 'تایید شد':
            case 'تایید': $this->text = 'yes';break;
            case 'غیر فعال':
            case 'رد':
            case 'رد شد':
            case 'غیر فعال شد': $this->text = 'no';break;
            case 'در انتظار تایید':
            case 'در انتظار بررسی': $this->text = 'pending';break;
            case 'لغو شد':
            case 'لغو': $this->text = 'cancel';break;
        }
        return $this;
    }
    public function fixShaba(){
        $this->text = str_replace('IR','',$this->text);
        return $this;
    }
    public function fixPayment(){
        switch ($this->text){
            case 'کیف پول': $this->text = 'wallet';break;
            case 'درگاه بانکی': $this->text = 'bank';break;
        }
        return $this;
    }

    public function fixAccess(){
        $this->text = access($this->text, 'en');
        return $this;
    }

    public function fixPrice(){
        $this->text = str_replace([',','ریال'],['',''],$this->text);
        return $this;
    }

    public function fixType(){
        switch ($this->text){
            case 'سریال' : $this->text = 'serial'; break;
            case 'ویدیو' : $this->text = 'movie'; break;
        }
        return $this;
    }

    public function fixYesAndNo(){
        switch ($this->text){
            case 'بله' : $this->text = 'yes'; break;
            case 'خیر' : $this->text = 'no'; break;
        }
        return $this;
    }

    public function payType(){
        switch ($this->text){
            case 'کیف پول' : $this->text = 'wallet'; break;
            case 'اینترنتی' : $this->text = 'bank'; break;
        }
        return $this;
    }

    public function fixPercent(){
        $this->text = str_replace('%','',$this->text);
        return $this;
    }
}
