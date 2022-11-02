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



//1.Yöntem

if (!isset($_SESSION['kullanici_mail'])) {
  Header("Location:../index.php");

}

$_GET['kullanici_id']=$kullanici_id;


?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Invoice Add | Creators Menejerlik ve Mail Üyelik Sistemine Hoşgeldiniz </title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="plugins/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
    <!--  BEGIN CUSTOM STYLE FILE  -->
    <link href="assets/css/apps/invoice-add.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="plugins/dropify/dropify.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/forms/theme-checkbox-radio.css">
    <link href="plugins/flatpickr/flatpickr.css" rel="stylesheet" type="text/css">
    <link href="plugins/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
    <!--  END CUSTOM STYLE FILE  -->
    
</head>
<body>
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
                    <?php  }?>
                </ul>
                <!-- <div class="shadow-bottom"></div> -->
                
            </nav>

        </div>
        <!--  END SIDEBAR  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">

                <div class="row invoice layout-top-spacing layout-spacing">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        
                        <div class="doc-container">
<form action="./netting/islem.php"  enctype="multipart/form-data" method="post">
                            <div class="row">
                                <div class="col-xl-12">

                                    <div class="invoice-content">

                                        <div class="invoice-detail-body">

                                            <div class="invoice-detail-title">

                                                <div class="invoice-logo">
                                                    <div class="upload">
                                                        <input type="file" id="input-file-max-fs" name="payment_logo" class="dropify" data-max-file-size="2M" />
                                                    </div>
                                                </div>
                                                


                                            </div>

                                            <div class="invoice-detail-header">

                                                <div class="row justify-content-between">
                                                    <div class="col-xl-5 invoice-address-company">

                                                        <h4>Kimden:-</h4>

                                                        <div class="invoice-address-company-fields">

                                                            <div class="form-group row">
                                                                <label for="company-name" class="col-sm-3 col-form-label col-form-label-sm">İsim</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" name="payment_fromname" class="form-control form-control-sm" id="company-name" placeholder="Şirket yada Kişi İsmi">
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label for="company-email" class="col-sm-3 col-form-label col-form-label-sm">Email</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" name="payment_fromemail" class="form-control form-control-sm" id="company-email" placeholder="Email adresini giriniz">
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label for="company-address" class="col-sm-3 col-form-label col-form-label-sm">Address</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" name="payment_fromadres" class="form-control form-control-sm" id="company-address" placeholder="Adres">
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label for="company-phone" class="col-sm-3 col-form-label col-form-label-sm">Telefon</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" name="payment_fromphone" class="form-control form-control-sm" id="company-phone" placeholder="Telefon numarası">
                                                                </div>
                                                            </div>                                                                
                                                            
                                                        </div>
                                                        
                                                    </div>


                                                    <div class="col-xl-5 invoice-address-client">

                                                        <h4>Kime:-</h4>

                                                        <div class="invoice-address-client-fields">

                                                            <div class="form-group row">
                                                                <label for="client-name" class="col-sm-3 col-form-label col-form-label-sm">İsim</label>
                                                                <div class="col-sm-9">
                                                                    <input name="payment_toname" type="text" class="form-control form-control-sm" id="client-name" placeholder="Şirket yada Kişi İsmi">
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label for="client-email" class="col-sm-3 col-form-label col-form-label-sm">Email</label>
                                                                <div class="col-sm-9">
                                                                    <input name="payment_toemail" type="text" class="form-control form-control-sm" id="client-email" placeholder="Email adresini giriniz">
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label for="client-address" class="col-sm-3 col-form-label col-form-label-sm">Adres</label>
                                                                <div class="col-sm-9">
                                                                    <input name="payment_toadres" type="text" class="form-control form-control-sm" id="client-address" placeholder="Adres">
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label for="client-phone" class="col-sm-3 col-form-label col-form-label-sm">Telefon</label>
                                                                <div class="col-sm-9">
                                                                    <input name="payment_tophone" type="text" class="form-control form-control-sm" id="client-phone" placeholder="Telefon Numarası">
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                        
                                                    </div>
                                                    
                                                    
                                                </div>
                                                
                                            </div>

                                            <div class="invoice-detail-terms">

                                                <div class="row justify-content-between">

                                                    

                                                    <div class="col-md-3">

                                                        <div class="form-group mb-4">
                                                            <label for="date">Fatura Zamanı</label>
                                                            <input name="payment_date" type="text" class="form-control form-control-sm" id="date" placeholder="Add date picker">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group mb-4">
                                                            <label for="due">Ödenme Zamanı</label>
                                                            <input name="payment_duedate" type="text" class="form-control form-control-sm" id="due" placeholder="None">
                                                        </div>
                                                        
                                                    </div>

                                                </div>
                                                
                                            </div>


                                            <div class="invoice-detail-items">

                                                <div class="table-responsive">
                                                    <table class="table table-bordered item-table">
                                                        <thead>
                                                            <tr>
                                                                <th class=""></th>
                                                                <th>Açıklama</th>
                                                                <th class="">Fiyat</th>
                                                                <th class="">Adet</th>
                                                                <th class="text-center">Vergi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="delete-item-row">
                                                                    <ul class="table-controls">
                                                                        <li><a href="javascript:void(0);" class="delete-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg></a></li>
                                                                    </ul>
                                                                </td>
                                                                <td class="description">
                                                                    
                                                                <input type="text" name="payment_item1desc" class="form-control form-control-sm" placeholder="Ödenek başlığı"> 
                                                                
                                                                <textarea class="form-control" name="payment_item1details" placeholder="Detay"></textarea></td>
                                                                <td class="rate">
                                                                    <input type="text" name="payment_item1price"  class="form-control form-control-sm" placeholder="Fiyat">
                                                                </td>
                                                                <td class="text-right qty"><input type="text" name="payment_item1qua" class="form-control form-control-sm" placeholder="Sayı"></td>
                                                                
                                                                <td class="text-center tax">
                                                                    <div class="n-chk">
                                                                        <label class="new-control new-checkbox new-checkbox-text checkbox-primary" style="height: 18px; margin: 0 auto;">
                                                                            <input type="checkbox" class="new-control-input" checked>
                                                                            <span class="new-control-indicator"></span><span class="new-chk-content">Vergi</span>
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                
                                                
                                            </div>


                                            <div class="invoice-detail-total">

                                                <div class="row">

                                                    <div class="col-md-6">
                                                        
                                                        <div class="form-group row invoice-created-by">
                                                            <label for="payment-method-account" class="col-sm-3 col-form-label col-form-label-sm">Hesap Numarası:</label>
                                                            <div class="col-sm-9">
                                                                <input name="payment_account" type="text" class="form-control form-control-sm" id="payment-method-account" placeholder="Hesap Numarası">
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="form-group row invoice-created-by">
                                                            <label for="payment-method-account" class="col-sm-3 col-form-label col-form-label-sm">Ay:</label>
                                                            <div class="col-sm-4">
                                                                <select name="payment_cardmonth" class="form-control country_code  form-control-sm" id="payment-method-country">
                                                                    <option value="">1</option>
                                                                    <option value="">2</option>
                                                                    <option value="">3</option>
                                                                    <option value="">4</option>
                                                                    <option value="">5</option>
                                                                    <option value="">6</option>
                                                                    <option value="">7</option>
                                                                    <option value="">8</option>
                                                                    <option value="">9</option>
                                                                    <option value="">10</option>
                                                                    <option value="">11</option>
                                                                    <option value="">12</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row invoice-created-by">
                                                            <label for="payment-method-account" class="col-sm-3 col-form-label col-form-label-sm">Yıl:</label>
                                                            <div class="col-sm-4">
                                                                <select name="payment_cardyear" class="form-control country_code  form-control-sm" id="payment-method-country">
                                                                   
                                                                    <option value="">21</option>
                                                                    <option value="">22</option>
                                                                    <option value="">23</option>
                                                                    <option value="">24</option>
                                                                    <option value="">25</option>
                                                                    <option value="">26</option>
                                                                    <option value="">27</option>
                                                                    <option value="">28</option>
                                                                    <option value="">29</option>
                                                                    <option value="">30</option>
                                                                    <option value="">31</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row invoice-created-by">
                                                            <label for="payment-method-bank-name" class="col-sm-3 col-form-label col-form-label-sm">İsim:</label>
                                                            <div class="col-sm-9">
                                                                <input name="payment_cardname" type="text" class="form-control form-control-sm" id="payment-method-bank-name" placeholder="Kart Üzerindeki İsim">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row invoice-created-by">
                                                            <label for="payment-method-code" class="col-sm-3 col-form-label col-form-label-sm">CVS:</label>
                                                            <div class="col-sm-9">
                                                                <input name="payment_swift" type="text" class="form-control form-control-sm" id="payment-method-code" placeholder="Arkadaki 3 Haneli Kod">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row invoice-created-by">
                                                            <label for="payment-method-country" class="col-sm-3 col-form-label col-form-label-sm">Ülke:</label>
                                                            <div class="col-sm-9">
                                                                <select name="payment_country" class="form-control country_code  form-control-sm" id="payment-method-country">
                                                                    <option value="">Ülkeyi Seçin</option>
                                                                    <option value="United States">United States</option>
                                                                    <option value="United Kingdom">United Kingdom</option>
                                                                    <option value="Canada">Canada</option>
                                                                    <option value="Australia">Australia</option>
                                                                    <option value="Germany">Germany</option>
                                                                    <option value="Sweden">Sweden</option>
                                                                    <option value="Denmark">Denmark</option>
                                                                    <option value="Norway">Norway</option>
                                                                    <option value="New-Zealand">New Zealand</option>
                                                                    <option value="Afghanistan">Afghanistan</option>
                                                                    <option value="Albania">Albania</option>
                                                                    <option value="Algeria">Algeria</option>
                                                                    <option value="American-Samoa">Andorra</option>
                                                                    <option value="Angola">Angola</option>
                                                                    <option value="Antigua Barbuda">Antigua &amp; Barbuda</option>
                                                                    <option value="Argentina">Argentina</option>
                                                                    <option value="Armenia">Armenia</option>
                                                                    <option value="Aruba">Aruba</option>
                                                                    <option value="Austria">Austria</option>
                                                                    <option value="Azerbaijan">Azerbaijan</option>
                                                                    <option value="Bahamas">Bahamas</option>
                                                                    <option value="Bahrain">Bahrain</option>
                                                                    <option value="Bangladesh">Bangladesh</option>
                                                                    <option value="Barbados">Barbados</option>
                                                                    <option value="Belarus">Belarus</option>
                                                                    <option value="Belgium">Belgium</option>
                                                                    <option value="Belize">Belize</option>
                                                                    <option value="Benin">Benin</option>
                                                                    <option value="Bermuda">Bermuda</option>
                                                                    <option value="Bhutan">Bhutan</option>
                                                                    <option value="Bolivia">Bolivia</option>
                                                                    <option value="Bosnia">Bosnia &amp; Herzegovina</option>
                                                                    <option value="Botswana">Botswana</option>
                                                                    <option value="Brazil">Brazil</option>
                                                                    <option value="British">British Virgin Islands</option>
                                                                    <option value="Brunei">Brunei</option>
                                                                    <option value="Bulgaria">Bulgaria</option>
                                                                    <option value="Burkina">Burkina Faso</option>
                                                                    <option value="Burundi">Burundi</option>
                                                                    <option value="Cambodia">Cambodia</option>
                                                                    <option value="Cameroon">Cameroon</option>
                                                                    <option value="Cape">Cape Verde</option>
                                                                    <option value="Cayman">Cayman Islands</option>
                                                                    <option value="Central-African">Central African Republic</option>
                                                                    <option value="Chad">Chad</option>
                                                                    <option value="Chile">Chile</option>
                                                                    <option value="China">China</option>
                                                                    <option value="Colombia">Colombia</option>
                                                                    <option value="Comoros">Comoros</option>
                                                                    <option value="Costa-Rica">Costa Rica</option>
                                                                    <option value="Croatia">Croatia</option>
                                                                    <option value="Cuba">Cuba</option>
                                                                    <option value="Cyprus">Cyprus</option>
                                                                    <option value="Czechia">Czechia</option>
                                                                    <option value="Côte">Côte d’Ivoire</option>
                                                                    <option value="Djibouti">Djibouti</option>
                                                                    <option value="Dominica">Dominica</option>
                                                                    <option value="Dominican">Dominican Republic</option>
                                                                    <option value="Ecuador">Ecuador</option>
                                                                    <option value="Egypt">Egypt</option>
                                                                    <option value="El-Salvador">El Salvador</option>
                                                                    <option value="Equatorial-Guinea">Equatorial Guinea</option>
                                                                    <option value="Eritrea">Eritrea</option>
                                                                    <option value="Estonia">Estonia</option>
                                                                    <option value="Ethiopia">Ethiopia</option>
                                                                    <option value="Fiji">Fiji</option>
                                                                    <option value="Finland">Finland</option>
                                                                    <option value="France">France</option>
                                                                    <option value="Gabon">Gabon</option>
                                                                    <option value="Georgia">Georgia</option>
                                                                    <option value="Ghana">Ghana</option>
                                                                    <option value="Greece">Greece</option>
                                                                    <option value="Grenada">Grenada</option>
                                                                    <option value="Guatemala">Guatemala</option>
                                                                    <option value="Guernsey">Guernsey</option>
                                                                    <option value="Guinea">Guinea</option>
                                                                    <option value="Guinea-Bissau">Guinea-Bissau</option>
                                                                    <option value="Guyana">Guyana</option>
                                                                    <option value="Haiti">Haiti</option>
                                                                    <option value="Honduras">Honduras</option>
                                                                    <option value="Hong-Kong">Hong Kong SAR China</option>
                                                                    <option value="Hungary">Hungary</option>
                                                                    <option value="Iceland">Iceland</option>
                                                                    <option value="India">India</option>
                                                                    <option value="Indonesia">Indonesia</option>
                                                                    <option value="Iran">Iran</option>
                                                                    <option value="Iraq">Iraq</option>
                                                                    <option value="Ireland">Ireland</option>
                                                                    <option value="Israel">Israel</option>
                                                                    <option value="Italy">Italy</option>
                                                                    <option value="Jamaica">Jamaica</option>
                                                                    <option value="Japan">Japan</option>
                                                                    <option value="Jordan">Jordan</option>
                                                                    <option value="Kazakhstan">Kazakhstan</option>
                                                                    <option value="Kenya">Kenya</option>
                                                                    <option value="Kuwait">Kuwait</option>
                                                                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                                                                    <option value="Laos">Laos</option>
                                                                    <option value="Latvia">Latvia</option>
                                                                    <option value="Lebanon">Lebanon</option>
                                                                    <option value="Lesotho">Lesotho</option>
                                                                    <option value="Liberia">Liberia</option>
                                                                    <option value="Libya">Libya</option>
                                                                    <option value="Liechtenstein">Liechtenstein</option>
                                                                    <option value="Lithuania">Lithuania</option>
                                                                    <option value="Luxembourg">Luxembourg</option>
                                                                    <option value="Macedonia">Macedonia</option>
                                                                    <option value="Madagascar">Madagascar</option>
                                                                    <option value="Malawi">Malawi</option>
                                                                    <option value="Malaysia">Malaysia</option>
                                                                    <option value="Maldives">Maldives</option>
                                                                    <option value="Mali">Mali</option>
                                                                    <option value="Malta">Malta</option>
                                                                    <option value="Mauritania">Mauritania</option>
                                                                    <option value="Mauritius">Mauritius</option>
                                                                    <option value="Mexico">Mexico</option>
                                                                    <option value="Moldova">Moldova</option>
                                                                    <option value="Monaco">Monaco</option>
                                                                    <option value="Mongolia">Mongolia</option>
                                                                    <option value="Montenegro">Montenegro</option>
                                                                    <option value="Morocco">Morocco</option>
                                                                    <option value="Mozambique">Mozambique</option>
                                                                    <option value="Myanmar">Myanmar (Burma)</option>
                                                                    <option value="Namibia">Namibia</option>
                                                                    <option value="Nepal">Nepal</option>
                                                                    <option value="Netherlands">Netherlands</option>
                                                                    <option value="Nicaragua">Nicaragua</option>
                                                                    <option value="Niger">Niger</option>
                                                                    <option value="Nigeria">Nigeria</option>
                                                                    <option value="North-Korea">North Korea</option>
                                                                    <option value="Oman">Oman</option>
                                                                    <option value="Pakistan">Pakistan</option>
                                                                    <option value="Palau">Palau</option>
                                                                    <option value="Palestinian">Palestinian Territories</option>
                                                                    <option value="Panama">Panama</option>
                                                                    <option value="Papua">Papua New Guinea</option>
                                                                    <option value="Paraguay">Paraguay</option>
                                                                    <option value="Peru">Peru</option>
                                                                    <option value="Philippines">Philippines</option>
                                                                    <option value="Poland">Poland</option>
                                                                    <option value="Portugal">Portugal</option>
                                                                    <option value="Puerto">Puerto Rico</option>
                                                                    <option value="Qatar">Qatar</option>
                                                                    <option value="Romania">Romania</option>
                                                                    <option value="Russia">Russia</option>
                                                                    <option value="Rwanda">Rwanda</option>
                                                                    <option value="Réunion">Réunion</option>
                                                                    <option value="Samoa">Samoa</option>
                                                                    <option value="San-Marino">San Marino</option>
                                                                    <option value="Saudi-Arabia">Saudi Arabia</option>
                                                                    <option value="Senegal">Senegal</option>
                                                                    <option value="Serbia">Serbia</option>
                                                                    <option value="Seychelles">Seychelles</option>
                                                                    <option value="Sierra-Leone">Sierra Leone</option>
                                                                    <option value="Singapore">Singapore</option>
                                                                    <option value="Slovakia">Slovakia</option>
                                                                    <option value="Slovenia">Slovenia</option>
                                                                    <option value="Solomon-Islands">Solomon Islands</option>
                                                                    <option value="Somalia">Somalia</option>
                                                                    <option value="South-Africa">South Africa</option>
                                                                    <option value="South-Korea">South Korea</option>
                                                                    <option value="Spain">Spain</option>
                                                                    <option value="Sri-Lanka">Sri Lanka</option>
                                                                    <option value="Sudan">Sudan</option>
                                                                    <option value="Suriname">Suriname</option>
                                                                    <option value="Swaziland">Swaziland</option>
                                                                    <option value="Switzerland">Switzerland</option>
                                                                    <option value="Syria">Syria</option>
                                                                    <option value="Sao-Tome-and-Principe">São Tomé &amp; Príncipe</option>
                                                                    <option value="Tajikistan">Tajikistan</option>
                                                                    <option value="Tanzania">Tanzania</option>
                                                                    <option value="Thailand">Thailand</option>
                                                                    <option value="Timor-Leste">Timor-Leste</option>
                                                                    <option value="Togo">Togo</option>
                                                                    <option value="Tonga">Tonga</option>
                                                                    <option value="Trinidad-and-Tobago">Trinidad &amp; Tobago</option>
                                                                    <option value="Tunisia">Tunisia</option>
                                                                    <option value="Turkey" selected>Turkey</option>
                                                                    <option value="Turkmenistan">Turkmenistan</option>
                                                                    <option value="Uganda">Uganda</option>
                                                                    <option value="Ukraine">Ukraine</option>
                                                                    <option value="UAE">United Arab Emirates</option>
                                                                    <option value="Uruguay">Uruguay</option>
                                                                    <option value="Uzbekistan">Uzbekistan</option>
                                                                    <option value="Vanuatu">Vanuatu</option>
                                                                    <option value="Venezuela">Venezuela</option>
                                                                    <option value="Vietnam">Vietnam</option>
                                                                    <option value="Yemen">Yemen</option>
                                                                    <option value="Zambia">Zambia</option>
                                                                    <option value="Zimbabwe">Zimbabwe</option>
                                                                    </select>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>


                                                </div>
                                                
                                            </div>

                                            <div class="invoice-detail-note">

                                                <div class="row">
                                                
                                                    <div class="col-md-12 align-self-center">

                                                        <div class="form-group row invoice-note">
                                                            <label for="invoice-detail-notes" class="col-sm-12 col-form-label col-form-label-sm">Not:</label>
                                                            <div class="col-sm-12">
                                                                <textarea name="payment_notes" class="form-control" id="invoice-detail-notes" placeholder='Yaptığınız işlem hakkında açıklayıcı cümleye yazınız' style="height: 88px;"></textarea>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>

                                                </div>

                                            </div>
                                            
                                            
                                        </div>
                                        
                                    </div>
                                    
                                </div>


                            </div>
                            <button type="submit" name="fatura_kaydet" class="btn-primary d-flex align-items-center">Kaydet</button>
                            
</form>                            
                        </div>

                    </div>
                </div>
            </div>
            <div class="footer-wrapper">
                <div class="footer-section f-section-1">
                    <p class="">© 2021 <a target="_blank" href="http://yigityatirim.com/">Yiğit Holding</a>, Bütün Hakları Saklıdır.</p>
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
        $(document).ready(function() {
            App.init();
        });
    </script>
    <script src="assets/js/custom.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <script src="plugins/dropify/dropify.min.js"></script>
    <script src="plugins/flatpickr/flatpickr.js"></script>
    <script src="assets/js/apps/invoice-add.js"></script>
</body>
</html>