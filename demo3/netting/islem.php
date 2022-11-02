<?php
ob_start();
session_start();

include 'baglan.php';
include '../production/fonksiyon.php';

if (isset($_POST['kullanici_register'])) {

	
	if (isset($_POST['checked'])=='test') {
		echo $kullanici_adsoyad=htmlspecialchars($_POST['kullanici_adsoyad']); echo "<br>";
		echo $kullanici_mail=htmlspecialchars($_POST['kullanici_mail']); echo "<br>";

		echo $kullanici_passwordone=trim($_POST['kullanici_password']); echo "<br>";
		echo $kullanici_passwordtwo=trim($_POST['kullanici_password_tekrar']); echo "<br>";



		if ($kullanici_passwordone==$kullanici_passwordtwo) {


			if (strlen($kullanici_passwordone)>=6) {


				

				


	// Başlangıç

				$kullanicisor=$db->prepare("select * from kullanici where kullanici_mail=:mail");
				$kullanicisor->execute(array(
					'mail' => $kullanici_mail
					));

				//dönen satır sayısını belirtir
				$say=$kullanicisor->rowCount();



				if ($say==0) {

					//md5 fonksiyonu şifreyi md5 şifreli hale getirir.
					$password=md5($kullanici_passwordone);

					$kullanici_yetki=0;

				//Kullanıcı kayıt işlemi yapılıyor...
					$kullanicikaydet=$db->prepare("INSERT INTO kullanici SET
						kullanici_adsoyad=:kullanici_adsoyad,
						kullanici_mail=:kullanici_mail,
						kullanici_password=:kullanici_password,

						user_place=:kullanici_place,
						kullanici_yetki=:kullanici_yetki
						");
					$insert=$kullanicikaydet->execute(array(
						'kullanici_adsoyad' => $kullanici_adsoyad,
						'kullanici_mail' => $kullanici_mail,
						'kullanici_password' => $password,

						'kullanici_place' => $_POST['kullanici_place'],
						'kullanici_yetki' => $kullanici_yetki
						));

					if ($insert) {


						header("Location:../../signup.php?durum=loginbasarili");


					//Header("Location:../production/genel-ayarlar.php?durum=ok");

					} else {


						header("Location:../../signup.php?durum=basarisiz");
					}

				} else {

					header("Location:../../signup.php?durum=mukerrerkayit");



				}




			// Bitiş



			} else {


				header("Location:../../signup.php?durum=eksiksifre");


			}



		} else {



			header("Location:../../signup.php?durum=farklisifre");
		}
		

	}
	else{
		header("Location:../../signup.php?checklenmemiş");
	}

}





if (isset($_POST['sliderkaydet'])) {


	$uploads_dir = '../../dimg/slider';
	@$tmp_name = $_FILES['slider_resimyol']["tmp_name"];
	@$name = $_FILES['slider_resimyol']["name"];
	//resmin isminin benzersiz olması
	$benzersizsayi1=rand(20000,32000);
	$benzersizsayi2=rand(20000,32000);
	$benzersizsayi3=rand(20000,32000);
	$benzersizsayi4=rand(20000,32000);	
	$benzersizad=$benzersizsayi1.$benzersizsayi2.$benzersizsayi3.$benzersizsayi4;
	$refimgyol=substr($uploads_dir, 6)."/".$benzersizad.$name;
	@move_uploaded_file($tmp_name, "$uploads_dir/$benzersizad$name");
	


	$kaydet=$db->prepare("INSERT INTO slider SET
		slider_ad=:slider_ad,
		slider_sira=:slider_sira,
		slider_link=:slider_link,
		slider_resimyol=:slider_resimyol
		");
	$insert=$kaydet->execute(array(
		'slider_ad' => $_POST['slider_ad'],
		'slider_sira' => $_POST['slider_sira'],
		'slider_link' => $_POST['slider_link'],
		'slider_resimyol' => $refimgyol
		));

	if ($insert) {

		Header("Location:../production/slider.php?durum=ok");

	} else {

		Header("Location:../production/slider.php?durum=no");
	}




}



if (isset($_POST['contactkayıt'])) {
$kayit=0;
if($_POST['custom-radio-1']){
	$kayit=1;
}else{
	$kayit=0;
}



	$kaydet=$db->prepare("INSERT INTO contact SET
		contact_name=:contact_name,
		contact_email=:contact_email,
		contact_phone=:contact_phone,
		contact_mesaj=:contact_mesaj,
		contact_which=:contact_which

		");
	$insert=$kaydet->execute(array(
		'contact_name' => $_POST['contact_name'],
		'contact_email' => $_POST['contact_email'],
		'contact_phone' => $_POST['contact_phone'],
		'contact_mesaj' => $_POST['contact_mesaj'],
		'contact_which' => $kayit

		));

	if ($insert) {

		Header("Location:../pages_contact_us.php?durum=ok");

	} else {

		Header("Location:../pages_contact_us.php?durum=no");
	}




}


if (isset($_POST['photo_kaydet'])) {


	$uploads_dir = '../dimg/slider';

	@$tmp_name = $_FILES['photos_imgs']["tmp_name"];
	@$name = $_FILES['photos_imgs']["name"];

	$benzersizsayi4=rand(20000,32000);
	$refimgyol=substr($uploads_dir, 2)."/".$benzersizsayi4.$name;

	@move_uploaded_file($tmp_name, "$uploads_dir/$benzersizsayi4$name");

	


	$kaydet=$db->prepare("INSERT INTO photos SET
		photos_title=:photos_title,
		photos_sendby=:photos_sendby,
		photos_imgs=:photos_imgs
		");
	$insert=$kaydet->execute(array(
		'photos_title' => $_POST['photos_title'],
		'photos_sendby' => $_POST['photos_sendby'],
		'photos_imgs' => $refimgyol
		));

	if ($insert) {

		Header("Location:../component_lightbox.php?durum=ok");

	} else {

		Header("Location:../component_lightbox.php?durum=no");
	}




}








if (isset($_POST['todokaydet'])) {


if($_POST['todo_kisi']==0){



	$kaydet=$db->prepare("INSERT INTO todo SET
		todo_user=:todo_user,
		todo_sendby=:todo_sendby,
		todo_title=:todo_title,
		todo_content=:todo_content

		");
	$insert=$kaydet->execute(array(
		'todo_user' => $_POST['kullanici_id'],
		'todo_sendby' => $_POST['kullanici_id'],
		'todo_title' => $_POST['todo_title'],
		'todo_content' => $_POST['todo_content']

		));
}else {
	$kullanicisor=$db->prepare("SELECT * FROM kullanici where kullanici_mail=:mail");
	$kullanicisor->execute(array(
	'mail' => $_POST['todo_mail']
	));
	$say=$kullanicisor->rowCount();
	$kullanicicek=$kullanicisor->fetch(PDO::FETCH_ASSOC);




	$kaydet=$db->prepare("INSERT INTO todo SET
		todo_user=:todo_user,
		todo_sendby=:todo_sendby,
		todo_title=:todo_title,
		todo_content=:todo_content

		");
	$insert=$kaydet->execute(array(
		'todo_user' => $_POST['kullanici_id'],
		'todo_sendby' => $kullanicicek['kullanici_id'],
		'todo_title' => $_POST['todo_title'],
		'todo_content' => $_POST['todo_content']

		));
}






	if ($insert) {

		Header("Location:../apps_todoList.php?durum=ok");

	} else {

		Header("Location:../apps_todoList.php?durum=no");
	}

}






if (isset($_POST['sss_kaydet'])) {



	
	
	
		$kaydet=$db->prepare("INSERT INTO sss SET
			sss_title=:sss_title,
			sss_content=:sss_content
	
			");
		$insert=$kaydet->execute(array(
			'sss_title' => $_POST['sss_title'],
			'sss_content' => $_POST['sss_content']
	
			));

	
	
	
	
	
	
		if ($insert) {
	
			Header("Location:../pages_faq.php?durum=ok");
	
		} else {
	
			Header("Location:../pages_faq.php?durum=no");
		}
	
	}




// Slider Düzenleme Başla


if (isset($_POST['sliderduzenle'])) {

	
	if($_FILES['slider_resimyol']["size"] > 0)  { 


		$uploads_dir = '../../dimg/slider';
		@$tmp_name = $_FILES['slider_resimyol']["tmp_name"];
		@$name = $_FILES['slider_resimyol']["name"];
		$benzersizsayi1=rand(20000,32000);
		$benzersizsayi2=rand(20000,32000);
		$benzersizsayi3=rand(20000,32000);
		$benzersizsayi4=rand(20000,32000);
		$benzersizad=$benzersizsayi1.$benzersizsayi2.$benzersizsayi3.$benzersizsayi4;
		$refimgyol=substr($uploads_dir, 6)."/".$benzersizad.$name;
		@move_uploaded_file($tmp_name, "$uploads_dir/$benzersizad$name");

		$duzenle=$db->prepare("UPDATE slider SET
			slider_ad=:ad,
			slider_link=:link,
			slider_sira=:sira,
			slider_durum=:durum,
			slider_resimyol=:resimyol	
			WHERE slider_id={$_POST['slider_id']}");
		$update=$duzenle->execute(array(
			'ad' => $_POST['slider_ad'],
			'link' => $_POST['slider_link'],
			'sira' => $_POST['slider_sira'],
			'durum' => $_POST['slider_durum'],
			'resimyol' => $refimgyol,
			));
		

		$slider_id=$_POST['slider_id'];

		if ($update) {

			$resimsilunlink=$_POST['slider_resimyol'];
			unlink("../../$resimsilunlink");

			Header("Location:../production/slider-duzenle.php?slider_id=$slider_id&durum=ok");

		} else {

			Header("Location:../production/slider-duzenle.php?durum=no");
		}



	} else {

		$duzenle=$db->prepare("UPDATE slider SET
			slider_ad=:ad,
			slider_link=:link,
			slider_sira=:sira,
			slider_durum=:durum		
			WHERE slider_id={$_POST['slider_id']}");
		$update=$duzenle->execute(array(
			'ad' => $_POST['slider_ad'],
			'link' => $_POST['slider_link'],
			'sira' => $_POST['slider_sira'],
			'durum' => $_POST['slider_durum']
			));

		$slider_id=$_POST['slider_id'];

		if ($update) {

			Header("Location:../production/slider-duzenle.php?slider_id=$slider_id&durum=ok");

		} else {

			Header("Location:../production/slider-duzenle.php?durum=no");
		}
	}

}



if ($_GET['todo_important']=="ok") {
	
	$duzenle=$db->prepare("UPDATE todo SET

		todo_important=:todo_important
		WHERE todo_id={$_GET['todo_id']}");
	$update=$duzenle->execute(array(

		'todo_important' => 1

		));



	if ($duzenle) {

		$resimsilunlink=$_GET['slider_resimyol'];
		unlink("../../$resimsilunlink");

		Header("Location:../apps_todoList.php?durum=ok");

	} else {

		Header("Location:../apps_todoList.php?durum=no");
	}

}



if ($_GET['yetkilendir']=="ok") {
	
	$duzenle=$db->prepare("UPDATE kullanici SET

		kullanici_yetki=:kullanici_yetki
		WHERE kullanici_id={$_GET['kullanici_id']}");
	$update=$duzenle->execute(array(

		'kullanici_yetki' => 5

		));


		
	if ($duzenle) {

		$resimsilunlink=$_GET['slider_resimyol'];
		unlink("../../$resimsilunlink");

		Header("Location:../table_dt_html5.php?durum=ok");

	} else {

		Header("Location:../table_dt_html5.php?durum=no");
	}

}



if ($_GET['addlike']=="ok") {
	
$art=intval($_GET['sss_like'])+1;

	$duzenle=$db->prepare("UPDATE sss SET

		sss_like=:sss_like
		WHERE sss_id={$_GET['sss_id']}");
	$update=$duzenle->execute(array(

		'sss_like' => $art

		));



	if ($duzenle) {

		$resimsilunlink=$_GET['slider_resimyol'];
		unlink("../../$resimsilunlink");

		Header("Location:../pages_faq.php?durum=ok");

	} else {

		Header("Location:../pages_faq.php?durum=no");
	}

}



if ($_GET['note_privacy']=="ok") {
	
	$duzenle=$db->prepare("UPDATE notes SET

		note_priv=:note_priv
		WHERE note_id={$_GET['note_id']}");
	$update=$duzenle->execute(array(

		'note_priv' => $_GET['note_priv']

		));



	if ($duzenle) {

		$resimsilunlink=$_GET['slider_resimyol'];
		unlink("../../$resimsilunlink");

		Header("Location:../apps_notes.php?durum=ok");

	} else {

		Header("Location:../apps_notes.php?durum=no");
	}

}


if ($_GET['note_fav']=="ok") {
	
	$duzenle=$db->prepare("UPDATE notes SET

		note_important=:note_important
		WHERE note_id={$_GET['note_id']}");
	$update=$duzenle->execute(array(

		'note_important' => $_GET['note_important']

		));



	if ($duzenle) {

		$resimsilunlink=$_GET['slider_resimyol'];
		unlink("../../$resimsilunlink");

		Header("Location:../apps_notes.php?durum=ok");

	} else {

		Header("Location:../apps_notes.php?durum=no");
	}

}


if ($_GET['note_delete']=="ok") {
	
	$duzenle=$db->prepare("UPDATE notes SET

		note_deleted=:note_deleted
		WHERE note_id={$_GET['note_id']}");
	$update=$duzenle->execute(array(

		'note_deleted' => 1

		));



	if ($duzenle) {

		$resimsilunlink=$_GET['slider_resimyol'];
		unlink("../../$resimsilunlink");

		Header("Location:../apps_notes.php?durum=ok");

	} else {

		Header("Location:../apps_notes.php?durum=no");
	}

}



if ($_GET['todo_important']=="unok") {
	
	$duzenle=$db->prepare("UPDATE todo SET

		todo_important=:todo_important	
		WHERE todo_id={$_GET['todo_id']}");
	$update=$duzenle->execute(array(

		'todo_important' => 0

		));



	if ($duzenle) {

		$resimsilunlink=$_GET['slider_resimyol'];
		unlink("../../$resimsilunlink");

		Header("Location:../apps_todoList.php?durum=ok");

	} else {

		Header("Location:../apps_todoList.php?durum=no");
	}

}


if ($_GET['todo_delete']=="ok") {
	
	$duzenle=$db->prepare("UPDATE todo SET

	todo_deleted=:todo_deleted	
		WHERE todo_id={$_GET['todo_id']}");
	$update=$duzenle->execute(array(

		'todo_deleted' => 1

		));



	if ($duzenle) {


		Header("Location:../apps_todoList.php?durum=ok");

	} else {

		Header("Location:../apps_todoList.php?durum=no");
	}

}
if ($_GET['todo_delete']=="unok") {
	
	$duzenle=$db->prepare("UPDATE todo SET

	todo_deleted=:todo_deleted	
		WHERE todo_id={$_GET['todo_id']}");
	$update=$duzenle->execute(array(

		'todo_deleted' => 0

		));



	if ($duzenle) {


		Header("Location:../apps_todoList.php?durum=ok");

	} else {

		Header("Location:../apps_todoList.php?durum=no");
	}

}






// Slider Düzenleme Bitiş

if ($_GET['kullanici_sil']=="ok") {
	
	$sil=$db->prepare("DELETE from kullanici where kullanici_id=:kullanici_id");
	$kontrol=$sil->execute(array(
		'kullanici_id' => $_GET['kullanici_id']
		));

	if ($kontrol) {

		$resimsilunlink=$_GET['slider_resimyol'];
		unlink("../../$resimsilunlink");

		Header("Location:../table_dt_html5.php?durum=ok");

	} else {

		Header("Location:../table_dt_html5.php?durum=no");
	}

}

if ($_GET['reklamsil']=="ok") {
	
	$sil=$db->prepare("DELETE from siparis where siparis_id=:siparis_id");
	$kontrol=$sil->execute(array(
		'siparis_id' => $_GET['siparis_id']
		));

	if ($kontrol) {



		Header("Location:../production/reklam.php?durum=ok");

	} else {

		Header("Location:../production/reklam.php?durum=no");
	}

}

if ($_GET['fatura_sil']=="ok") {
	
	$sil=$db->prepare("DELETE from payment_system where payment_id=:payment_id");
	$kontrol=$sil->execute(array(
		'payment_id' => $_GET['payment_id']
		));

	if ($kontrol) {



		Header("Location:../apps_invoice-list.php?durum=ok");

	} else {

		Header("Location:../apps_invoice-list.php?durum=no");
	}

}



if (isset($_POST['baskasinagonder'])) {

	$kullanicisor=$db->prepare("SELECT * FROM kullanici where kullanici_mail=:mail");
	$kullanicisor->execute(array(
	  'mail' => $_POST['mailadres']
	  ));
	$kullanicicek=$kullanicisor->fetch(PDO::FETCH_ASSOC);
	

	
	
	
	$kaydet=$db->prepare("INSERT INTO scrumboard SET
		scrumb_title=:scrumb_title,
		scrumb_content=:scrumb_content,
		scrumb_category=:scrumb_category


		");
	$insert=$kaydet->execute(array(
		'scrumb_title' => $_POST['scrumb_title'],
		'scrumb_content' => $_POST['scrumb_content'],
		'scrumb_category' => 10

		));







	if ($insert) {

		Header("Location:../apps_scrumboard.php?durum=ok");

	} else {

		Header("Location:../apps_scrumboard.php?durum=no");
	}

}



if (isset($_POST['general_kayit'])) {

	

	$uploads_dir = '../dimg/slider';

	@$tmp_name = $_FILES['kullanici_resim']["tmp_name"];
	@$name = $_FILES['kullanici_resim']["name"];

	$benzersizsayi4=rand(20000,32000);
	$refimgyol=substr($uploads_dir, 2)."/".$benzersizsayi4.$name;

	@move_uploaded_file($tmp_name, "$uploads_dir/$benzersizsayi4$name");

	
	$duzenle=$db->prepare("UPDATE kullanici SET
		kullanici_resim=:logo,
		kullanici_ad=:kullanici_ad,
		user_day=:user_day,
		user_month=:user_month,
		user_year=:user_year,
		user_job=:user_job
		WHERE kullanici_id={$_POST['kullanici_id']}");
	$update=$duzenle->execute(array(
		'logo' => $refimgyol,
		'kullanici_ad' => $_POST['kullanici_ad'],
		'user_day' => $_POST['user_day'],
		'user_month' => $_POST['user_month'],
		'user_year' => $_POST['user_year'],
		'user_job' => $_POST['user_job']

		));



	if ($update) {

		$resimsilunlink=$_POST['eski_yol'];
		unlink("../../$resimsilunlink");

		Header("Location:../user_account_setting.php?durum=ok");

	} else {

		Header("Location:../user_account_setting.php?durum=no");
	}

}


if (isset($_POST['aciklamakayit'])) {

	


	
	$duzenle=$db->prepare("UPDATE kullanici SET
	
		kullanici_aciklama=:kullanici_aciklama
		WHERE kullanici_id={$_POST['kullanici_id']}");
	$update=$duzenle->execute(array(

		'kullanici_aciklama' => $_POST['kullanici_aciklama']

		));



	if ($update) {


		Header("Location:../user_account_setting.php?durum=ok");

	} else {

		Header("Location:../user_account_setting.php?durum=no");
	}

}


if (isset($_POST['iletisim_kayit'])) {

	


	
	$duzenle=$db->prepare("UPDATE kullanici SET
	
	user_website=:user_website,
		kullanici_gsm=:kullanici_gsm,
		kullanici_adres=:kullanici_adres,
		user_country=:user_country
		WHERE kullanici_id={$_POST['kullanici_id']}");
	$update=$duzenle->execute(array(

		'user_website' => $_POST['user_website'],
		'kullanici_gsm' => $_POST['kullanici_gsm'],
		'kullanici_adres' => $_POST['kullanici_adres'],
		'user_country' => $_POST['user_country']

		));



	if ($update) {


		Header("Location:../user_account_setting.php?durum=ok");

	} else {

		Header("Location:../user_account_setting.php?durum=no");
	}

}


if (isset($_POST['sosyalkayit'])) {

	


	
	$duzenle=$db->prepare("UPDATE kullanici SET
	
	kullanici_insta=:kullanici_insta,
	kullanici_youtube=:kullanici_youtube,
	kullanici_twitch=:kullanici_twitch,
	kullanici_twitter=:kullanici_twitter
		WHERE kullanici_id={$_POST['kullanici_id']}");
	$update=$duzenle->execute(array(

		'kullanici_insta' => $_POST['kullanici_insta'],
		'kullanici_youtube' => $_POST['kullanici_youtube'],
		'kullanici_twitch' => $_POST['kullanici_twitch'],
		'kullanici_twitter' => $_POST['kullanici_twitter']

		));



	if ($update) {


		Header("Location:../user_account_setting.php?durum=ok");

	} else {

		Header("Location:../user_account_setting.php?durum=no");
	}

}





if (isset($_POST['fotoekleme'])) {

	

	$uploads_dir = '../../dimg';

	@$tmp_name = $_FILES['bank_img1']["tmp_name"];
	@$name = $_FILES['bank_img1']["name"];

	@$tmp_name1 = $_FILES['bank_img2']["tmp_name"];
	@$name1 = $_FILES['bank_img2']["name"];

	@$tmp_name2 = $_FILES['bank_img3']["tmp_name"];
	@$name2 = $_FILES['bank_img3']["name"];

	$benzersizsayi4=rand(20000,32000);
	$benzersizsayi5=rand(20000,32000);
	$benzersizsayi6=rand(20000,32000);
	$refimgyol=substr($uploads_dir, 6)."/".$benzersizsayi4.$name;
	$refimgyol2=substr($uploads_dir, 6)."/".$benzersizsayi5.$name;
	$refimgyol3=substr($uploads_dir, 6)."/".$benzersizsayi6.$name;

	@move_uploaded_file($tmp_name, "$uploads_dir/$benzersizsayi4$name");
	@move_uploaded_file($tmp_name1, "$uploads_dir/$benzersizsayi5$name");
	@move_uploaded_file($tmp_name2, "$uploads_dir/$benzersizsayi6$name");

	
	$duzenle=$db->prepare("UPDATE banka SET
		bank_img1=:bank_img1,
		bank_img2=:bank_img2,
		bank_img3=:bank_img3
		WHERE banka_id={$_POST['banka_id']}");
	$update=$duzenle->execute(array(
		'bank_img1' => $refimgyol,
		'bank_img2' => $refimgyol2,
		'bank_img3' => $refimgyol3
		));



	if ($update) {

		Header("Location:../production/banka.php?durum=ok");

	} else {

		Header("Location:../production/banka.php?durum=no");
	}

}




if (isset($_POST['admingiris'])) {

	$kullanici_mail=$_POST['kullanici_mail'];
	$kullanici_password=md5($_POST['kullanici_password']);

	$kullanicisor=$db->prepare("SELECT * FROM kullanici where kullanici_mail=:mail and kullanici_password=:password");
	$kullanicisor->execute(array(
		'mail' => $kullanici_mail,
		'password' => $kullanici_password
		));

	echo $say=$kullanicisor->rowCount();

	if ($say==1) {

		$_SESSION['kullanici_mail']=$kullanici_mail;
		header("Location:../production/index.php");
		exit;



	} else {

		header("Location:../production/login.php?durum=no");
		exit;
	}
	

}




if (isset($_POST['kullanicigiris'])) {


	
	echo $kullanici_mail=htmlspecialchars($_POST['kullanici_mail']); 
	echo $kullanici_password=md5($_POST['kullanici_password']); 

	$gelen_url=$_POST['gelen_url'];

	$kullanicisor=$db->prepare("select * from kullanici where kullanici_mail=:mail and kullanici_password=:password");
	$kullanicisor->execute(array(
		'mail' => $kullanici_mail,

		'password' => $kullanici_password

		));


	$say=$kullanicisor->rowCount();



	if ($say==1) {

		echo $_SESSION['kullanici_mail']=$kullanici_mail;

		header("Location:../index.php");
		exit;
		




	} else {


		header("Location:../../index.php");

	}


}






if (isset($_POST['changeuser'])) {
	
	//Tablo güncelleme işlemi kodları...
	$ayarkaydet=$db->prepare("UPDATE kullanici SET
		kullanici_adsoyad=:kullanici_adsoyad,
		kullanici_gsm=:kullanici_gsm,
		kullanici_adres=:kullanici_adres,
		kullanici_aciklama=:kullanici_aciklama,
		kullanici_face=:kullanici_face,
		kullanici_twitter=:kullanici_twitter,
		kullanici_insta=:kullanici_insta,
		kullanici_youtube=:kullanici_youtube,
		kullanici_durum=:kullanici_durum
		WHERE kullanici_id={$_POST['kullanici_id']}");

	$update=$ayarkaydet->execute(array(
		'kullanici_adsoyad' => $_POST['kullanici_adsoyad'],
		'kullanici_gsm' => $_POST['kullanici_gsm'],
		'kullanici_adres' => $_POST['kullanici_adres'],
		'kullanici_aciklama' => $_POST['kullanici_aciklama'],
		'kullanici_face' => $_POST['kullanici_face'],
		'kullanici_twitter' => $_POST['kullanici_twitter'],
		'kullanici_insta' => $_POST['kullanici_insta'],
		'kullanici_youtube' => $_POST['kullanici_youtube'],
		'kullanici_durum' => $_POST['kullanici_durum']
		));



	if ($update) {

		header("Location:../production/genel-ayar.php?durum=ok");

	} else {

		header("Location:../production/genel-ayar.php?durum=no");
	}
	
}


if (isset($_POST['videoduzenle'])) {
	
	//Tablo güncelleme işlemi kodları...
	$ayarkaydet=$db->prepare("UPDATE video SET

		video_place=:video_place
		WHERE video_id={$_POST['video_id']}");

	$update=$ayarkaydet->execute(array(

		'video_place' => $_POST['video_place']
		));



	if ($update) {

		header("Location:../production/menu.php?durum=ok");

	} else {

		header("Location:../production/menu.php?durum=no");
	}
	
}



if (isset($_POST['iletisimayarkaydet'])) {
	
	//Tablo güncelleme işlemi kodları...
	$ayarkaydet=$db->prepare("UPDATE ayar SET
		ayar_tel=:ayar_tel,
		ayar_gsm=:ayar_gsm,
		ayar_faks=:ayar_faks,
		ayar_mail=:ayar_mail,
		ayar_ilce=:ayar_ilce,
		ayar_il=:ayar_il,
		ayar_adres=:ayar_adres,
		ayar_mesai=:ayar_mesai
		WHERE ayar_id=0");

	$update=$ayarkaydet->execute(array(
		'ayar_tel' => $_POST['ayar_tel'],
		'ayar_gsm' => $_POST['ayar_gsm'],
		'ayar_faks' => $_POST['ayar_faks'],
		'ayar_mail' => $_POST['ayar_mail'],
		'ayar_ilce' => $_POST['ayar_ilce'],
		'ayar_il' => $_POST['ayar_il'],
		'ayar_adres' => $_POST['ayar_adres'],
		'ayar_mesai' => $_POST['ayar_mesai']
		));


	if ($update) {

		header("Location:../production/iletisim-ayarlar.php?durum=ok");

	} else {

		header("Location:../production/iletisim-ayarlar.php?durum=no");
	}
	
}




if (isset($_POST['hakkimizdakaydet'])) {
	
	//Tablo güncelleme işlemi kodları...

	/*

	copy paste işlemlerinde tablo ve işaretli satır isminin değiştirildiğinden emin olun!!!

	*/
	$ayarkaydet=$db->prepare("UPDATE hakkimizda SET
		hakkimizda_baslik=:hakkimizda_baslik,
		hakkimizda_icerik=:hakkimizda_icerik,
		hakkimizda_video=:hakkimizda_video,
		hakkimizda_vizyon=:hakkimizda_vizyon,
		hakkimizda_misyon=:hakkimizda_misyon
		WHERE hakkimizda_id=0");

	$update=$ayarkaydet->execute(array(
		'hakkimizda_baslik' => $_POST['hakkimizda_baslik'],
		'hakkimizda_icerik' => $_POST['hakkimizda_icerik'],
		'hakkimizda_video' => $_POST['hakkimizda_video'],
		'hakkimizda_vizyon' => $_POST['hakkimizda_vizyon'],
		'hakkimizda_misyon' => $_POST['hakkimizda_misyon']
		));


	if ($update) {

		header("Location:../production/hakkimizda.php?durum=ok");

	} else {

		header("Location:../production/hakkimizda.php?durum=no");
	}
	
}



if (isset($_POST['kullaniciduzenle'])) {

	$kullanici_id=$_POST['kullanici_id'];

	$ayarkaydet=$db->prepare("UPDATE kullanici SET
		kullanici_gsm=:kullanici_gsm,
		kullanici_adsoyad=:kullanici_adsoyad,
		kullanici_aciklama=:kullanici_aciklama,
		kullanici_face=:kullanici_face,
		kullanici_twitter=:kullanici_twitter,
		kullanici_insta=:kullanici_insta,
		kullanici_youtube=:kullanici_youtube,
		kullanici_adres=:kullanici_adres
		WHERE kullanici_id={$_POST['kullanici_id']}");

	$update=$ayarkaydet->execute(array(
		'kullanici_gsm' => $_POST['kullanici_gsm'],
		'kullanici_adsoyad' => $_POST['kullanici_adsoyad'],
		'kullanici_aciklama' => $_POST['kullanici_aciklama'],
		'kullanici_face' => $_POST['kullanici_face'],
		'kullanici_twitter' => $_POST['kullanici_twitter'],
		'kullanici_insta' => $_POST['kullanici_insta'],
		'kullanici_youtube' => $_POST['kullanici_youtube'],
		'kullanici_adres' => $_POST['kullanici_adres']
		));


	if ($update) {

		Header("Location:../production/genel-ayar.php?kullanici_id=$kullanici_id&durum=ok");

	} else {

		Header("Location:../production/genel-ayar.php?kullanici_id=$kullanici_id&durum=no");
	}

}


if (isset($_POST['kullanicibilgiguncelle'])) {

	$kullanici_id=$_POST['kullanici_id'];

	$ayarkaydet=$db->prepare("UPDATE kullanici SET
		kullanici_adsoyad=:kullanici_adsoyad,
		kullanici_il=:kullanici_il,
		kullanici_ilce=:kullanici_ilce
		WHERE kullanici_id={$_POST['kullanici_id']}");

	$update=$ayarkaydet->execute(array(
		'kullanici_adsoyad' => $_POST['kullanici_adsoyad'],
		'kullanici_il' => $_POST['kullanici_il'],
		'kullanici_ilce' => $_POST['kullanici_ilce']
		));


	if ($update) {

		Header("Location:../../hesabim?durum=ok");

	} else {

		Header("Location:../../hesabim?durum=no");
	}

}
if (isset($_POST['giveperm'])) {

	$kullanici_id=$_POST['kullanici_id'];

	$ayarkaydet=$db->prepare("UPDATE kullanici SET

		kullanici_yetki=:kullanici_yetki
		WHERE kullanici_id={$_POST['kullanici_id']}");

	$update=$ayarkaydet->execute(array(

		'kullanici_yetki' => $_POST['kullanici_yetki']
		));


	if ($update) {

		Header("Location:../production/kullanici.php?durum=ok");

	} else {

		Header("Location:../production/kullanici.php?durum=no");
	}

}


if ($_GET['kullanicisil']=="ok") {

	$sil=$db->prepare("DELETE from kullanici where kullanici_id=:id");
	$kontrol=$sil->execute(array(
		'id' => $_GET['kullanici_id']
		));


	if ($kontrol) {


		header("location:../production/kullanici.php?sil=ok");


	} else {

		header("location:../production/kullanici.php?sil=no");

	}


}


if (isset($_POST['menuduzenle'])) {

	$menu_id=$_POST['menu_id'];

	$menu_seourl=seo($_POST['menu_ad']);

	
	$ayarkaydet=$db->prepare("UPDATE menu SET
		menu_ad=:menu_ad,
		menu_detay=:menu_detay,
		menu_url=:menu_url,
		menu_sira=:menu_sira,
		menu_seourl=:menu_seourl,
		menu_durum=:menu_durum
		WHERE menu_id={$_POST['menu_id']}");

	$update=$ayarkaydet->execute(array(
		'menu_ad' => $_POST['menu_ad'],
		'menu_detay' => $_POST['menu_detay'],
		'menu_url' => $_POST['menu_url'],
		'menu_sira' => $_POST['menu_sira'],
		'menu_seourl' => $menu_seourl,
		'menu_durum' => $_POST['menu_durum']
		));


	if ($update) {

		Header("Location:../production/menu-duzenle.php?menu_id=$menu_id&durum=ok");

	} else {

		Header("Location:../production/menu-duzenle.php?menu_id=$menu_id&durum=no");
	}

}


if ($_GET['iletisimsil']=="ok") {

	$sil=$db->prepare("DELETE from menu where menu_id=:id");
	$kontrol=$sil->execute(array(
		'id' => $_GET['menu_id']
		));


	if ($kontrol) {


		header("location:../production/iletişim.php?sil=ok");


	} else {

		header("location:../production/iletişim.php?sil=no");

	}


}
if ($_GET['videosil']=="ok") {

	$sil=$db->prepare("DELETE from video where video_id=:id");
	$kontrol=$sil->execute(array(
		'id' => $_GET['menu_id']
		));


	if ($kontrol) {


		header("location:../production/menu.php?sil=ok");


	} else {

		header("location:../production/menu.php?sil=no");

	}


}



if (isset($_POST['videoekle'])) {

	$uploads_dir = '../../dimg/slider';
	@$tmp_name = $_FILES['video_foto']["tmp_name"];
	@$name = $_FILES['video_foto']["name"];
	//resmin isminin benzersiz olması
	$benzersizsayi1=rand(20000,32000);
	$benzersizsayi2=rand(20000,32000);
	$benzersizsayi3=rand(20000,32000);
	$benzersizsayi4=rand(20000,32000);	
	$benzersizad=$benzersizsayi1.$benzersizsayi2.$benzersizsayi3.$benzersizsayi4;
	$refimgyol=substr($uploads_dir, 6)."/".$benzersizad.$name;
	@move_uploaded_file($tmp_name, "$uploads_dir/$benzersizad$name");




	$ayarekle=$db->prepare("INSERT INTO video SET
		video_sendby=:video_sendby,
		video_foto=:video_foto,
		video_username=:video_username,
		video_title=:video_title,
		video_sub=:video_sub,
		video_text=:video_text,
		video_link=:video_link
		");

	$insert=$ayarekle->execute(array(
		'video_sendby' => $_POST['video_sendby'],
		'video_username' => $_POST['video_username'],
		'video_title' => $_POST['video_title'],
		'video_sub' => $_POST['video_sub'],
		'video_text' => $_POST['video_text'],
		'video_foto' => $refimgyol,
		'video_link' => $_POST['video_link']
		));


	if ($insert) {

		Header("Location:../production/menu.php?durum=ok");

	} else {

		Header("Location:../production/menu.php?durum=no");
	}

}




if (isset($_POST['fatura_kaydet'])) {

	


	$uploads_dir = '../../dimg/slider';
	@$tmp_name = $_FILES['payment_logo']["tmp_name"];
	@$name = $_FILES['payment_logo']["name"];
	//resmin isminin benzersiz olması
	$benzersizsayi1=rand(20000,32000);
	
	$benzersizad=$benzersizsayi1.$benzersizsayi2.$benzersizsayi3.$benzersizsayi4;
	$refimgyol=substr($uploads_dir, 6)."/".$benzersizad.$name;
	@move_uploaded_file($tmp_name, "$uploads_dir/$benzersizad$name");
	




	$ayarekle=$db->prepare("INSERT INTO payment_system SET
		payment_fromname=:payment_fromname,
		payment_fromemail=:payment_fromemail,
		payment_fromadres=:payment_fromadres,
		payment_fromphone=:payment_fromphone,
		payment_toname=:payment_toname,
		payment_toemail=:payment_toemail,
		payment_toadres=:payment_toadres,
		payment_tophone=:payment_tophone,
		payment_date=:payment_date,
		payment_duedate=:payment_duedate,
		payment_item1desc=:payment_item1desc,
		payment_item1details=:payment_item1details,
		payment_item1price=:payment_item1price,
		payment_item1qua=:payment_item1qua,
		payment_account=:payment_account,
		payment_cardmonth=:payment_cardmonth,
		payment_cardyear=:payment_cardyear,
		payment_cardname=:payment_cardname,
		payment_swift=:payment_swift,
		payment_country=:payment_country,
		payment_notes=:payment_notes,
		payment_logo=:payment_logo
		");

	$insert=$ayarekle->execute(array(
		'payment_fromname' => $_POST['payment_fromname'],
		'payment_fromemail' => $_POST['payment_fromemail'],
		'payment_fromadres' => $_POST['payment_fromadres'],
		'payment_fromphone' => $_POST['payment_fromphone'],
		'payment_toname' => $_POST['payment_toname'],
		'payment_toemail' => $_POST['payment_toemail'],
		'payment_toadres' => $_POST['payment_toadres'],
		'payment_tophone' => $_POST['payment_tophone'],
		'payment_date' => $_POST['payment_date'],
		'payment_duedate' => $_POST['payment_duedate'],
		'payment_item1desc' => $_POST['payment_item1desc'],
		'payment_item1details' => $_POST['payment_item1details'],
		'payment_item1price' => $_POST['payment_item1price'],
		'payment_item1qua' => $_POST['payment_item1qua'],
		'payment_account' => $_POST['payment_account'],
		'payment_cardmonth' => $_POST['payment_cardmonth'],
		'payment_cardyear' => $_POST['payment_cardyear'],
		'payment_cardname' => $_POST['payment_cardname'],
		'payment_swift' => $_POST['payment_swift'],
		'payment_country' => $_POST['payment_country'],
		'payment_toname' => $_POST['payment_toname'],
		'payment_notes' => $_POST['payment_notes'],

		'payment_logo' => $refimgyol

		));


	if ($insert) {

		Header("Location:../production/menu.php?durum=ok");

	} else {

		Header("Location:../production/menu.php?durum=no");
	}

}


if (isset($_POST['fatura_change'])) {

	


	$uploads_dir = '../../dimg/slider';
	@$tmp_name = $_FILES['payment_logo']["tmp_name"];
	@$name = $_FILES['payment_logo']["name"];
	//resmin isminin benzersiz olması
	$benzersizsayi1=rand(20000,32000);
	
	$benzersizad=$benzersizsayi1.$benzersizsayi2.$benzersizsayi3.$benzersizsayi4;
	$refimgyol=substr($uploads_dir, 6)."/".$benzersizad.$name;
	@move_uploaded_file($tmp_name, "$uploads_dir/$benzersizad$name");
	




	$ayarekle=$db->prepare("UPDATE payment_system SET
		payment_fromname=:payment_fromname,
		payment_fromemail=:payment_fromemail,
		payment_fromadres=:payment_fromadres,
		payment_fromphone=:payment_fromphone,
		payment_toname=:payment_toname,
		payment_toemail=:payment_toemail,
		payment_toadres=:payment_toadres,
		payment_tophone=:payment_tophone,
		payment_date=:payment_date,
		payment_duedate=:payment_duedate,
		payment_item1desc=:payment_item1desc,
		payment_item1details=:payment_item1details,
		payment_item1price=:payment_item1price,
		payment_item1qua=:payment_item1qua,
		payment_account=:payment_account,
		payment_cardmonth=:payment_cardmonth,
		payment_cardyear=:payment_cardyear,
		payment_cardname=:payment_cardname,
		payment_swift=:payment_swift,
		payment_country=:payment_country,
		payment_notes=:payment_notes,
		payment_logo=:payment_logo
		WHERE payment_id={$_POST['payment_id']}");


	$insert=$ayarekle->execute(array(
		'payment_fromname' => $_POST['payment_fromname'],
		'payment_fromemail' => $_POST['payment_fromemail'],
		'payment_fromadres' => $_POST['payment_fromadres'],
		'payment_fromphone' => $_POST['payment_fromphone'],
		'payment_toname' => $_POST['payment_toname'],
		'payment_toemail' => $_POST['payment_toemail'],
		'payment_toadres' => $_POST['payment_toadres'],
		'payment_tophone' => $_POST['payment_tophone'],
		'payment_date' => $_POST['payment_date'],
		'payment_duedate' => $_POST['payment_duedate'],
		'payment_item1desc' => $_POST['payment_item1desc'],
		'payment_item1details' => $_POST['payment_item1details'],
		'payment_item1price' => $_POST['payment_item1price'],
		'payment_item1qua' => $_POST['payment_item1qua'],
		'payment_account' => $_POST['payment_account'],
		'payment_cardmonth' => $_POST['payment_cardmonth'],
		'payment_cardyear' => $_POST['payment_cardyear'],
		'payment_cardname' => $_POST['payment_cardname'],
		'payment_swift' => $_POST['payment_swift'],
		'payment_country' => $_POST['payment_country'],
		'payment_toname' => $_POST['payment_toname'],
		'payment_notes' => $_POST['payment_notes'],

		'payment_logo' => $refimgyol

		));


	if ($insert) {

		Header("Location:../apps_invoice-edit.php?durum=ok");

	} else {

		Header("Location:../apps_invoice-edit.php?durum=no");
	}

}



if (isset($_POST['kategoriduzenle'])) {

	$kategori_id=$_POST['kategori_id'];
	$kategori_seourl=seo($_POST['kategori_ad']);

	
	$kaydet=$db->prepare("UPDATE kategori SET
		kategori_ad=:ad,
		kategori_durum=:kategori_durum,	
		kategori_seourl=:seourl,
		kategori_sira=:sira
		WHERE kategori_id={$_POST['kategori_id']}");
	$update=$kaydet->execute(array(
		'ad' => $_POST['kategori_ad'],
		'kategori_durum' => $_POST['kategori_durum'],
		'seourl' => $kategori_seourl,
		'sira' => $_POST['kategori_sira']		
		));

	if ($update) {

		Header("Location:../production/kategori-duzenle.php?durum=ok&kategori_id=$kategori_id");

	} else {

		Header("Location:../production/kategori-duzenle.php?durum=no&kategori_id=$kategori_id");
	}

}


if (isset($_POST['kategoriekle'])) {

	$kategori_seourl=seo($_POST['kategori_ad']);

	$kaydet=$db->prepare("INSERT INTO kategori SET
		kategori_ad=:ad,
		kategori_durum=:kategori_durum,	
		kategori_seourl=:seourl,
		kategori_sira=:sira
		");
	$insert=$kaydet->execute(array(
		'ad' => $_POST['kategori_ad'],
		'kategori_durum' => $_POST['kategori_durum'],
		'seourl' => $kategori_seourl,
		'sira' => $_POST['kategori_sira']		
		));

	if ($insert) {

		Header("Location:../production/kategori.php?durum=ok");

	} else {

		Header("Location:../production/kategori.php?durum=no");
	}

}


if (isset($_POST['scrumbekle'])) {



	$kaydet=$db->prepare("INSERT INTO scrumb_category SET

scrumbcategory_title=:scrumbcategory_title,	

		scrumbcategory_sendby=:scrumbcategory_sendby
		");
	$insert=$kaydet->execute(array(

		'scrumbcategory_title' => $_POST['scrumbcategory_title'],
		
		'scrumbcategory_sendby' => $_POST['scrumbcategory_sendby']		
		));

	if ($insert) {

		Header("Location:../apps_scrumboard.php?durum=ok");

	} else {

		Header("Location:../apps_scrumboard.php?durum=no");
	}

}


if (isset($_POST['scrumbcategoryekle'])) {



	$kaydet=$db->prepare("INSERT INTO scrumboard SET

scrumb_come=:scrumb_come,	
scrumb_category=:scrumb_category,	
scrumb_content=:scrumb_content,	

scrumb_title=:scrumb_title
		");
	$insert=$kaydet->execute(array(

		'scrumb_come' => $_POST['scrumb_come'],
		'scrumb_category' => $_POST['scrumb_category'],
		'scrumb_content' => $_POST['scrumb_content'],
		
		'scrumb_title' => $_POST['scrumb_title']		
		));

	if ($insert) {

		Header("Location:../apps_scrumboard.php?durum=ok");

	} else {

		Header("Location:../apps_scrumboard.php?durum=no");
	}

}


if ($_GET['kategorisil']=="ok") {
	
	$sil=$db->prepare("DELETE from kategori where kategori_id=:kategori_id");
	$kontrol=$sil->execute(array(
		'kategori_id' => $_GET['kategori_id']
		));

	if ($kontrol) {

		Header("Location:../production/kategori.php?durum=ok");

	} else {

		Header("Location:../production/kategori.php?durum=no");
	}

}

if ($_GET['urunsil']=="ok") {
	
	$sil=$db->prepare("DELETE from urun where urun_id=:urun_id");
	$kontrol=$sil->execute(array(
		'urun_id' => $_GET['urun_id']
		));

	if ($kontrol) {

		Header("Location:../production/urun.php?durum=ok");

	} else {

		Header("Location:../production/urun.php?durum=no");
	}

}

if ($_GET['scrumbsil']=="ok") {
	
	$sil=$db->prepare("DELETE from scrumboard where scrumb_id=:scrumb_id");
	$kontrol=$sil->execute(array(
		'scrumb_id' => $_GET['scrumb_id']
		));

	if ($kontrol) {

		Header("Location:../apps_scrumboard.php?durum=ok");

	} else {

		Header("Location:../apps_scrumboard.php?durum=no");
	}

}

if ($_GET['categorysil']=="ok") {
	
	$sil=$db->prepare("DELETE from scrumb_category where scrumb_id=:scrumb_id");
	$kontrol=$sil->execute(array(
		'scrumb_id' => $_GET['scrumb_id']
		));

	if ($kontrol) {

		Header("Location:../apps_scrumboard.php?durum=ok");

	} else {

		Header("Location:../apps_scrumboard.php?durum=no");
	}

}


if (isset($_POST['urunekle'])) {

	$urun_seourl=seo($_POST['urun_ad']);

	$kaydet=$db->prepare("INSERT INTO urun SET
		kategori_id=:kategori_id,
		urun_ad=:urun_ad,
		urun_detay=:urun_detay,
		urun_fiyat=:urun_fiyat,
		urun_video=:urun_video,
		urun_keyword=:urun_keyword,
		urun_durum=:urun_durum,
		urun_stok=:urun_stok,	
		urun_seourl=:seourl		
		");
	$insert=$kaydet->execute(array(
		'kategori_id' => $_POST['kategori_id'],
		'urun_ad' => $_POST['urun_ad'],
		'urun_detay' => $_POST['urun_detay'],
		'urun_fiyat' => $_POST['urun_fiyat'],
		'urun_video' => $_POST['urun_video'],
		'urun_keyword' => $_POST['urun_keyword'],
		'urun_durum' => $_POST['urun_durum'],
		'urun_stok' => $_POST['urun_stok'],
		'seourl' => $urun_seourl

		));

	if ($insert) {

		Header("Location:../production/urun.php?durum=ok");

	} else {

		Header("Location:../production/urun.php?durum=no");
	}

}

if (isset($_POST['urunduzenle'])) {

	$urun_id=$_POST['urun_id'];
	$urun_seourl=seo($_POST['urun_ad']);

	$kaydet=$db->prepare("UPDATE urun SET
		kategori_id=:kategori_id,
		urun_ad=:urun_ad,
		urun_detay=:urun_detay,
		urun_fiyat=:urun_fiyat,
		urun_video=:urun_video,
		urun_onecikar=:urun_onecikar,
		urun_keyword=:urun_keyword,
		urun_durum=:urun_durum,
		urun_stok=:urun_stok,	
		urun_seourl=:seourl		
		WHERE urun_id={$_POST['urun_id']}");
	$update=$kaydet->execute(array(
		'kategori_id' => $_POST['kategori_id'],
		'urun_ad' => $_POST['urun_ad'],
		'urun_detay' => $_POST['urun_detay'],
		'urun_fiyat' => $_POST['urun_fiyat'],
		'urun_video' => $_POST['urun_video'],
		'urun_onecikar' => $_POST['urun_onecikar'],
		'urun_keyword' => $_POST['urun_keyword'],
		'urun_durum' => $_POST['urun_durum'],
		'urun_stok' => $_POST['urun_stok'],
		'seourl' => $urun_seourl

		));

	if ($update) {

		Header("Location:../production/urun-duzenle.php?durum=ok&urun_id=$urun_id");

	} else {

		Header("Location:../production/urun-duzenle.php?durum=no&urun_id=$urun_id");
	}

}




if (isset($_POST['yorumkaydet'])) {

$comsayisi= (int)($_POST['banka_comment'])+1;
	$kaydet=$db->prepare("UPDATE banka SET

		banka_comment=:seourl		
		WHERE banka_id={$_POST['urun_id']}");
	$update=$kaydet->execute(array(

		'seourl' => $comsayisi

		));




	$gelen_url=$_POST['gelen_url'];

	$ayarekle=$db->prepare("INSERT INTO yorumlar SET
		yorum_detay=:yorum_detay,
		kullanici_id=:kullanici_id,
		urun_id=:urun_id	
		
		");

	$insert=$ayarekle->execute(array(
		'yorum_detay' => $_POST['yorum_detay'],
		'kullanici_id' => $_POST['kullanici_id'],
		'urun_id' => $_POST['urun_id']
		
		));


	if ($insert) {

		Header("Location:$gelen_url");

	} else {

		Header("Location:$gelen_url");
	}

}


if (isset($_POST['sepetekle'])) {


	$ayarekle=$db->prepare("INSERT INTO sepet SET
		urun_adet=:urun_adet,
		kullanici_id=:kullanici_id,
		urun_id=:urun_id	
		
		");

	$insert=$ayarekle->execute(array(
		'urun_adet' => $_POST['urun_adet'],
		'kullanici_id' => $_POST['kullanici_id'],
		'urun_id' => $_POST['urun_id']
		
		));


	if ($insert) {

		Header("Location:../../sepet?durum=ok");

	} else {

		Header("Location:../../sepet?durum=no");
	}

}


if ($_GET['urun_onecikar']=="ok") {

	

	
	$duzenle=$db->prepare("UPDATE urun SET
		
		urun_onecikar=:urun_onecikar
		
		WHERE urun_id={$_GET['urun_id']}");
	
	$update=$duzenle->execute(array(


		'urun_onecikar' => $_GET['urun_one']
		));



	if ($update) {

		

		Header("Location:../production/urun.php?durum=ok");

	} else {

		Header("Location:../production/urun.php?durum=no");
	}

}

if ($_GET['yorum_onay']=="ok") {

	
	$duzenle=$db->prepare("UPDATE yorumlar SET
		
		yorum_onay=:yorum_onay
		
		WHERE yorum_id={$_GET['yorum_id']}");
	
	$update=$duzenle->execute(array(

		'yorum_onay' => $_GET['yorum_one']
		));



	if ($update) {

		

		Header("Location:../production/yorum.php?durum=ok");

	} else {

		Header("Location:../production/yorum.php?durum=no");
	}

}



if ($_GET['yorumsil']=="ok") {
	
	$sil=$db->prepare("DELETE from yorumlar where yorum_id=:yorum_id");
	$kontrol=$sil->execute(array(
		'yorum_id' => $_GET['yorum_id']
		));

	if ($kontrol) {

		
		Header("Location:../production/yorum.php?durum=ok");

	} else {

		Header("Location:../production/yorum.php?durum=no");
	}

}




if ($_GET['seepost']=="ok") {
	
	$banka_id=$_GET['banka_id'];
	$banka_gorunen=(int)($_GET['banka_gorunen']);
	$banka_gorunen=$banka_gorunen+1;
	$duzenle=$db->prepare("UPDATE banka SET
		
		banka_gorunen=:banka_gorunen
		
		WHERE banka_id={$_GET['banka_id']}");
	
	$update=$duzenle->execute(array(

		'banka_gorunen' => $banka_gorunen
		));



	if ($update) {

		
		Header("Location:../../2016/10/03/top-tips-to-stay-hydrated/index.php?banka_id=$banka_id");

	} else {

		Header("Location:../../2016/10/03/top-tips-to-stay-hydrated/index.php?banka_id=$banka_id");
	}

}


if (isset($_POST['bankaekle'])) {



	$kaydet=$db->prepare("INSERT INTO banka SET
		banka_ad=:ad,
		banka_kisa=:banka_kisa,
		banka_hesapadsoyad=:banka_hesapadsoyad,
		banka_tags=:banka_tags,
		banka_konu=:banka_konu,
		banka_usage=:banka_usage,
		banka_usages=:banka_usages,
		banka_sendby=:banka_sendby,
		banka_iban=:banka_iban
		");
	$insert=$kaydet->execute(array(
		'ad' => $_POST['banka_ad'],
		'banka_kisa' => $_POST['banka_kisa'],
		'banka_hesapadsoyad' => $_POST['banka_hesapadsoyad'],
		'banka_tags' => $_POST['banka_tags'],
		'banka_konu' => $_POST['banka_konu'],
		'banka_usage' => $_POST['banka_usage'],
		'banka_usages' => $_POST['banka_usages'],
		'banka_sendby' => $_POST['banka_sendby'],
		'banka_iban' => $_POST['banka_iban']		
		));

	if ($insert) {
		
		Header("Location:../production/banka.php?durum=ok");

	} else {

		Header("Location:../production/banka.php?durum=no");
	}

}


if (isset($_POST['postguncelle'])) {

	$banka_id=$_POST['banka_id'];
	$banka_place=$_POST['banka_place'];
	$banka_durum=0;
	if($banka_place!=0){
		$banka_durum=1;

	}
	
	$kaydet=$db->prepare("UPDATE banka SET


		post_yetkili=:post_yetkili,	
		banka_durum=:banka_durum,
		banka_place=:banka_place

		WHERE banka_id={$_POST['banka_id']}");
	$update=$kaydet->execute(array(

		'post_yetkili' => $_POST['post_yetkili'],
		'banka_durum' => $banka_durum,
		'banka_place' => $_POST['banka_place']
		
		));

	if ($update) {

		Header("Location:../production/postdetay.php?banka_id=$banka_id&durum=ok");

	} else {

		Header("Location:../production/postdetay.php?banka_id=$banka_id&durum=no");
	}


	

}


if ($_GET['bankasil']=="ok") {
	
	$sil=$db->prepare("DELETE from banka where banka_id=:banka_id");
	$kontrol=$sil->execute(array(
		'banka_id' => $_GET['banka_id']
		));

	if ($kontrol) {

		
		Header("Location:../production/banka.php?durum=ok");

	} else {

		Header("Location:../production/banka.php?durum=no");
	}

}



if (isset($_POST['kullanicisifreguncelle'])) {

	echo $kullanici_eskipassword=trim($_POST['kullanici_eskipassword']); echo "<br>";
	echo $kullanici_passwordone=trim($_POST['kullanici_passwordone']); echo "<br>";
	echo $kullanici_passwordtwo=trim($_POST['kullanici_passwordtwo']); echo "<br>";

	$kullanici_password=md5($kullanici_eskipassword);


	$kullanicisor=$db->prepare("select * from kullanici where kullanici_password=:password");
	$kullanicisor->execute(array(
		'password' => $kullanici_password
		));

			//dönen satır sayısını belirtir
	$say=$kullanicisor->rowCount();



	if ($say==0) {

		header("Location:../../sifre-guncelle?durum=eskisifrehata");



	} else {



	//eski şifre doğruysa başla


		if ($kullanici_passwordone==$kullanici_passwordtwo) {


			if (strlen($kullanici_passwordone)>=6) {


				//md5 fonksiyonu şifreyi md5 şifreli hale getirir.
				$password=md5($kullanici_passwordone);

				$kullanici_yetki=1;

				$kullanicikaydet=$db->prepare("UPDATE kullanici SET
					kullanici_password=:kullanici_password
					WHERE kullanici_id={$_POST['kullanici_id']}");

				
				$insert=$kullanicikaydet->execute(array(
					'kullanici_password' => $password
					));

				if ($insert) {


					header("Location:../../sifre-guncelle.php?durum=sifredegisti");


				//Header("Location:../production/genel-ayarlar.php?durum=ok");

				} else {


					header("Location:../../sifre-guncelle.php?durum=no");
				}





		// Bitiş



			} else {


				header("Location:../../sifre-guncelle.php?durum=eksiksifre");


			}



		} else {

			header("Location:../../sifre-guncelle?durum=sifreleruyusmuyor");

			exit;


		}


	}

	exit;

	if ($update) {

		header("Location:../../sifre-guncelle?durum=ok");

	} else {

		header("Location:../../sifre-guncelle?durum=no");
	}

}


//Sipariş İşlemleri

if (isset($_POST['bankasiparisekle'])) {


	$siparis_tip="Banka Havalesi";


	$kaydet=$db->prepare("INSERT INTO siparis SET
		kullanici_id=:kullanici_id,
		siparis_tip=:siparis_tip,	
		siparis_banka=:siparis_banka,
		siparis_toplam=:siparis_toplam
		");
	$insert=$kaydet->execute(array(
		'kullanici_id' => $_POST['kullanici_id'],
		'siparis_tip' => $siparis_tip,
		'siparis_banka' => $_POST['siparis_banka'],
		'siparis_toplam' => $_POST['siparis_toplam']		
		));

	if ($insert) {

		//Sipariş başarılı kaydedilirse...

		echo $siparis_id = $db->lastInsertId();

		echo "<hr>";


		$kullanici_id=$_POST['kullanici_id'];
		$sepetsor=$db->prepare("SELECT * FROM sepet where kullanici_id=:id");
		$sepetsor->execute(array(
			'id' => $kullanici_id
			));

		while($sepetcek=$sepetsor->fetch(PDO::FETCH_ASSOC)) {

			$urun_id=$sepetcek['urun_id']; 
			$urun_adet=$sepetcek['urun_adet'];

			$urunsor=$db->prepare("SELECT * FROM urun where urun_id=:id");
			$urunsor->execute(array(
				'id' => $urun_id
				));

			$uruncek=$urunsor->fetch(PDO::FETCH_ASSOC);
			
			echo $urun_fiyat=$uruncek['urun_fiyat'];


			
			$kaydet=$db->prepare("INSERT INTO siparis_detay SET
				
				siparis_id=:siparis_id,
				urun_id=:urun_id,	
				urun_fiyat=:urun_fiyat,
				urun_adet=:urun_adet
				");
			$insert=$kaydet->execute(array(
				'siparis_id' => $siparis_id,
				'urun_id' => $urun_id,
				'urun_fiyat' => $urun_fiyat,
				'urun_adet' => $urun_adet

				));


		}

		if ($insert) {

			

			//Sipariş detay kayıtta başarıysa sepeti boşalt

			$sil=$db->prepare("DELETE from sepet where kullanici_id=:kullanici_id");
			$kontrol=$sil->execute(array(
				'kullanici_id' => $kullanici_id
				));

			
			Header("Location:../../siparislerim?durum=ok");
			exit;


		}

		




	} else {

		echo "başarısız";

		//Header("Location:../production/siparis.php?durum=no");
	}



}


if(isset($_POST['urunfotosil'])) {

	$urun_id=$_POST['urun_id'];


	echo $checklist = $_POST['urunfotosec'];

	
	foreach($checklist as $list) {

		$sil=$db->prepare("DELETE from urunfoto where urunfoto_id=:urunfoto_id");
		$kontrol=$sil->execute(array(
			'urunfoto_id' => $list
			));
	}

	if ($kontrol) {

		Header("Location:../production/urun-galeri.php?urun_id=$urun_id&durum=ok");

	} else {

		Header("Location:../production/urun-galeri.php?urun_id=$urun_id&durum=no");
	}


} 


if (isset($_POST['mailayarkaydet'])) {
	
	$ayarkaydet=$db->prepare("UPDATE ayar SET
		ayar_smtphost=:smtphost,
		ayar_smtpuser=:smtpuser,
		ayar_smtppassword=:smtppassword,
		ayar_smtpport=:smtpport
		WHERE ayar_id=0");
	$update=$ayarkaydet->execute(array(
		'smtphost' => $_POST['ayar_smtphost'],
		'smtpuser' => $_POST['ayar_smtpuser'],
		'smtppassword' => $_POST['ayar_smtppassword'],
		'smtpport' => $_POST['ayar_smtpport']
		));

	if ($update) {

		Header("Location:../production/mail-ayar.php?durum=ok");

	} else {

		Header("Location:../production/mail-ayar.php?durum=no");
	}

}



if (isset($_POST['iletisimekle'])) {
	
	$ayarkaydet=$db->prepare("INSERT INTO menu SET
		menu_ust=:menu_ust,
		menu_url=:menu_url,
		menu_ad=:menu_ad,
		menu_detay=:menu_detay
		");
	$update=$ayarkaydet->execute(array(
		'menu_ust' => $_POST['menu_ust'],
		'menu_url' => $_POST['menu_url'],
		'menu_ad' => $_POST['menu_ad'],
		'menu_detay' => $_POST['menu_detay']
		));

	if ($update) {

		Header("Location:../../community-guidelines/index.php?page=5&durum=ok");

	} else {

		Header("Location:../../community-guidelines/index.php?page=5&durum=no");
	}

}
if (isset($_POST['reklamekle'])) {
	
	$ayarkaydet=$db->prepare("INSERT INTO siparis SET
		kullanici_name=:kullanici_name,
		siparis_konu=:siparis_konu,
		siparis_mail=:siparis_mail,
		siparis_content=:siparis_content
		");
	$update=$ayarkaydet->execute(array(
		'kullanici_name' => $_POST['kullanici_name'],
		'siparis_konu' => $_POST['siparis_konu'],
		'siparis_mail' => $_POST['siparis_mail'],
		'siparis_content' => $_POST['siparis_content']
		));

	if ($update) {

		Header("Location:../../community-guidelines/index.php?page=8&durum=ok");

	} else {

		Header("Location:../../community-guidelines/index.php?page=8&durum=no");
	}

}

if (isset($_POST['aboneliksubmit'])) {
	$gelenurl=$_POST['gelen_url'];
	if(strlen($_POST['kullanici_id'])){
		$ayarkaydet=$db->prepare("INSERT INTO slider SET

		kullanici_id=:kullanici_id
		");
		$update=$ayarkaydet->execute(array(

			'kullanici_id' => $_POST['kullanici_id']
			));


	}else{

		$ayarkaydet=$db->prepare("INSERT INTO slider SET

		abonelik_mail=:abonelik_mail
		");
		$update=$ayarkaydet->execute(array(

		'abonelik_mail' => $_POST['abonelik_mail']
		));

	}
	
	if ($update) {

		Header("Location:$gelenurl?durum=ok");

	} else {

		Header("Location:$gelenurl?durum=no");
	}

}





if (isset($_POST['note_add'])) {
	
	
	
	
		$ayarkaydet=$db->prepare("INSERT INTO notes SET

			note_content=:note_content,
			note_title=:note_title,
			note_user=:note_user
			");
		$update=$ayarkaydet->execute(array(

			'note_content' => $_POST['note_content'],
			'note_title' => $_POST['note_title'],
			'note_user' => $_POST['note_user']
			));


	
	
	if ($update) {

		Header("Location:../apps_notes.php?durum=ok");

	} else {

		Header("Location:../apps_notes.php?durum=no");
	}

}








if (isset($_POST['mail_gonder'])) {


$kullanicisor=$db->prepare("SELECT * FROM kullanici where kullanici_mail=:mail");
$kullanicisor->execute(array(
  'mail' => $_POST['mail_adres']
  ));

$kullanicicek=$kullanicisor->fetch(PDO::FETCH_ASSOC);

if($kullanicicek['kullanici_id']!=''){
	$ayarkaydet=$db->prepare("INSERT INTO mailing SET

	mail_sendby=:mail_sendby,
	mail_adres=:mail_adres,
	mail_title=:mail_title,
	mail_content=:mail_content,
	mail_come=:mail_come
	");
	$update=$ayarkaydet->execute(array(

	'mail_sendby' => $_POST['mail_sendby'],
	'mail_adres' => $_POST['mail_adres'],
	'mail_title' => $_POST['mail_title'],
	'mail_content' => $_POST['mail_content'],
	'mail_come' => $kullanicicek['kullanici_id']
	));
}else{
	$ayarkaydet=$db->prepare("INSERT INTO mailing SET

	mail_sendby=:mail_sendby,
	mail_adres=:mail_adres,
	mail_title=:mail_title,
	mail_content=:mail_content,
	mail_come=:mail_come
	");
	$update=$ayarkaydet->execute(array(

	'mail_sendby' => $_POST['mail_sendby'],
	'mail_adres' => $_POST['mail_adres'],
	'mail_title' => $_POST['mail_title'],
	'mail_content' => $_POST['mail_content'],
	'mail_come' => 0
	));
}

	
	if ($update) {

		Header("Location:../apps_mailbox.php?durum=ok");

	} else {

		Header("Location:../apps_mailbox.php?durum=no");
	}

}



if (isset($_POST['calenderkayit'])) {



		$ayarkaydet=$db->prepare("INSERT INTO kalender SET
	
		calender_title=:calender_title,
		calender_begin=:calender_begin,
		calender_end=:calender_end,
		calender_content=:calender_content,
		calender_user=:calender_user,
		calender_class=:calender_class
		");
		$update=$ayarkaydet->execute(array(
	
		'calender_title' => $_POST['calender_title'],
		'calender_begin' => $_POST['calender_begin'],
		'calender_end' => $_POST['calender_end'],
		'calender_content' => $_POST['calender_content'],
		'calender_user' => $_POST['calender_user'],
		'calender_class' => $_POST['calender_class']
		));
	
	
		
		if ($update) {
	
			Header("Location:../apps_calendar.php?durum=ok");
	
		} else {
	
			Header("Location:../apps_calendar.php?durum=no");
		}
	
	}



?>