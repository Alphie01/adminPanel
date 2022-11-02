
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

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Mailbox | Creators Menejerlik ve Mail Üyelik Sistemine Hoşgeldiniz </title>
    <link rel="shortcut icon" type="image/png" href="assets/img/favicon.ico" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
    <!--  BEGIN CUSTOM STYLE FILE  -->
    <link rel="stylesheet" type="text/css" href="plugins/editors/quill/quill.snow.css">
    <link href="assets/css/apps/mailbox.css" rel="stylesheet" type="text/css" />

    <script src="plugins/sweetalerts/promise-polyfill.js"></script>
    <link href="plugins/sweetalerts/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <link href="plugins/sweetalerts/sweetalert.css" rel="stylesheet" type="text/css" />
    <link href="plugins/notification/snackbar/snackbar.min.css" rel="stylesheet" type="text/css" />
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
                <div class="row layout-top-spacing">
                    <div class="col-xl-12 col-lg-12 col-md-12">

                        <div class="row">

                            <div class="col-xl-12  col-md-12">

                                <div class="mail-box-container">
                                    <div class="mail-overlay"></div>

                                    <div class="tab-title">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-12 text-center mail-btn-container">
                                                <a id="btn-compose-mail" class="btn btn-block" href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg></a>
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-12 mail-categories-container">

                                                <div class="mail-sidebar-scroll">

                                                    <ul class="nav nav-pills d-block" id="pills-tab" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link list-actions active" id="mailInbox"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-inbox"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path></svg> <span class="nav-names">Gelen Kutusu</span> <span class="mail-badge badge"></span></a>
                                                        </li>
                                                        
                                                        
                                                        <li class="nav-item">
                                                            <a class="nav-link list-actions" id="sentmail"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg> <span class="nav-names"> Gönderilen Mailler</span></a>
                                                        </li>
                                                        
                                                        <li class="nav-item">
                                                            <a class="nav-link list-actions"  id="trashed"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg> <span class="nav-names">Çöp Kutusu</span></a>
                                                        </li>
                                                    </ul>

                                                    
                                                

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="mailbox-inbox" class="accordion mailbox-inbox">

                                        <div class="search">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu mail-menu d-lg-none"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                                            <input type="text" class="form-control input-search" placeholder="Mail Ara">
                                        </div>

                                        <div class="action-center">
                                            <div class="">
                                                <div class="n-chk">
                                                    <label class="new-control new-checkbox checkbox-primary">
                                                      <input type="checkbox" class="new-control-input" id="inboxAll">
                                                      <span class="new-control-indicator"></span><span>Tümünü Seç</span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="">
                                                <a class="nav-link dropdown-toggle d-icon label-group" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" data-toggle="tooltip" data-placement="top" data-original-title="Label" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg></a>
                                                <div class="dropdown-menu dropdown-menu-right d-icon-menu">
                                                    <a class="label-group-item label-personal dropdown-item position-relative g-dot-primary" href="javascript:void(0);"> Personal</a>
                                                    <a class="label-group-item label-work dropdown-item position-relative g-dot-warning" href="javascript:void(0);"> Work</a>
                                                    <a class="label-group-item label-social dropdown-item position-relative g-dot-success" href="javascript:void(0);"> Social</a>
                                                    <a class="label-group-item label-private dropdown-item position-relative g-dot-danger" href="javascript:void(0);"> Private</a>
                                                </div>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" data-toggle="tooltip" data-placement="top" data-original-title="Important" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star action-important"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-toggle="tooltip" data-placement="top" data-original-title="Spam" class="feather feather-alert-circle action-spam"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12" y2="16"></line></svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" data-toggle="tooltip" data-placement="top" data-original-title="Revive Mail" stroke-linejoin="round" class="feather feather-activity revive-mail"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-toggle="tooltip" data-placement="top" data-original-title="Delete Permanently" class="feather feather-trash permanent-delete"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                <div class="dropdown d-inline-block more-actions">
                                                    <a class="nav-link dropdown-toggle" id="more-actions-btns-dropdown" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="more-actions-btns-dropdown">
                                                        <a class="dropdown-item action-mark_as_read" href="javascript:void(0);">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book-open"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg> Mark as Read
                                                        </a>
                                                        <a class="dropdown-item action-mark_as_unRead" href="javascript:void(0);">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg> Mark as Unread
                                                        </a>
                                                        <a class="dropdown-item action-delete" href="javascript:void(0);">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-toggle="tooltip" data-placement="top" data-original-title="Delete" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Trash
                                                        </a>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                






<?php 
    $mailinboxsor=$db->prepare("SELECT * FROM mailing where mail_come=:mail_come and mail_checked=1 order by mail_id ");
    $mailinboxsor->execute(array(
      'mail_come' => $kullanicicek['kullanici_id']
      ));

      $mailsendbysor=$db->prepare("SELECT * FROM mailing where mail_sendby=:mail_sendby order by mail_id");
      $mailsendbysor->execute(array(
        'mail_sendby' => $kullanicicek['kullanici_id']
        ));

?>



                                        <div class="message-box">
                                            
                                            <div class="message-box-scroll" id="ct">

<?php 
$countsa=0;
while($mailsendbycek=$mailsendbysor->fetch(PDO::FETCH_ASSOC)){



?>

                                                <div class="mail-item sentmail">
                                                    <div class="animated animatedFadeInUp fadeInUp" id="mailHeadingTwo">
                                                        <div class="mb-0">
                                                            <div class="mail-item-heading work collapsed"  data-toggle="collapse" role="navigation" data-target="#mailCollapseTwo<?php echo $countsa ?>" aria-expanded="false">
                                                                <div class="mail-item-inner">

                                                                    <div class="d-flex">
                                                                        <div class="n-chk text-center">
                                                                            <label class="new-control new-checkbox checkbox-primary">
                                                                              <input type="checkbox" class="new-control-input inbox-chkbox">
                                                                              <span class="new-control-indicator"></span>
                                                                            </label>
                                                                        </div>
                                                                            <?php 
                                                                                $mailsedbykullanicisor=$db->prepare("SELECT * FROM kullanici where kullanici_id=:kullanici_id");
                                                                                $mailsedbykullanicisor->execute(array(
                                                                                  'kullanici_id' => $mailsendbycek['mail_come']
                                                                                  ));
                                                                                  $mailsedbykullanicicek=$mailsedbykullanicisor->fetch(PDO::FETCH_ASSOC);
                                                                            ?>

                                                                        <div class="f-body">
                                                                            <div class="meta-mail-time">
                                                                                <p class="user-email" data-mailTo="<?php $mailsendbycek['mail_adres'] ?>"><?php echo $mailsedbykullanicicek['kullanici_adsoyad'] ?></p>
                                                                            </div>
                                                                            <div class="meta-title-tag">
                                                                                <p class="mail-content-excerpt" data-mailDescription='{"ops":[{"insert":"<?php echo $mailsendbycek['mail_content'] ?>\n"}]}'><span class="mail-title" data-mailTitle="<?php echo $mailsendbycek['mail_title'] ?>"><?php echo $mailsendbycek['mail_title'] ?> </span><?php echo $mailsendbycek['mail_content'] ?></p>
                                                                                <div class="tags">
                                                                                    <span class="g-dot-primary"></span>
                                                                                    <span class="g-dot-warning"></span>
                                                                                    <span class="g-dot-success"></span>
                                                                                    <span class="g-dot-danger"></span>
                                                                                </div>
                                                                                <p class="meta-time align-self-center"><?php echo $mailsendbycek['mail_timestamp'] ?></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

<?php 
$countsa++;

}
$count=0;
while($mailinboxcek=$mailinboxsor->fetch(PDO::FETCH_ASSOC)){
?>

                                                <div class="mail-item mailInbox">
                                                    <div class="animated animatedFadeInUp fadeInUp" id="mailHeadingFive">
                                                        <div class="mb-0">
                                                            <div class="mail-item-heading collapsed"  data-toggle="collapse" role="navigation" data-target="#mailCollapseFive<?php echo $count ?>" aria-expanded="false">
                                                                <div class="mail-item-inner">

                                                                    <div class="d-flex">
                                                                        <div class="n-chk text-center">
                                                                            <label class="new-control new-checkbox checkbox-primary">
                                                                              <input type="checkbox" class="new-control-input inbox-chkbox">
                                                                              <span class="new-control-indicator"></span>
                                                                            </label>
                                                                        </div>
                                                                        <div class="f-head">
                                                                            <img src="assets/img/profile-19.jpeg" class="user-profile" alt="avatar">
                                                                        </div>
                                                                        <div class="f-body">
                                                                            <div class="meta-mail-time">
                                                                                <p class="user-email" data-mailTo="<?php echo $mailinboxcek['mail_adres'] ?>">
                                                                            
                                                                            <?php 
                                                                                $mailinboxkullanicisor=$db->prepare("SELECT * FROM kullanici where kullanici_id=:kullanici_id");
                                                                                $mailinboxkullanicisor->execute(array(
                                                                                  'kullanici_id' => $mailinboxcek['mail_sendby']
                                                                                  ));
                                                                                  $mailinboxkullanicicek=$mailinboxkullanicisor->fetch(PDO::FETCH_ASSOC);
                                                                            ?>
                                                                            
                                                                            </p>
                                                                            </div>
                                                                            <div class="meta-title-tag">
                                                                                <p class="mail-content-excerpt" data-mailDescription='{"ops":[{"insert":""<?php echo $mailinboxcek['mail_content'] ?>"\n"}]}'><span class="mail-title" data-mailTitle="<?php echo$mailinboxcek['mail_title'] ?>"><?php echo$mailinboxcek['mail_title'] ?> -</span> "<?php echo $mailinboxcek['mail_content'] ?>"</p>
                                                                                <div class="tags">
                                                                                    <span class="g-dot-primary"></span>
                                                                                    <span class="g-dot-warning"></span>
                                                                                    <span class="g-dot-success"></span>
                                                                                    <span class="g-dot-danger"></span>
                                                                                </div>
                                                                                <p class="meta-time align-self-center"><?php echo $mailinboxcek['mail_timestamp'] ?></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            
<?php 

$count++;
}
?>                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            </div>
                                        </div>

                                        <div class="content-box">
                                            <div class="d-flex msg-close">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left close-message"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                                                <h2 class="mail-title" data-selectedMailTitle=""></h2>
                                            </div>


<?php 
 $mailinbox2sor=$db->prepare("SELECT * FROM mailing where mail_sendby=:mail_sendby order by mail_id");
 $mailinbox2sor->execute(array(
   'mail_sendby' => $kullanicicek['kullanici_id']
   ));

$bot1=0;
   while($mailinbox2cek=$mailinbox2sor->fetch(PDO::FETCH_ASSOC)){
     $mailinboxkullanici2sor=$db->prepare("SELECT * FROM kullanici where kullanici_id=:kullanici_id");
     $mailinboxkullanici2sor->execute(array(
       'kullanici_id' => $mailinbox2cek['mail_come']
       ));
       $mailinboxkullanici2cek=$mailinboxkullanici2sor->fetch(PDO::FETCH_ASSOC);


?>

                                            <div id="mailCollapseTwo<?php echo $bot1; ?>" class="collapse" aria-labelledby="mailHeadingTwo" data-parent="#mailbox-inbox">
                                                <div class="mail-content-container sentmail" data-mailfrom="info@mail.com" data-mailto="alan@mail.com" data-mailcc="">

                                                    <div class="d-flex justify-content-between mb-3">
                                                        <div class="d-flex user-info">
                                                            <div class="f-body">
                                                                <div class="meta-mail-time">
                                                                    <div class="">
                                                                        <p class="user-email" data-mailto="alan@mail.com"><span>Kime:</span> <?php echo $mailinboxkullanici2cek['kullanici_adsoyad'];
                                                                        
                                                                        $dates=explode(" ", $mailinbox2cek['mail_timestamp'])
                                                                        
                                                                        
                                                                        ?></p>
                                                                    </div>
                                                                    <p class="mail-content-meta-date current-recent-mail"><?php echo $dates[0] ?> -</p>
                                                                    <p class="meta-time align-self-center"><?php  echo $dates[1] ?></p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="action-btns">
                                                            <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" data-original-title="Reply">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-corner-up-left reply"><polyline points="9 14 4 9 9 4"></polyline><path d="M20 20v-7a4 4 0 0 0-4-4H4"></path></svg>
                                                            </a>
                                                            <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" data-original-title="Forward">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-corner-up-right forward"><polyline points="15 14 20 9 15 4"></polyline><path d="M4 20v-7a4 4 0 0 1 4-4h12"></path></svg>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <p class="mail-content" data-mailTitle="<?php echo $mailinbox2cek['mail_title'] ?>" data-maildescription='{"ops":[{"insert":"<?php echo $mailinbox2cek['mail_content'] ?>\n"}]}'> <?php echo $mailinbox2cek['mail_content'] ?></p>

                                                    <div class="attachments">
                                                        <h6 class="attachments-section-title">Attachments</h6>
                                                        <div class="attachment file-pdf">
                                                            <div class="media">
                                                                
                                                            </div>
                                                        </div>

                                                        <div class="attachment file-folder">
                                                            <div class="media">
                                                                
                                                            </div>
                                                        </div>

                                                        <div class="attachment file-img">
                                                            <div class="media">
                                                                
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>



<?php 

$bot1++;
} ?>



<?php 
    $mailinbox1sor=$db->prepare("SELECT * FROM mailing where mail_come=:mail_come order by mail_id LIMIT 10");
    $mailinbox1sor->execute(array(
      'mail_come' => $kullanicicek['kullanici_id']
      ));

$bot=0;
      while($mailinbox1cek=$mailinbox1sor->fetch(PDO::FETCH_ASSOC)){
        $mailinboxkullanici1sor=$db->prepare("SELECT * FROM kullanici where kullanici_id=:kullanici_id");
        $mailinboxkullanici1sor->execute(array(
          'kullanici_id' => $mailinbox1cek['mail_sendby']
          ));
          $mailinboxkullanici1cek=$mailinboxkullanici1sor->fetch(PDO::FETCH_ASSOC);
?>
                                            <div id="mailCollapseFive<?php echo $bot?>" class="collapse" aria-labelledby="mailHeadingFive" data-parent="#mailbox-inbox">
                                                <div class="mail-content-container mailInbox" data-mailfrom="" data-mailto="" data-mailcc="">

                                                    <div class="d-flex justify-content-between mb-5">
                                                        <div class="d-flex user-info">
                                                            <div class="f-head">
                                                                <img src="assets/img/profile-19.jpeg" class="user-profile" alt="avatar">
                                                            </div>
                                                            <div class="f-body">
                                                                <div class="meta-title-tag">
                                                                    <h4 class="mail-usr-name" data-mailtitle="<?php echo $mailinbox1cek['mail_title'] ?>"><?php echo $mailinboxkullanici1cek['kullanici_adsoyad'] ?></h4>
                                                                </div>
                                                                <div class="meta-mail-time">
                                                                    <p class="user-email" data-mailto="<?php echo $mailinbox1cek['mail_adres'] ?>"><?php echo $mailinbox1cek['mail_adres'] ?></p>
                                                                    
                                                                    <?php $date=explode(" ",$mailinbox1cek['mail_timestamp']) ?>
                                                                    
                                                                    <p class="mail-content-meta-date current-recent-mail"><?php echo $date[0] ?> </p>
                                                                    <p class="meta-time align-self-center"><?php echo $date[1] ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="action-btns">
                                                            <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" data-original-title="Reply">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-corner-up-left reply"><polyline points="9 14 4 9 9 4"></polyline><path d="M20 20v-7a4 4 0 0 0-4-4H4"></path></svg>
                                                            </a>
                                                            <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" data-original-title="Forward">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-corner-up-right forward"><polyline points="15 14 20 9 15 4"></polyline><path d="M4 20v-7a4 4 0 0 1 4-4h12"></path></svg>
                                                            </a>
                                                        </div>
                                                    </div>

                                                    <p class="mail-content" data-mailTitle="<?php echo $mailinbox1cek['mail_title'] ?>" data-maildescription='{"ops":[{"insert":"<?php echo $mailinbox1cek['mail_content'] ?>\n"}]}'><?php echo $mailinbox1cek['mail_content'] ?> </p>

                                                </div>
                                            </div>


<?php   

$bot++;
} ?>                                            


                                        </div>

                                    </div>
                                    
                                </div>

                                <!-- Modal -->




                                <div class="modal fade" id="composeMailModal" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="modal"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                <div class="compose-box">
                                                    <div class="compose-content">
                                                        <form action="./netting/islem.php"  enctype="multipart/form-data" method="POST" class="section general-info">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="d-flex mb-4 mail-form">
                                                                        <p>Kimden: <?php echo $kullanicicek['kullanici_adsoyad'] ?></p>
                                                                        <input type="hidden" name="mail_sendby" value="<?php echo $kullanicicek['kullanici_id'] ?>">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="d-flex mb-4 mail-to">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                                                        <div class="">
                                                                            <input type="email" id="m-to" placeholder="To" name="mail_adres" class="form-control">
                                                                            <span class="validation-text"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="d-flex mb-4 mail-cc">
                                                                        
                                                                        <div>
                                                                            <input type="hidden" id="m-cc" placeholder="Cc" class="form-control">
                                                                            <span class="validation-text"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="d-flex mb-4 mail-subject">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                                                <div class="w-100">
                                                                    <input type="text" id="m-subject" name="mail_title" placeholder="Subject" class="form-control">
                                                                    <span class="validation-text"></span>
                                                                </div>
                                                            </div>

                                                            <div class="d-flex">
                                                                <input type="file" class="form-control-file" id="mail_File_attachment" multiple="multiple">
                                                            </div>

                                                            <div class="d-flex m-4 mail-subject">
                                        
                                                                <div class="w-100">
                                                                    <textarea name="mail_content" id="" style="background-color:#060818; color: white;" cols="50" rows="10"></textarea>
                                                                    <span class="validation-text"></span>
                                                                </div>
                                                            </div>

                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button id="btn-reply-save" class="btn float-left"> Save Reply</button>
                                                <button id="btn-fwd-save" class="btn float-left"> Save Fwd</button>

                                                <button class="btn" data-dismiss="modal"> <i class="flaticon-delete-1"></i> Discard</button>

                                                <button id="btn-reply" class="btn"> Reply</button>  
                                                <button id="btn-fwd" class="btn"> Forward</button>
                                                <button type="submit" class="btn btn-primary" name="mail_gonder">Gönder</button>
                                            </div></form>
                                        </div>
                                    </div>
                                </div>

                            </div>


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
    <script src="assets/js/ie11fix/fn.fix-padStart.js"></script>
    <script src="plugins/editors/quill/quill.js"></script>
    <script src="plugins/sweetalerts/sweetalert2.min.js"></script>
    <script src="plugins/notification/snackbar/snackbar.min.js"></script>
    <script src="assets/js/apps/custom-mailbox.js"></script>
</body>
</html>