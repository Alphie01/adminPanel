<?php  



?>


<!doctype html>
<html lang="en">
<head>
	<!-- // Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Required meta tags // -->

    <meta name="description" content="Login and Register Form HTML Template - developed by 'ceosdesigns' - sold exclusively on 'themeforest.net'">
	<meta name="author" content="ceosdesigns.sk">

    <title>Creators Station Menejerlik ve Üyelik Sistemi</title>

	<!-- // Favicon -->
	<link href="images/favicon.png" rel="icon">
	<!-- Favicon // -->

	<!-- // Google Web Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600&amp;display=swap" rel="stylesheet">
	<!-- Google Web Fonts // -->
	
	<!-- // Font Awesome 5 Free -->
	<link href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous" rel="stylesheet">
	<!-- Font Awesome 5 Free // -->

    <!-- // Template CSS files -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">
	<!-- Template CSS files  // -->
</head>
<body>
	<!-- // Preloader -->
	<div id="nm-preloader" class="nm-aic nm-vh-md-100">
		<div class="nm-ripple">
			<div></div>
			<div></div>
		</div>
	</div>
	<!-- Preloader // -->

	<main class="d-flex">
		<div class="container main-container">
			<div class="row nm-row">
				<div class="col-lg-7 nm-bgi d-none d-lg-flex">
					<div class="overlay">
						<div class="hero-item">
							<a href="http://creators.com.tr/" aria-label="Nimoy">
								<img src="./logo.png" alt="Logo">
							</a>
						</div>
						<div class="hero-item hero-item-1">
							<h2>Creators İle</h2>
							<h2>Sosyal Medya </h2>
							<h2>Dünyasına Bağlan</h2>
						</div>	
						<ul class="hero-item">
						<li><a href="http://creators.com.tr/turkey/index.html" target="_blank">Creators Türkiye</a></li>
							<li><a href="http://creators.com.tr/usa/index.html" target="_blank">Creators Usa</a></li>
							<li><a href="http://creators.com.tr/kids/index.html" target="_blank">Creators Kids</a></li>
							<li><a href="http://vloggerakademi.com.tr" target="_blank">Vlogger Akademi</a></li>
							<li><a href="http://vloggerworkshop.com.tr" target="_blank">Vlogger Workshop</a></li>
						</ul>
					</div>
				</div>
				<div class="col-lg-5 nm-mb-1 nm-mb-md-1 nm-aic">
					<div class="card">
						<div class="card-content">
							<div class="header">
								<p class="h2">Yeniden hoşgeldiniz!</p>
								<p class="h2">Kullanıcı paneli için giriş yapın.</p>
							</div>
							<form action="./demo3/netting/islem.php" method="POST">
								<div class="form-group">
									<label for="inputEmail">Email</label>
									<div class="input-group nm-gp">
										<span class="nm-gp-pp"><i class="fas fa-envelope-open"></i></span>
										<input id="inputEmail" class="form-control" type="email" tabindex="1" name="kullanici_mail" placeholder="Email adresinizi giriniz" required>
									</div>
								</div>	

								<div class="form-group">
									<label for="inputPassword">Şifre</label>
									<div class="input-group nm-gp">
										<span class="nm-gp-pp"><i class="fas fa-lock"></i></span>
										<input id="inputPassword" class="form-control" type="password" tabindex="2" name="kullanici_password" placeholder="Şifrenizi giriniz" required>
									</div>
								</div>

								<div class="d-flex nm-jcb nm-mb-1 nm-mt-1">



								</div>

								<button type="submit" name="kullanicigiris" class="btn btn-block btn-primary nm-btn">Giriş Yap</button>

								<p class="divider">Sosyal medya ile giriş yapın!</p>

								<div class="row social nm-mb-1">
									<div class="col-md-6 mb-2 mb-md-0">
										<a href="#" class="btn btn-block nm-btn btn-facebook">
											<i class="fab fa-facebook-f nm-mr-1h"></i>
											Facebook
										</a>
									</div>

									<div class="col-md-6">
										<a href="#" class="btn btn-block nm-btn btn-google">
											<i class="fab fa-google nm-mr-1h"></i>
											Google
										</a>
									</div>
								</div>

								<p class="text-center nm-ft-b nm-wh mb-0">
									Daha kullanıcı hesabı oluşturmadın mı?
									<a href="signup.php">Kayıt Ol</a>
								</p>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
	
	<!-- // Vendor JS files -->
	<script src="js/jquery-3.6.0.min.js"></script>
	<script src="js/bootstrap.bundle.min.js"></script>
	<!-- Vendor JS files // -->

	<!-- Template JS files // -->
	<script src="js/script.js"></script>
	<!-- Template JS files // -->

	<!-- ======================================================= -->
	<!-- // Setting to allow preview of different color variants -->
	<!-- ======================================================= -->
	

	<script>
		let tmpLocation = window.location.href;
		let tmpEndLocation = tmpLocation.split("/");
		let targetLocation = tmpEndLocation[tmpEndLocation.length-1];
		targetLocation = targetLocation.replace(".html","").replace("#", "");
		let targetLocationArray = [];

		if(targetLocation.includes("_")){
			targetLocationArray = targetLocation.split("_");
			targetLocationArray[1] = "_" + targetLocationArray[1];
		}
		else {
			targetLocationArray[0] = targetLocation;
			targetLocationArray[1] = "";
		}

		let l = document.links;
		for(let i=0; i<l.length; i++) {
			let tmp = l[i].attributes.href.nodeValue;
			l[i].attributes.href.nodeValue = tmp.replace("recover","recover" + targetLocationArray[1]).replace("login","login" + targetLocationArray[1]).replace("signup","signup" + targetLocationArray[1]);
		}

		document.getElementById("blue").setAttribute('href',"./" + targetLocationArray[0] + ".html");
		document.getElementById("beige").setAttribute('href',"./" + targetLocationArray[0] + "_1.html");
		document.getElementById("burgundy").setAttribute('href',"./" + targetLocationArray[0] + "_2.html");
		document.getElementById("fuchsia").setAttribute('href',"./" + targetLocationArray[0] + "_3.html");
		document.getElementById("turquoise").setAttribute('href',"./" + targetLocationArray[0] + "_4.html");

		document.getElementById("colors").style.transition = 'all 0.2s';
		document.getElementById("settings").addEventListener("click", () =>{
			let leftPosition = document.getElementById("colors").style.left;

			if(leftPosition == '40px'){
				document.getElementById("colors").style.left = '0px';
			}
			else {
				document.getElementById("colors").style.left = '40px';
			}
		});
	</script>
	<!-- ======================================================= -->
	<!-- Setting to allow preview of different color variants // -->
	<!-- ======================================================= -->
</body>
</html>