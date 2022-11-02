<?php 

$file=fopen("kaynak.txt","w");

$ch=curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://www.instagram.com/alpselcukkkk/');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_FILE, $file);

if (curl_exec($ch)) {
	echo "Başarıyla kaynak kodu dosyaya yazıldı";
}

curl_close($ch);

 ?>