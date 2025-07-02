<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {
    
    function valid_img($str) {

        $x = @getimagesize($str);

        switch ($x['mime']) {
            case "image/gif":
                $response = TRUE;
                break;
            case "image/jpeg":
                $response = TRUE;
                break;
            case "image/png":
                $response = TRUE;
                break;
            default:
                $response = FALSE;
                break;
        }

        return $response;
    }


}

