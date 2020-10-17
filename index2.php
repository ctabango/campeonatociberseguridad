<?php
$url = "http://www.bancointernacional.com.ec/mcm-web-baninter/login.jsp";
$dom="http://www.bancointernacional.com.ec";

$contentHtml=getPage($url);
$contenidosinScripts=eliminarScripts($contentHtml);
//echo $contenidosinScripts;
$re = '/(alt|href|src)=("[^"]*")/'; 
preg_match_all($re,$contenidosinScripts, $matches);
//print_r($matches);
for($elementos=0;$elementos<=count($matches[2])-1;$elementos++){
	$archivo=quitarprimeroyultimo($matches[2][$elementos]);
	if(strlen($archivo)>5){
	 $nuevoNombreArchivo=download_image1($archivo);
	$contenidosinScripts = str_replace($archivo, $nuevoNombreArchivo, $contenidosinScripts);
	}
	//echo $archivo;
}

$fh = fopen("contenidolimpio.html", 'w') or die("Se produjo un error al crear el archivo");
   
  fwrite($fh, $contenidosinScripts) or die("No se pudo escribir en el archivo");
  
  fclose($fh);
echo $contenidosinScripts;
// preg_match('href="([^"]+)"@' , $contenidosinScripts, $matches);
//print_r($matches);
//preg_match_all('~<(.*?)"([^"]+)"(.*?)>~', $contentHtml, $matches);

//print_r($scripts[0]);
//var_dump($matches);
function eliminarScripts($code){
	$code = preg_replace('/<noscript\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/noscript>/i', '', $code);

	$code = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i', '', $code);
return $code;
}
function quitarprimeroyultimo($texto){
	$texto=substr($texto,1);
$texto=substr($texto,0,-1);
return $texto;
}
function download_image1($image_url){
	$dominio="https://www.bancointernacional.com.ec";
	$urlArchivo=$dominio.$image_url;
	$path = $image_url;
	 $nombreArchivo=extractdata("/",$image_url);
	 $extension=extractdata(".",$image_url);
	
	if($extension =='js'){
		$ruta="js//";
	}elseif($extension =='png' or $extension =='jpg' or $extension =='ico'){
		$ruta="imagenes//";
	}elseif($extension =='css'){
		$ruta="css//";
	}else{
		$ruta="otros//";
	}
	if (!file_exists($ruta)) {
    mkdir($ruta, 0777, true);
	
}
//echo $ruta.$nombreArchivo;
    $fp = fopen ($ruta.$nombreArchivo, 'w+');              // open file handle

    $ch = curl_init($urlArchivo);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // enable if you want
    curl_setopt($ch, CURLOPT_FILE, $fp);          // output to file
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1000);      // some large value to allow curl to run for a long time
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    // curl_setopt($ch, CURLOPT_VERBOSE, true);   // Enable this line to see debug prints
    curl_exec($ch);

    curl_close($ch);                              // closing curl handle
    fclose($fp);                                  // closing file handle
	return $ruta.$nombreArchivo;
}
function extractdata($dato,$texto){
	$array_ofpath = explode($dato, $texto);//explode retorna un array
	$NameFile = end($array_ofpath);	
	return $NameFile;
}
function getPage ($url) {

$cookie="cookies";
if (!file_exists($cookie)) {
    mkdir($cookie, 0777, true);
	
}
$useragent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.89 Safari/537.36';
$timeout= 120;
$dir            = dirname(__FILE__);
$cookie_file    = $dir . '/cookies/' . md5($_SERVER['REMOTE_ADDR']) . '.txt';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_FAILONERROR, true);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
curl_setopt($ch, CURLOPT_ENCODING, "" );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt($ch, CURLOPT_AUTOREFERER, true );
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout );
curl_setopt($ch, CURLOPT_TIMEOUT, $timeout );
curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
curl_setopt($ch, CURLOPT_REFERER, 'http://www.google.com/');
$content = curl_exec($ch);
if(curl_errno($ch))
{
    echo 'error:' . curl_error($ch);
}
else
{
    return $content;        
}
    curl_close($ch);

}