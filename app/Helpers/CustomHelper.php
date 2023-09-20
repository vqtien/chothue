<?php

if (!function_exists('makeSlug')) {
    function makeSlug($str)
    {
        $text        = cleanText($str);
        $patterns    = array('/ {2,}/', '/ /', '/-{2,}/');
        $replace     = array(' ', '-', '-');
        $string      = preg_replace($patterns, $replace, $text);
        return strtolower($string);
    }
}

if (!function_exists('cleanText')) {
    function cleanText($string = '')
    {
        $patterns = array(
            '/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ|À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/',
            '/(ì|í|ị|ỉ|ĩ|Ì|Í|Ị|Ỉ|Ĩ)/',
            '/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ|Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/',
            '/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ|È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/',
            '/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ|Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/',
            '/(ỳ|ý|ỵ|ỷ|ỹ|Ỳ|Ý|Ỵ|Ỷ|Ỹ)/',
            '/(đ|Đ)/',
            '/æ/',
            '/ç/i',
            '/ñ/i',
            '/%/i',
            '/[^\x00-\x7F]+/',
            '/[^\w_ \-]+/i',
            '/^\-|\-$/i',
            '/-+$/',
            '/ {2,}/'
        );
        $replace = array('a', 'i', 'u', 'e', 'o', 'y', 'd', 'ae', 'c', 'n', ' ', ' ', ' ', ' ', '', ' ');
        $string = preg_replace($patterns, $replace, $string);

        return $string;
    }
}
