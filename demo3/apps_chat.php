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
    <title>Chat Application | Creators Menejerlik ve Mail Üyelik Sistemine Hoşgeldiniz </title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="assets/css/apps/mailing-chat.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL STYLES -->
    
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
                    <?php } ?>
                </ul>
                <!-- <div class="shadow-bottom"></div> -->
                
            </nav>

        </div>
        <!--  END SIDEBAR  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">

                <div class="chat-section layout-top-spacing">
                    <div class="row">

                        <div class="col-xl-12 col-lg-12 col-md-12">

                            <div class="chat-system">
                                <div class="hamburger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu mail-menu d-lg-none"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></div>
                                <div class="user-list-box">
                                    <div class="search">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                        <input type="text" class="form-control" placeholder="Search" />
                                    </div>
                                    <div class="people">

                                        <div class="person" data-chat="person6">
                                            <div class="user-info">
                                                <div class="f-head">
                                                    <img src="assets/img/profile-4.jpeg" alt="avatar">
                                                </div>
                                                <div class="f-body">
                                                    <div class="meta-info">
                                                        <span class="user-name" data-name="Nia Hillyer">Ebru Yıldız</span>
                                                        <span class="user-meta-time">2:09 PM</span>
                                                    </div>
                                                    <span class="preview">How do you do?</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="person" data-chat="person1">
                                            <div class="user-info">
                                                <div class="f-head">
                                                    <img src="assets/img/profile-3.jpeg" alt="avatar">
                                                </div>
                                                <div class="f-body">
                                                    <div class="meta-info">
                                                        <span class="user-name" data-name="Sean Freeman">Ferit S.</span>
                                                        <span class="user-meta-time">2:09 PM</span>
                                                    </div>
                                                    <span class="preview">I was wondering...</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="person" data-chat="person2">
                                            <div class="user-info">
                                                <div class="f-head">
                                                    <img src="assets/img/profile-11.jpeg" alt="avatar">
                                                </div>
                                                <div class="f-body">
                                                    <div class="meta-info">
                                                        <span class="user-name" data-name="Alma Clarke">Elif Y.</span>
                                                        <span class="user-meta-time">1:44 PM</span>
                                                    </div>
                                                    <span class="preview">I've forgotten how it felt before</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="person" data-chat="person3">
                                            <div class="user-info">
                                                <div class="f-head">
                                                    <img src="assets/img/profile-23.jpeg" alt="avatar">
                                                </div>
                                                <div class="f-body">
                                                    <div class="meta-info">
                                                        <span class="user-name" data-name="Alan Green">Selin D.</span>
                                                        <span class="user-meta-time">2:09 PM</span>
                                                    </div>
                                                    <span class="preview">But we’re probably gonna need a new carpet.</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="person" data-chat="person4">
                                            <div class="user-info">
                                                <div class="f-head">
                                                    <img src="assets/img/profile-7.jpeg" alt="avatar">
                                                </div>
                                                <div class="f-body">
                                                    <div class="meta-info">
                                                        <span class="user-name" data-name="Shaun Park">Alp</span>
                                                        <span class="user-meta-time">2:09 PM</span>
                                                    </div>
                                                    <span class="preview">It’s not that bad...</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="person" data-chat="person5">
                                            <div class="user-info">
                                                <div class="f-head">
                                                    <img src="assets/img/profile-15.jpeg" alt="avatar">
                                                </div>
                                                <div class="f-body">
                                                    <div class="meta-info">
                                                        <span class="user-name" data-name="Roxanne">Burak G.</span>
                                                        <span class="user-meta-time">2:09 PM</span>
                                                    </div>
                                                    <span class="preview">Wasup for the third time like is you bling bitch</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="person" data-chat="person7">
                                            <div class="user-info">
                                                <div class="f-head">
                                                    <img src="assets/img/profile-32.jpeg" alt="avatar">
                                                </div>
                                                <div class="f-body">
                                                    <div class="meta-info">
                                                        <span class="user-name" data-name="Ernest Reeves">Şeyma S.</span>
                                                        <span class="user-meta-time">2:09 PM</span>
                                                    </div>
                                                    <span class="preview">Wasup for the third time like is you bling bitch</span>
                                                </div>
                                            </div>
                                        </div>



                                       
                                        
                                    </div>
                                </div>
                                <div class="chat-box">

                                    <div class="chat-not-selected">
                                        <p> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg> Click User To Chat</p>
                                    </div>

                                    <div class="overlay-phone-call">
                                        <div class="">
                                            <div class="calling-user-info">
                                                <div class="">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left-circle go-back-chat"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line></svg>
                                                    <span class="user-name"></span>
                                                    <span class="call-status">Calling...</span>
                                                </div>
                                            </div>

                                            <div class="calling-user-img">
                                                <div class="">
                                                    <img src="assets/img/90x90.jpg" alt="dynamic-image">
                                                </div>

                                                <div class="timer"><label class="minutes">00</label> : <label class="seconds">00</label></div>
                                            </div>

                                            <div class="calling-options">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video switch-to-video-call"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mic switch-to-microphone"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="23"></line><line x1="8" y1="23" x2="16" y2="23"></line></svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus add-more-caller"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone-off cancel-call"><path d="M10.68 13.31a16 16 0 0 0 3.41 2.6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7 2 2 0 0 1 1.72 2v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.42 19.42 0 0 1-3.33-2.67m-2.67-3.34a19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91"></path><line x1="23" y1="1" x2="1" y2="23"></line></svg>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="overlay-video-call">
                                        <img src="assets/img/video-chat.jpg" class="video-caller" alt="video-chat">
                                        <div class="">
                                            <div class="calling-user-info">
                                                <div class="d-flex">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left-circle go-back-chat"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line></svg>
                                                    <div class="">
                                                        <span class="user-name"></span>
                                                        <div class="timer"><label class="minutes">00</label> : <label class="seconds">00</label></div>
                                                    </div>
                                                    <span class="call-status">Calling...</span>
                                                </div>
                                            </div>

                                            <div class="calling-user-img">
                                                <div class="">
                                                    <img src="assets/img/90x90.jpg" alt="dynamic-image">
                                                </div>

                                            </div>
                                            <div class="calling-options">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone switch-to-phone-call"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mic switch-to-microphone"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="23"></line><line x1="8" y1="23" x2="16" y2="23"></line></svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus add-more-caller"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video-off cancel-call"><path d="M16 16v1a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h2m5.66 0H14a2 2 0 0 1 2 2v3.34l1 1L23 7v10"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="chat-box-inner">
                                        <div class="chat-meta-user">
                                            <div class="current-chat-user-name"><span><img src="assets/img/90x90.jpg" alt="dynamic-image"><span class="name"></span></span></div>

                                            <div class="chat-action-btn align-self-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone  phone-call-screen"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video video-call-screen"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
                                                <div class="dropdown d-inline-block">
                                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                                    </a>

                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink-2">
                                                        <a class="dropdown-item" href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg> Settings</a>
                                                        <a class="dropdown-item" href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg> Mail</a>
                                                        <a class="dropdown-item" href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-copy"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg> Copy</a>
                                                        <a class="dropdown-item" href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg> Delete</a>
                                                        <a class="dropdown-item" href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share-2"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg> Share</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="chat-conversation-box">
                                            <div id="chat-conversation-box-scroll" class="chat-conversation-box-scroll">
                                                <div class="chat" data-chat="person1">
                                                    <div class="conversation-start">
                                                        <span>Today, 6:48 AM</span>
                                                    </div>
                                                    <div class="bubble you">
                                                        Hello,
                                                    </div>
                                                    <div class="bubble you">
                                                        It's me.
                                                    </div>
                                                    <div class="bubble you">
                                                        I have a question regarding project.
                                                    </div>
                                                </div>
                                                <div class="chat" data-chat="person2">
                                                    <div class="conversation-start">
                                                        <span>Today, 5:38 PM</span>
                                                    </div>
                                                    <div class="bubble you">
                                                        Hello!
                                                    </div>
                                                    <div class="bubble me">
                                                        Hey!
                                                    </div>
                                                    <div class="bubble me">
                                                        How was your day so far.
                                                    </div>
                                                    <div class="bubble you">
                                                        It was a bit dramatic.
                                                    </div>
                                                </div>
                                                <div class="chat" data-chat="person3">
                                                    <div class="conversation-start">
                                                        <span>Today, 3:38 AM</span>
                                                    </div>
                                                    <div class="bubble me">
                                                        Hey Buddy.
                                                    </div>
                                                    <div class="bubble me">
                                                        What's up
                                                    </div>
                                                    <div class="bubble you">
                                                        I am sick
                                                    </div>
                                                    <div class="bubble you">
                                                        Not comming to office today.
                                                    </div>
                                                </div>
                                                <div class="chat" data-chat="person4">
                                                    <div class="conversation-start">
                                                        <span>Yesterday, 4:20 PM</span>
                                                    </div>
                                                    <div class="bubble you">
                                                        Hi, collect your check
                                                    </div>
                                                    <div class="bubble me">
                                                        Ok, I will be there in 10 mins
                                                    </div>
                                                </div>
                                                <div class="chat" data-chat="person5">
                                                    <div class="conversation-start">
                                                        <span>Today, 6:28 AM</span>
                                                    </div>
                                                    <div class="bubble you">
                                                        Hi
                                                    </div>
                                                    <div class="bubble you">
                                                        Uploaded files to server.
                                                    </div>
                                                </div>
                                                <div class="chat" data-chat="person6">
                                                    <div class="conversation-start">
                                                        <span>Monday, 1:27 PM</span>
                                                    </div>
                                                    <div class="bubble you">
                                                        Hi, I am back from vacation
                                                    </div>
                                                    <div class="bubble you">
                                                        How are you?
                                                    </div>
                                                    <div class="bubble me">
                                                        Welcom Back
                                                    </div>
                                                    <div class="bubble me">
                                                        I am all well
                                                    </div>
                                                    <div class="bubble you">
                                                        Coffee?
                                                    </div>
                                                </div>
                                                <div class="chat" data-chat="person7">
                                                </div>
                                                <div class="chat" data-chat="person8">
                                                </div>
                                                <div class="chat" data-chat="person9">
                                                </div>
                                                <div class="chat" data-chat="person10">
                                                </div>
                                                <div class="chat" data-chat="person11">
                                                </div>
                                                <div class="chat" data-chat="person12">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="chat-footer">
                                            <div class="chat-input">
                                                <form class="chat-form" action="javascript:void(0);">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                                    <input type="text" class="mail-write-box form-control" placeholder="Message"/>
                                                </form>
                                            </div>
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

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="assets/js/apps/mailbox-chat.js"></script>
    <!-- END PAGE LEVEL SCRIPTS -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
</body>
</html>