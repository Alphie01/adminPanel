<?php

session_start();
ob_start();
$data = $_SESSION['userdetails'];
?>

<!DOCTYPE html>
<html>
<head>
 <title>İnstagram api kullanımı</title>
</head>
<body>

<?php
function vericek($yol)
{
 $curl = curl_init();
 curl_setopt($curl, CURLOPT_URL, $yol);
 curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
 $veri = curl_exec($curl);
 curl_close($curl);
 $veri = json_decode($veri);
 return $veri;
}


$link = "https://api.instagram.com/v1/users/self/?access_token=".$data->access_tok;
$gelen = vericek($link);

?>
<br><br>
<ul>
 <li>Kullanıcı Adı :<?=$gelen->data->username?></li>
 <li>Profil Resmi :<?=$gelen->data->profile_picture?></li>
 <li>Tam Adı :<?=$gelen->data->full_name?></li>
 <li>Açıklama :<?=$gelen->data->bio?></li>
 <li>Web Site :<?=$gelen->data->website?></li>
 <li>Paylaşım Sayısı :<?=$gelen->data->counts->media?></li>
 <li>Takip Edilen :<?=$gelen->data->counts->follows?></li>
 <li>Takipçi Sayısı :<?=$gelen->data->counts->followed_by?></li>
</ul>


</body>
</html>