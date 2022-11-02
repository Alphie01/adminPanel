
<?php
ob_start();
session_start();
error_reporting(0);

include './netting/baglan.php';
include 'fonksiyon.php';

//Belirli veriyi seçme işlemi
$ayarsor=$db->prepare("SELECT * FROM ayar where ayar_id=:id");
$ayarsor->execute(array(
  'id' => 0
  ));
$ayarcek=$ayarsor->fetch(PDO::FETCH_ASSOC);


$kullanicisor=$db->prepare("SELECT * FROM kullanici where kullanici_mail=:mail");
$kullanicisor->execute(array(
  'mail' => $_SESSION['kullanici_mail']
  ));
$say=$kullanicisor->rowCount();
$kullanicicek=$kullanicisor->fetch(PDO::FETCH_ASSOC);

if ($say==0) {

  Header("Location:../index.php?durum=izinsiz");
  exit;

}





if (!isset($_SESSION['kullanici_mail'])) {
  Header("Location:../index.php");

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Creators Kullanıcı Paneli </title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico"/>
    <link href="assets/css/loader.css" rel="stylesheet" type="text/css" />
    <script src="assets/js/loader.js"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
    <link href="assets/css/dashboard/dash_1.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

</head>
<body>
    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    <div class="header-container fixed-top">
        <header class="header navbar navbar-expand-sm">

            <ul class="navbar-item theme-brand flex-row  text-center">
                <li class="nav-item theme-logo">
                    <a href="index.php">
                        <img src="./img/creatorschannel-cutout.png" class="navbar-logo" alt="logo">
                    </a>
                </li>
                <li class="nav-item theme-text">
                    <a href="index.php" class="nav-link"> Creators </a>
                </li>
            </ul>

            <ul class="navbar-item flex-row ml-md-0 ml-auto">
                <li class="nav-item align-self-center search-animated">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search toggle-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <form class="form-inline search-full form-inline search" role="search">
                        <div class="search-bar">
                            <input type="text" class="form-control search-form-control  ml-lg-auto" placeholder="Ara...">
                        </div>
                    </form>
                </li>
            </ul>

            <ul class="navbar-item flex-row ml-md-auto">

                <li class="nav-item dropdown language-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="language-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="./img/yuvarlak-türk-bayrağı-png-6-Transparent-Images.png" class="flag-width" alt="flag">
                    </a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="language-dropdown">
                        <a class="dropdown-item d-flex" href="javascript:void(0);"><img src="./img/yuvarlak-türk-bayrağı-png-6-Transparent-Images.png" class="flag-width" alt="flag"> <span class="align-self-center">&nbsp;Türkçe</span></a>

                        <a class="dropdown-item d-flex" href="javascript:void(0);"><img src="assets/img/ca.png" class="flag-width" alt="flag"> <span class="align-self-center">&nbsp;English</span></a>
                    </div>
                </li>

                <li class="nav-item dropdown message-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="messageDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    </a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="messageDropdown">
                        <div class="">
                            
                            <a class="dropdown-item">
                                <div class="">
<?php

$ayarsor=$db->prepare("SELECT * FROM mailing where mail_come=:mail_come and mail_checked=1");
$ayarsor->execute(array(
  'mail_come' => $kullanicicek['kullanici_id']
  ));
while($ayarcek=$ayarsor->fetch(PDO::FETCH_ASSOC)){
    $mailfoto=$db->prepare("SELECT * FROM kullanici where kullanici_id=:kullanici_id");
    $mailfoto->execute(array(
    'kullanici_id' => $ayarcek['mail_come']
    ));
    $mailfotocek=$mailfoto->fetch(PDO::FETCH_ASSOC);
?>
                                    <div class="media m-2">
                                        <div class="user-img">
                                            <div class="avatar avatar-xl">
                                                <span class="avatar-title rounded-circle"><?php echo $mailfotocek['kullanici_ad'] ?></span>
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <div class="">
                                                <h5 class="usr-name"><?php echo $ayarcek['mail_title'] ?></h5>
                                                
                                            </div>
                                        </div>
                                    </div>
<?php
}
?>
                                </div>
                            </a>




                        </div>
                    </div>
                </li>

                <li class="nav-item dropdown notification-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg><span class="badge badge-success"></span>
                    </a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="notificationDropdown">
                        <div class="notification-scroll">


                        <?php  
                                
                                $notsor=$db->prepare("SELECT * FROM notifications where notification_get=:notification_get ");
                                $notsor->execute(array(
                                'notification_get' => $kullanicicek['kullanici_id']
                                ));
                                while($notcek=$notsor->fetch(PDO::FETCH_ASSOC)){
                                                                
                                ?>
                                                            <div class="dropdown-item">
                                <div class="media server-log">
                                <div class="media-body">
                                    <div class="data-info">
                                        <h6 class=""><?php echo $notcek['notification_title'] ?></h6>
                                        
                                    </div>
                                    
                                    <div class="icon-status">
                                        <a href="">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        </a>
                                    </div>
                                </div>
                                </div></div>
                                <?php }  ?>


                            <div class="dropdown-item">
                                <div class="media server-log">
                                    




                                <div class="media-body">
                                    <div class="data-info">
                                        <h6 class="">Creators'e hoşgeldiniz</h6>
                                        
                                    </div>
                                    
                                    <div class="icon-status">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                    </div>
                                </div>

                                </div>
                            </div>

                            
                        </div>
                    </div>
                    
                </li>

                <li class="nav-item dropdown user-profile-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <img src=".<?php if($kullanicicek['kullanici_resim']!=''){echo $kullanicicek['kullanici_resim'];}else{echo "/face.jpg";} ?>" alt="avatar">
                    </a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                        <div class="">
                            <div class="dropdown-item">
                                <a class="" href="user_profile.php"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> Profil</a>
                            </div>


                            <div class="dropdown-item">
                                <a class="" href="../logout.php"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> Çıkış Yap</a>
                            </div>
                        </div>
                    </div>
                    
                </li>

            </ul>
        </header>
    </div>
    <!--  END NAVBAR  -->

    <!--  BEGIN NAVBAR  -->
    <div class="sub-header-container">
        <header class="header navbar navbar-expand-sm">
            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></a>

            <ul class="navbar-nav flex-row">
                <li>
                    <div class="page-header">

                        <nav class="breadcrumb-one" aria-label="breadcrumb">
                            
                        </nav>

                    </div>
                </li>
            </ul>
            <ul class="navbar-nav flex-row ml-auto ">
                <li class="nav-item more-dropdown">
                    <div class="dropdown  custom-dropdown-icon">
                        <a class="dropdown-toggle btn" href="#" role="button" id="customDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span>Yeniliklerden Haberdar Olun.</span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="customDropdown">
                            <a class="dropdown-item" href="#">Yama Notları</a>
                            <a class="dropdown-item" href="#">İnstagram</a>
                            <a class="dropdown-item" href="#">Youtube</a>
                            <a class="dropdown-item" href="#">Twitter</a>
                            <a class="dropdown-item" href="#" style="color:lightgray;">v0.0.1</a>
                            
                        </div>
                    </div>
                </li>
            </ul>
        </header>
    </div>
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        <div class="sidebar-wrapper sidebar-theme">
            
            <nav id="sidebar">
                <div class="shadow-bottom"></div>
                <ul class="list-unstyled menu-categories" id="accordionExample">
                    <li class="menu">
                        <a href="./index.php"  aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                <span>Dashboard</span>
                            </div>

                        </a>

                    </li>

                    <?php 

                        if($kullanicicek['kullanici_yetki']!=0){


                    ?>
                    <li class="menu">
                        <a href="./user_profile.php"  aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                                <i class="fa fa-user"></i>
                                <span>Kullanıcı Profili</span>
                            </div>

                        </a>

                    </li>


                    <li class="menu">
                        <a href="./apps_chat.php"  aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                            <i class="far fa-comments"></i>
                                <span>Chat</span>
                            </div>

                        </a>

                    </li>

                    <li class="menu">
                        <a href="./apps_mailbox.php" aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                            <i class="fa fa-mail-bulk"></i>
                                <span>Mail</span>
                            </div>

                        </a>

                    </li>


                    <li class="menu">
                        <a href="./apps_todoList.php" aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                                <i class="fa fa-th-list"></i>
                                <span>Yapılacaklar listesi</span>
                            </div>

                        </a>

                    </li>


                    <li class="menu">
                        <a href="./apps_notes.php"  aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                            <i class="far fa-sticky-note"></i>
                                <span>Notlar</span>
                            </div>

                        </a>

                    </li>

                    <li class="menu">
                        <a href="./apps_scrumboard.php"  aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                            <i class="fas fa-notes-medical"></i>
                                <span>Scrumboard</span>
                            </div>

                        </a>

                    </li>

                    <?php } ?>
                    <li class="menu">
                        <a href="./apps_contacts.php"  aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                            <i class="far fa-comment"></i>
                                <span>Takımımız</span>
                            </div>

                        </a>

                    </li>
                    <?php if($kullanicicek['kullanici_yetki']==5){ ?>
                    <li class="menu">
                        <a href="./apps_invoice-list.php"  aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                            <i class="fas fa-dollar-sign"></i>
                                <span>Fatura</span>
                            </div>

                        </a>

                    </li>
                    <?php } if($kullanicicek['kullanici_yetki']!=0){?>
                    <li class="menu">
                        <a href="./apps_calendar.php"  aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                            <i class="far fa-calendar-check"></i>
                                <span>Takvim</span>
                            </div>

                        </a>

                    </li>
                                
                    <?php }
                        if($kullanicicek['kullanici_yetki']==0 || $kullanicicek['kullanici_yetki']==5){

                    ?>
                    <li class="menu">
                        <a href="./component_pricing_table.php" aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                            <i class="fas fa-shopping-basket"></i>
                                <span>Fiyatlandırma</span>
                            </div>

                        </a>

                    </li>
                    <?php  }?>

                    <?php 
                        if($kullanicicek['kullanici_yetki']!=0){

                    ?>
                    <li class="menu">
                        <a href="./component_lightbox.php"  aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                            <i class="fas fa-photo-video"></i>
                                <span>Galeri</span>
                            </div>

                        </a>

                    </li>
                    <?php  }?>
                                        
                    <?php 
                        if($kullanicicek['kullanici_yetki']==5){

                    ?>
                    <li class="menu">
                        <a href="./table_dt_html5.php"  aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                            <i class="fas fa-user"></i>
                                <span>Kullanıcılar</span>
                            </div>

                        </a>

                    </li>
                    <?php } ?>


                    <li class="menu">
                        <a href="./pages_helpdesk.php"  aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                            <i class="fas fa-info-circle"></i>
                                <span>Yardım</span>
                            </div>

                        </a>

                    </li>


                    <li class="menu">
                        <a href="./pages_faq.php"  aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                            <i class="fas fa-question"></i>
                                <span>SSS</span>
                            </div>

                        </a>

                    </li>
                    

                    <li class="menu">
                        <a href="pages_contact_us.php" aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                            <i class="far fa-id-card"></i>
                                <span>İletişim</span>
                            </div>

                        </a>

                    </li>
                    </li>

                    <?php 
                        if($kullanicicek['kullanici_yetki']==5){

                    ?>
                    <li class="menu">
                        <a href="./map_jvector.php"  aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                            <i class="fas fa-map-signs"></i>
                                <span>Harita</span>
                            </div>

                        </a>

                    </li>
                    <?php  } ?>
                </ul>
                <!-- <div class="shadow-bottom"></div> -->
                
            </nav>

        </div>
        <!--  END SIDEBAR  -->
        
        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">

                <div class="row layout-top-spacing">

                    <?php  if($kullanicicek['kullanici_yetki']!=0){ ?>
                <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                        <div class="widget widget-table-two">

                            <div class="widget-heading">
                                <h5 class="">Sosyal medya hesaplarınıza gelen takipçi sayısı</h5>
                            </div>

                            <div class="widget-content">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th><div class="th-content">Sosyal Medya</div></th>
                                                <th><div class="th-content">Gelen Takipçi Sayısı</div></th>
                                                <th><div class="th-content th-heading">Yapılan Reklam Sayısı</div></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><div class="td-content customer-name"><i class="fab fa-youtube"></i><span style="margin-left:5px;">Youtube</span></div></td>
                                                <td><div class="td-content product-invoice">#46894</div></td>
                                                <td><div class="td-content pricing"><span class="">5</span></div></td>
                                            </tr>
                                            
                                            <tr>
                                                <td><div class="td-content customer-name"><i class="fab fa-instagram"></i><span style="margin-left:5px;">İnstagram</span></div></td>
                                                <td><div class="td-content product-invoice">#46894</div></td>
                                                <td><div class="td-content pricing"><span class="">7</span></div></td>
                                            </tr>

                                            <tr>
                                                <td><div class="td-content customer-name"><i class="fab fa-twitter"></i><span style="margin-left:5px;">Twitter</span></div></td>
                                                <td><div class="td-content product-invoice">#46894</div></td>
                                                <td><div class="td-content pricing"><span class="">10</span></div></td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    

                    <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                        <div class="widget widget-table-three">

                            <div class="widget-heading">
                                <h5 class="">Son Postlarınıza gelen beğeni sayısı</h5>
                            </div>

                            <div class="widget-content">
                                <div class="table-responsive">
                                    <table class="table table-scroll">
                                        <thead>
                                            <tr>
                                                <th><div class="th-content">Post Linki</div></th>
                                                <th><div class="th-content th-heading">Sosyal Medya</div></th>
                                                <th><div class="th-content th-heading">Gelen Beğeni</div></th>
                                                <th><div class="th-content">Yapılan Reklam</div></th>
                                                <th><div class="th-content">Git</div></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><div class="td-content product-name"><div class="align-self-center"><p class="prd-name">Headphone</p></div></div></td>
                                                <td><div class="td-content"><span class="pricing" style="display:flex; align-items:center; justify-content:center;"><i class="fab fa-instagram"></i></span></div></td>
                                                <td><div class="td-content"><span class="discount-pricing">12000</span></div></td>
                                                <td><div class="td-content">7</div></td>
                                                <td><div class="td-content"><a href="javascript:void(0);" class="text-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg> Direct</a></div></td>
                                            </tr>

                                            <tr>
                                                <td><div class="td-content product-name"><div class="align-self-center"><p class="prd-name">Headphone</p></div></div></td>
                                                <td><div class="td-content"><span class="pricing" style="display:flex; align-items:center; justify-content:center;"><i class="fab fa-instagram"></i></span></div></td>
                                                <td><div class="td-content"><span class="discount-pricing">10452</span></div></td>
                                                <td><div class="td-content">10</div></td>
                                                <td><div class="td-content"><a href="javascript:void(0);" class="text-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg> Direct</a></div></td>
                                            </tr>

                                            <tr>
                                                <td><div class="td-content product-name"><div class="align-self-center"><p class="prd-name">Headphone</p></div></div></td>
                                                <td><div class="td-content"><span class="pricing" style="display:flex; align-items:center; justify-content:center;"><i class="fab fa-youtube"></i></span></div></td>
                                                <td><div class="td-content"><span class="discount-pricing">10447</span></div></td>
                                                <td><div class="td-content">5</div></td>
                                                <td><div class="td-content"><a href="javascript:void(0);" class="text-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg> Direct</a></div></td>
                                            </tr>
                                            

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                    
                    

                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
                        <div class="widget widget-three">
                            <div class="widget-heading">
                                <h5 class="">Toplam Kazanç</h5>

                                <div class="task-action">
                                    <div class="dropdown">
                                        <a class="dropdown-toggle" href="#" role="button" id="pendingTask" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="pendingTask" style="will-change: transform;">
                                            <a class="dropdown-item" href="javascript:void(0);">View Report</a>
                                            <a class="dropdown-item" href="javascript:void(0);">Edit Report</a>
                                            <a class="dropdown-item" href="javascript:void(0);">Mark as Done</a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="widget-content">

                                <div class="order-summary">

                                    <div class="summary-list summary-income">

                                        <div class="summery-info">

                                            <div class="w-icon">
                                                    <i class="fa fa-calculator"></i>
                                            </div>

                                            <div class="w-summary-details">

                                                <div class="w-summary-info">
                                                    <h6>Toplam <span class="summary-count"><?php echo $kullanicicek['youtube_salary']+$kullanicicek['inf_salary']+$kullanicicek['mene_salary'] ?> ₺</span></h6>
                                                    
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="summary-list summary-profit">

                                        <div class="summery-info">

                                            <div class="w-icon">
                                            <i class="fab fa-youtube"></i>
                                            </div>
                                            <div class="w-summary-details">

                                                <div class="w-summary-info">
                                                    <h6>Youtube <span class="summary-count"><?php if($kullanicicek['youtube_salary']!='') echo $kullanicicek['youtube_salary'];else echo "0" ?> ₺</span></h6>
                                                    
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="summary-list summary-profit">

                                        <div class="summery-info">

                                            <div class="w-icon">
                                            <i class="fas fa-comments-dollar"></i>
                                            </div>
                                            <div class="w-summary-details">

                                                <div class="w-summary-info">
                                                    <h6>İnfluencer <span class="summary-count"><?php if($kullanicicek['inf_salary']!='') echo $kullanicicek['inf_salary'];else echo "0" ?> ₺</span></h6>
                                                    
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="summary-list summary-expenses">

                                        <div class="summery-info">

                                            <div class="w-icon">
                                                <i class="fa fa-camera"></i>
                                            </div>
                                            <div class="w-summary-details">

                                                <div class="w-summary-info">
                                                    <h6>Menejerlik <span class="summary-count"><?php if($kullanicicek['mene_salary']!='') echo $kullanicicek['mene_salary'];else echo "0" ?> ₺</span></h6>
                                                    
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>



                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                        <div class="widget-one widget">
                            <div class="widget-content">
                                <div class="w-chart">
                                    <div id="s-col" class=""></div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">

                        <div class="widget widget-activity-four">

                            <div class="widget-heading">
                                <h5 class="">Yakında yapılacak Etkinlikler</h5>
                                <div class="w-icon">
                                    <a class="btn btn-primary" href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
                                </div>
                            </div>

                            <div class="widget-content">

                                <div class="mt-container mx-auto">
                                    <div class="timeline-line">

                                        <?php 

                                        $todosor=$db->prepare("SELECT * FROM kalender where calender_user=:calender_user order by calender_begin DESC ");
                                        $todosor->execute(array(
                                            'calender_user' => $kullanicicek['kullanici_id']
                                        ));

                                        while($todocek=$todosor->fetch(PDO::FETCH_ASSOC)){
                                            $splitedclas=explode("-",$todocek['calender_class']);
                                        ?>


                                        <div class="item-timeline timeline-<?php echo $splitedclas[1] ?>">
                                            <div class="t-dot" data-original-title="" title="">
                                            </div>
                                            <div class="t-text">
                                                <p><?php echo $todocek['calender_title'] ?></p>
                                                <span class="badge"><?php 
                                                $tarih=explode(" ",$todocek['calender_begin']);
                                                
                                                echo $tarih[0] ?></span>
                                                <p class="t-time"><?php 
                                                $tarih=explode(" ",$todocek['calender_begin']);
                                                
                                                echo $tarih[0] ?></p>
                                            </div>
                                        </div>

                                                <?php  
                                                }

                                                ?>

                                    </div>
                                </div>

                                <div class="tm-action-btn">
                                    <a href="./apps_calendar.php" class="btn"><span>Tümünü Gör...</span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
                                </div>
                            </div>
                        </div>
                    </div>


                


                

                    

                    

                </div>
                    <?php }else{ ?>
                        <div class="col-12" style="height:80vh; display:flex; align-items:center; justify-content:center;">
                            <p>
                                Creators Üyelik ve İletişim Sistemine hoş geldiniz. Daha Fazlası İçin <a href="./component_pricing_table.php">Fiyatlandırma</a> bölümünden ulaşabilirsiniz.
                            </p>
                        </div>
                    <?php  } ?>
            </div>
            <div class="footer-wrapper">
                <div class="footer-section f-section-1">
                    <p class="">@2018 Yiğit Holding, Bütün Hakları Saklıdır.</p>
                </div>
                <div class="footer-section f-section-2">
                   
                </div>
            </div>
        </div>
        <!--  END CONTENT AREA  -->

    </div>
    <!-- END MAIN CONTAINER -->

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="assets/js/libs/jquery-3.1.1.min.js"></script>
    <script src="bootstrap/js/popper.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script>
$(document).ready(function(){
    App.init()
})




    </script>
    <script src="assets/js/custom.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script src="plugins/apex/apexcharts.min.js"></script>


<script>
    var sCol = {
    chart: {
        height: 350,
        type: 'bar',
        toolbar: {
          show: false,
        }
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'  
        },
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
    },
    series: [{
        name: 'İnstagram',
        data: [44, 55, 57, 56, 61, 58, 63, 60, 66]
    }, {
        name: 'Youtube',
        data: [76, 85, 101, 98, 87, 105, 91, 250, 94]
    }, {
        name: 'Twitter',
        data: [76, 85, 101, 98, 87, 105, 91, 114, 94]
    }],
    xaxis: {
        categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
    },
    yaxis: {
        title: {
            text: '$ (thousands)'
        }
    },
    fill: {
        opacity: 1

    },
    tooltip: {
        y: {
            formatter: function (val) {
                return " " + val + " Takipçi"
            }
        }
    }
}

var chart = new ApexCharts(
    document.querySelector("#s-col"),
    sCol
);

chart.render();
</script>

    <script src="assets/js/dashboard/dash_1.js"></script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

</body>
</html>