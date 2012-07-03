<?
class TimeFix
{   
    static function DatetimeInGMT() {
        return date("Y-m-d H:i:s", time()-date("Z",time()));
    }

    static function GMTDatetimeToLocal($datetime) {
        $time = strtotime($datetime);
        return date("Y-m-d H:i:s", $time+date("Z",$time));
    }
}
?>