<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 05.12.18
 * Time: 2:25
 */

function shaba($aaa, $bbb) {
        if (file_exists($aaa) == false){
            return 'ERROR!!!';
        }
        else {
            ob_start();
            extract($bbb);
            require_once $aaa;
            $temp = ob_get_contents();
            ob_end_clean();
            return $temp;
        }
    }
