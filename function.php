<?php
date_default_timezone_set("Asia/Jakarta");
ini_set('display_errors',1);
error_reporting(E_ALL);
setlocale(LC_ALL, 'en_US.UTF8');
// header("Content-type: application/json;  charset=utf-8");
ini_set("max_execution_time", 30);
# GLOBAL
define('METHOD', $_SERVER['REQUEST_METHOD']);
$api_ci = 'http://10.6.11.11';
$url    = 'http://10.6.11.77/landing_admin/';
$url_b  = 'https://broadcast.eva.id/landing_admin/';
$method = $_SERVER['REQUEST_METHOD'];
$date   = date('Y-m-d H:i:s');
$json   = file_get_contents("php://input");

# FUNCTION
function conv_tanggal($tanggal){
  $awal   = substr($tanggal, 6, 4).'-'.substr($tanggal, 0, 2).'-'.substr($tanggal, 3, 2);
  $akhir  = substr($tanggal, 19, 4).'-'.substr($tanggal, 13, 2).'-'.substr($tanggal, 16, 2);
  $tanggals = [
    'awal' => $awal,
    'akhir'=> $akhir
  ];
  return $tanggals;
}

function respon($rc=400, $rm='error'){
  // header('Content-Type: application/json');
  // http_response_code($rc);  

  $array  = [
      'rc'    => $rc,
      'rm'    => $rm
  ];
  echo json_encode($array);
  exit();
}

function respons($rc=400, $rm='error'){
  header('Content-Type: application/json');
  http_response_code($rc);  

  $array  = [
      'rc'    => $rc,
      'rm'    => $rm
  ];
  echo json_encode($array);
  exit();
}

function conn_slave(){
	/*$arrSlave = array("slave01", "slave02");
	if( is_array($arrSlave) ){
		shuffle($arrSlave);
		if( $arrSlave[0] == "slave01" ){
			$conSlave = slave01();
		}
		else{
			$conSlave = slave02();
		}
	}
	else{
		$conSlave = slave01();
	}*/
	$conSlave	= slave01();
	return $conSlave;
}

function dec($val, $def=''){
  $json       = file_get_contents("php://input");
  $decode     = json_decode($json, true);
  $hasil      = isset($decode[$val]) ? $decode[$val] : $def;
  return $hasil;
}

function httpPost($url,$params)
{
	if ( is_array($params) )
	{
 
		$postData = http_build_query($params, 'flags_');
 
	}
	else
	{
		$postData = $params;
	}
 
    $ch = curl_init();  
 
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_HEADER, false); 
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);  
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));  
    curl_setopt($ch, CURLOPT_TIMEOUT, 21); 
	curl_setopt($ch, CURLOPT_ENCODING, '');
	curl_setopt($ch, CURLOPT_VERBOSE, false);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:13.0) Gecko/20100101 Firefox/13.0.1');
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	
   
    $output=curl_exec($ch);

	
	if ( false === $output )
	  {
	  }
	
	$info = curl_getinfo($ch);  
 	//LogstoDebug(basename(__FILE__)."(".__LINE__.")-> CURL ". ' content type:'. $info['content_type'].' http code:' . $info['http_code'], _MODULE );			   
	
	$code = $info['http_code'];
	if ( 200 != $code ) { 
		$output = false; 
		}
	
 
	
    curl_close($ch);
    return $output;
 
}

function sendMessageVia($id, $message, $platform = '5'){
  /*Default Variable*/
  //$url = 'https://whook.eva.id/bot_notify/notifbyviaevabot.php';
  //$url = 'https://whook2.eva.id/bot_notify/notifbyviavallenbot.php';
  $url = 'http://10.6.11.11/bot_notify/notifbyviavallenbot.php';
  $body = array(
      'platform' => $platform,
      'id_receiver' => $id,
      'content_type' => 'application/text',
      'content' => serialize($message),
      'date_send' => Date('YmdHis')
  );
  $body = serialize($body);
  $body = 'message='.base64_encode_url($body);
  $response = httpPost($url, $body);
  return $response;
}

function slave01(){
	#Config DB
	$servername = "10.6.11.74";
	$username 	= "evaWrite77";
	$password 	= "7Desember@2021";
	$database 	= "eva_ext";

	#Create connection
	$conn = mysqli_connect($servername, $username, $password, $database);

	#Check connection
	if (!$conn) {
		syxlog("ERROR ".__LINE__." >> "."Connection failed: " . mysqli_connect_error());
		$lolos	= false;
		die();
	}
	return $conn;
}

function kirim_apis($file,$datana = array()){
	$hasil		= false;
	$data_chat	= false;
	#$kirim_api	= curl("https://rz.helobot.id/api_rz/".$file.".php","post",$datana);	
	$kirim_api	= curl("http://10.6.11.77/api_dashboard/".$file.".php","post",$datana);	
	$code		= $kirim_api["code"];
	$body		= $kirim_api["body"];
	$bodys		= decode($body);
	#die("https://rz.helobot.id/api_rz/".$file.".php<pre>".var_export($datana,true)."</pre><pre>".var_export($bodys,true)."</pre>");
	if($code == "200"){
		$hasil			= $bodys;
	}
	return $hasil;
}
function curl( $url, $method = 'get', $data = '', $header = array(), $keep = true ){
  global $ch, $curl_cookie_file;

  $res = array();
  if ( false == $ch )
  {
    $ch = curl_init();
  }

  $_header = array(
    'Accept-Language: en-US,en;q=0.7,fr;q=0.3',
    'Accept-Encoding: gzip, deflate',
    );
  $header += $_header;
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
  curl_setopt($ch, CURLOPT_TIMEOUT, 40);
  curl_setopt($ch, CURLOPT_HEADER, false);
  curl_setopt($ch, CURLOPT_ENCODING, '');
  curl_setopt($ch, CURLOPT_VERBOSE, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:37.0) Gecko/20100101 Firefox/37.0');
  curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

  if ( 'post' == $method AND '' != $data )
  {
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  }

  if ( strpos($url, 'https://') !== false )
  {
    //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    #curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    #curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  }

  if ( $curl_cookie_file )
  {
    curl_setopt($ch, CURLOPT_COOKIEJAR, $curl_cookie_file);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $curl_cookie_file);
  }

  $ce = curl_exec($ch);
  if ( false === $ce )
  {
    $res = array(
      'eno' => curl_errno($ch),
      'emsg' => curl_error($ch),
      );
  }
  else
  {
    $res = array(
      'code' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
      'body' => $ce,
      );
  }

  if ( false != $keep )
  {
    curl_close($ch);
    $ch = false;
  }

  return $res;
}

function decode($array_respon){
	if(!empty($array_respon))
	{
		$hsl_decode64 = base64_decode($array_respon);
		$hsl_unserialize = unserialize($hsl_decode64);
		return $hsl_unserialize;
	}
	else return "\n maap array respon kosong ! <p>";
}

function httpPosts($url,$params)
{
  if ( is_array($params) )
  {
     $postData = '';
     //create name value pairs seperated by &
     foreach($params as $k => $v) 
     { 
      $postData .= $k . '='.$v.'&'; 
     }
     rtrim($postData, '&');
  }
  else
  {
    $postData = $params;
  }
 
    $ch = curl_init();  
 
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_HEADER, false); 
    if(is_array($postData)){
      curl_setopt($ch, CURLOPT_POST, count($postData));
    }
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);  
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));  
	curl_setopt($ch, CURLOPT_ENCODING, '');
	curl_setopt($ch, CURLOPT_VERBOSE, false);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
   
    $output=curl_exec($ch);

  if ( false === $output )
    {
    //addLogs(basename(FILE)."(".LINE.")-> ERR CURL ". ' no:'. curl_errno($ch).' msg:' . curl_error($ch), _MODULE );    
    }
  
  $info = curl_getinfo($ch);  
  
  //addLogs(basename(FILE)."(".LINE.")-> CURL ". ' content type:'. $info['content_type'].' http code:' . $info['http_code'], _MODULE );         
  
    curl_close($ch);
    return $output;
 
}

function create_uidbayu()
{
  $ref = microtime(true);
  $sec = $ref | 0;

  return sprintf("%d%06d", $sec, ($ref - $sec) * 1000000);
}

function randstr($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function create_uids(){
    $ref = microtime(true);
    $sec = $ref | 0;
    return sprintf("%d%'08d", $sec, ($ref - $sec) * 100000000);
}

function base64_encode_url($string)
{
  $out = base64_encode($string);
  $out = str_replace(array('+','/','='), array('-','_',''), $out);
  return $out;
}

function ecee($string, $konci) { 
    $result = ''; 
    for($i=0; $i<strlen($string); $i++) { 
    $char = substr($string, $i, 1); 
    $koncichar = substr($konci, ($i % strlen($konci))-1, 1); 
    $char = chr(ord($char)+ord($koncichar)); 
    $result.=$char; 
    }

    return rawurlencode(base64_encode_url($result)); 
}

function base64_decode_url($string)
{
  $out = str_replace(array('-', '_', ' ', "\t", "\r", "\n"),
    array('+', '/', ''), $string);
  $out .= substr( '====', (strlen($out) % 4) );
  $out = base64_decode($out);
  return $out;
}

function dcee($string, $konci) {
	$string = rawurldecode($string);	
	$result = ''; 
	$string = base64_decode_url($string); 
	for($i=0; $i<strlen($string); $i++) { 
		$char = substr($string, $i, 1); 
		$koncichar = substr($konci, ($i % strlen($konci))-1, 1); 
		$char = chr(ord($char)-ord($koncichar)); 
		$result.=$char; 
	} 
	return $result; 
}

function koneksi_old($db='eva_ext'){
  $conn = new PDO('mysql:host=10.6.11.74;dbname='.$db, 'evaWrite77', '7Desember@2021');
  // $conn = new PDO('mysql:host=103.139.193.247;dbname='.$db, 'evaTestR', '32Desember@2023');
  if($conn){
    return $conn;
  }else{
    return 'error';
  }
}

function koneksi($db){
    $host = '127.0.0.1';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Better error reporting
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch as associative array
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Use native prepared statements
    ];

    try {
        $conn = new PDO($dsn, $user, $pass, $options);
        return $conn;
    } catch (\PDOException $e) {
        exit("Database connection failed: " . $e->getMessage());
    }
}

function rand_string($length = 10) {
  $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

function get_typo_old($keyword){
  $len        = strlen($keyword);
  $data       = array();
  for ($i=0; $i < $len; $i++) { 
      $new    = $i-1;
      $batas  = $len-1;
  
      if($i == 0){
          
      }elseif($i == $batas){
          $sub    = substr($keyword, 0, $batas);
          $data[] = $sub;
      }else{
          $sub    = substr($keyword, 0, $new);
          $sub2   = substr($keyword, $i);
          $data[] = $sub.$sub2;
      }
  }
  
  for ($i=0; $i < $len; $i++) { 
      $add    = $i+2;
      $kurang = $len-1;
      if($i == 0){
          $sub    = substr($keyword, 2);
          $data[] = $sub;
      }elseif($i == $kurang){
  
      }else{
          $sub    = substr($keyword, 0, $i);
          $sub2   = substr($keyword, $add);
          $data[] = $sub.$sub2; 
      }
  }
  return $data;
}

function get_typo($keyword){
    $len        = strlen($keyword);
    $data       = array();
    for ($i=0; $i < $len; $i++) { 
        $new    = $i-1;
        $batas  = $len-1;
    
        if($i == 0){
            
        }elseif($i == $batas){
            $sub    = substr($keyword, 0, $batas);
            $data[] = $sub;
        }else{
            $sub    = substr($keyword, 0, $new);
            $sub2   = substr($keyword, $i);
            $data[] = $sub.$sub2;
        }
    }
    
    for ($i=0; $i < $len; $i++) {
        for ($x=$i; $x < $len; $x++) { 
            if($x == $i){

            }else{
                $kata   = $keyword;
                $kata[$i]    = '"';
                $kata[$x]    = '`';
                $kata        = str_replace('"', '', $kata);
                $kata        = str_replace('`', '', $kata);
                $data[]     = $kata;
                // $next1  = $i+1;
                // $next2  = $i+2;
                // $d  = $x+2;
                // $nuxt1  = $d+1;
                // $nuxt2  = $d+2;

                // $kata[$i]       = '"';
                // $kata[$next1]       = '9';
                // $kata[$next2]       = 'T';
                // mlog('typo', $kata);
                // $kata[$d]       = '`';
                // $kata[$nuxt1]   = '5';
                // $kata[$nuxt2]   = 'C';
                // $kata           = str_replace('"9T', '', $kata);
                // $kata           = str_replace('`5C', '', $kata);
                // $data[]         = $kata;
                
            }
        }
    }
    return $data;
}

function hilangkan($kata){
  $ganti  = str_replace("'", "&rsquo;", $kata);
  $ganti  = str_replace('"', "&rdquo;", $ganti);
  $ganti  = str_replace("‘", "&lsquo;", $ganti);
  return $ganti;
}

function api_post($url, $data){
    $payload = json_encode($data);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = trim(curl_exec($ch));
    curl_close($ch);
    return $result;
}

function api_post_header($url, $data, $bearer_token = null){
  $payload = json_encode($data);
  $ch = curl_init($url);

  $headers = array('Content-Type: application/json');

  if ($bearer_token) {
      $headers[] = 'Authorization: Bearer ' . $bearer_token;
  }

  curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
  curl_setopt($ch, CURLOPT_HEADER, 0); // or 1 if you need headers in the response
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Add this line to disable SSL verification (for development/testing ONLY)
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // Add this line to disable SSL verification (for development/testing ONLY)

  $result = curl_exec($ch);
  if (curl_errno($ch)) { // Check for cURL errors
      $error_message = curl_error($ch);
      curl_close($ch);
      return "cURL Error: " . $error_message; // Return the error message
  }
  curl_close($ch);
  return $result;
}

function api_delete($url, $data = null) {
  $ch = curl_init($url);

  if ($data !== null) {
      $payload = json_encode($data);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
  } else {
      curl_setopt($ch, CURLOPT_HTTPHEADER, array());
  }

  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $result = trim(curl_exec($ch));
  curl_close($ch);
  return $result;
}

function api_put($url, $data) {
  $payload = json_encode($data);
  $ch = curl_init($url);
  
  // Set the request method to PUT
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
  curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
  // Execute the request and get the result
  $result = trim(curl_exec($ch));
  
  // Check for errors
  if (curl_errno($ch)) {
      echo 'cURL error: ' . curl_error($ch);
  }
  
  curl_close($ch);
  return $result;
}

function api_get($url, $params = array()) {
  // Append parameters to the URL as query strings
  if (!empty($params)) {
      $url .= '?' . http_build_query($params);
  }

  $ch = curl_init($url);
  
  // Set the request method to GET
  curl_setopt($ch, CURLOPT_HTTPGET, true);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
  // Execute the request and get the result
  $result = trim(curl_exec($ch));
  
  // Check for errors
  if (curl_errno($ch)) {
      echo 'cURL error: ' . curl_error($ch);
  }
  
  curl_close($ch);
  return $result;
}


?>