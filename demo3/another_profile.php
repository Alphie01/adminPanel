<?php
ob_start();
session_start();
error_reporting(0);

include './netting/baglan.php';
include 'fonksiyon.php';

//Belirli veriyi seçme işlemi
$ayarsor = $db->prepare("SELECT * FROM ayar where ayar_id=:id");
$ayarsor->execute(array(
    'id' => 0
));
$ayarcek = $ayarsor->fetch(PDO::FETCH_ASSOC);


$kullanicisor = $db->prepare("SELECT * FROM kullanici where kullanici_mail=:mail");
$kullanicisor->execute(array(
    'mail' => $_SESSION['kullanici_mail']
));
$say = $kullanicisor->rowCount();
$kullanicicek = $kullanicisor->fetch(PDO::FETCH_ASSOC);

if ($say == 0) {

    Header("Location:../index.php?durum=izinsiz");
    exit;
}




//1.Yöntem

if (!isset($_SESSION['kullanici_mail'])) {
    Header("Location:../index.php");
}

$kullaniciid = $_GET['kullanici_id'];

$denesor = $db->prepare("SELECT * FROM kullanici where kullanici_id=:kullanici_id");
$denesor->execute(array(
    'kullanici_id' => $kullaniciid
));

$denecek = $denesor->fetch(PDO::FETCH_ASSOC);


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>User Profile | Creators Menejerlik ve Mail Üyelik Sistemine Hoşgeldiniz </title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
    <!--  BEGIN CUSTOM STYLE FILE  -->
    <link href="assets/css/users/user-profile.css" rel="stylesheet" type="text/css" />
    <!--  END CUSTOM STYLE FILE  -->
</head>

<body>

    <!--  BEGIN NAVBAR  -->
    <div class="header-container fixed-top">
        <header class="header navbar navbar-expand-sm">

            <ul class="navbar-item theme-brand flex-row  text-center">
                <li class="nav-item theme-logo">
                    <a href="index.html">
                        <img src="./img/creatorschannel-cutout.png" class="navbar-logo" alt="logo">
                    </a>
                </li>
                <li class="nav-item theme-text">
                    <a href="index.html" class="nav-link"> Creators </a>
                </li>
            </ul>

            <ul class="navbar-item flex-row ml-md-0 ml-auto">
                <li class="nav-item align-self-center search-animated">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search toggle-search">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
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
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                    </a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="messageDropdown">
                        <div class="">

                            <a class="dropdown-item">
                                <div class="">

                                    <div class="media">
                                        <div class="user-img">
                                            <div class="avatar avatar-xl">
                                                <span class="avatar-title rounded-circle">OG</span>
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <div class="">
                                                <h5 class="usr-name">Mesaj 1</h5>
                                                <p class="msg-title">Mesaj</p>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </a>




                        </div>
                    </div>
                </li>

                <li class="nav-item dropdown notification-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg><span class="badge badge-success"></span>
                    </a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="notificationDropdown">
                        <div class="notification-scroll">

                            <div class="dropdown-item">
                                <div class="media server-log">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-server">
                                        <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                                        <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                                        <line x1="6" y1="6" x2="6" y2="6"></line>
                                        <line x1="6" y1="18" x2="6" y2="18"></line>
                                    </svg>
                                    <div class="media-body">
                                        <div class="data-info">
                                            <h6 class="">Creators'e hoşgeldiniz</h6>
                                            <p class="">45 min ago</p>
                                        </div>

                                        <div class="icon-status">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                                <line x1="6" y1="6" x2="18" y2="18"></line>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>

                </li>

                <li class="nav-item dropdown user-profile-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <img src=".<?php if ($kullanicicek['kullanici_resim'] != '') {
                                        echo $kullanicicek['kullanici_resim'];
                                    } else {
                                        echo "/face.jpg";
                                    } ?>" alt="avatar">
                    </a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                        <div class="">
                            <div class="dropdown-item">
                                <a class="" href="user_profile.php"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg> Profile</a>
                            </div>


                            <div class="dropdown-item">
                                <a class="" href="../logout.php"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                        <polyline points="16 17 21 12 16 7"></polyline>
                                        <line x1="21" y1="12" x2="9" y2="12"></line>
                                    </svg> Çıkış Yap</a>
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
            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg></a>

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
                        <a class="dropdown-toggle btn" href="#" role="button" id="customDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span>Yeniliklerden Haberdar Olun.</span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg></a>

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
                        <a href="./index.php" aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                </svg>
                                <span>Dashboard</span>
                            </div>

                        </a>

                    </li>

                    <?php

                    if ($kullanicicek['kullanici_yetki'] != 0) {


                    ?>
                        <li class="menu">
                            <a href="./user_profile.php" aria-expanded="true" class="dropdown-toggle">
                                <div class="">
                                    <i class="fa fa-user"></i>
                                    <span>Kullanıcı Profili</span>
                                </div>

                            </a>

                        </li>


                        <li class="menu">
                            <a href="./apps_chat.php" aria-expanded="true" class="dropdown-toggle">
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
                            <a href="./apps_notes.php" aria-expanded="true" class="dropdown-toggle">
                                <div class="">
                                    <i class="far fa-sticky-note"></i>
                                    <span>Notlar</span>
                                </div>

                            </a>

                        </li>

                        <li class="menu">
                            <a href="./apps_scrumboard.php" aria-expanded="true" class="dropdown-toggle">
                                <div class="">
                                    <i class="fas fa-notes-medical"></i>
                                    <span>Scrumboard</span>
                                </div>

                            </a>

                        </li>

                    <?php } ?>
                    <li class="menu">
                        <a href="./apps_contacts.php" aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                                <i class="far fa-comment"></i>
                                <span>Takımımız</span>
                            </div>

                        </a>

                    </li>
                    <?php if ($kullanicicek['kullanici_yetki'] == 5) { ?>
                        <li class="menu">
                            <a href="./apps_invoice-list.php" aria-expanded="true" class="dropdown-toggle">
                                <div class="">
                                    <i class="fas fa-dollar-sign"></i>
                                    <span>Fatura</span>
                                </div>

                            </a>

                        </li>
                    <?php }
                    if ($kullanicicek['kullanici_yetki'] != 0) { ?>
                        <li class="menu">
                            <a href="./apps_calendar.php" aria-expanded="true" class="dropdown-toggle">
                                <div class="">
                                    <i class="far fa-calendar-check"></i>
                                    <span>Takvim</span>
                                </div>

                            </a>

                        </li>

                    <?php }
                    if ($kullanicicek['kullanici_yetki'] == 0 || $kullanicicek['kullanici_yetki'] == 5) {

                    ?>
                        <li class="menu">
                            <a href="./component_pricing_table.php" aria-expanded="true" class="dropdown-toggle">
                                <div class="">
                                    <i class="fas fa-shopping-basket"></i>
                                    <span>Fiyatlandırma</span>
                                </div>

                            </a>

                        </li>
                    <?php  } ?>

                    <?php
                    if ($kullanicicek['kullanici_yetki'] != 0) {

                    ?>
                        <li class="menu">
                            <a href="./component_lightbox.php" aria-expanded="true" class="dropdown-toggle">
                                <div class="">
                                    <i class="fas fa-photo-video"></i>
                                    <span>Galeri</span>
                                </div>

                            </a>

                        </li>
                    <?php  } ?>

                    <?php
                    if ($kullanicicek['kullanici_yetki'] == 5) {

                    ?>
                        <li class="menu">
                            <a href="./table_dt_html5.php" aria-expanded="true" class="dropdown-toggle">
                                <div class="">
                                    <i class="fas fa-user"></i>
                                    <span>Kullanıcılar</span>
                                </div>

                            </a>

                        </li>
                    <?php } ?>


                    <li class="menu">
                        <a href="./pages_helpdesk.php" aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                                <i class="fas fa-info-circle"></i>
                                <span>Yardım</span>
                            </div>

                        </a>

                    </li>


                    <li class="menu">
                        <a href="./pages_faq.php" aria-expanded="true" class="dropdown-toggle">
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
                    if ($kullanicicek['kullanici_yetki'] == 5) {

                    ?>
                        <li class="menu">
                            <a href="./map_jvector.php" aria-expanded="true" class="dropdown-toggle">
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

                <div class="row layout-spacing">

                    <!-- Content -->
                    <div class="col-xl-4 col-lg-6 col-md-5 col-sm-12 layout-top-spacing">

                        <div class="user-profile layout-spacing">
                            <div class="widget-content widget-content-area">
                                <div class="d-flex justify-content-between">
                                    <h3 class="">Profile</h3>
                                    <a href="user_account_setting.php" class="mt-2 edit-profile"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3">
                                            <path d="M12 20h9"></path>
                                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                        </svg></a>
                                </div>
                                <div class="text-center user-info">
                                    <img src=".<?php echo $denecek['kullanici_resim'] ?>" width="65px" height="65px" alt="avatar">
                                    <p class=""><?php echo $denecek['kullanici_ad'] ?></p>
                                </div>
                                <div class="user-info-list">

                                    <div class="">
                                        <ul class="contacts-block list-unstyled">
                                            <li class="contacts-block__item">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-coffee">
                                                    <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                                                    <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                                                    <line x1="6" y1="1" x2="6" y2="4"></line>
                                                    <line x1="10" y1="1" x2="10" y2="4"></line>
                                                    <line x1="14" y1="1" x2="14" y2="4"></line>
                                                </svg> <?php echo $denecek['user_job'] ?>
                                            </li>
                                            <li class="contacts-block__item">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar">
                                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                                </svg><?php echo $denecek['kullanici_zaman'] ?>
                                            </li>
                                            <li class="contacts-block__item">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin">
                                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                                    <circle cx="12" cy="10" r="3"></circle>
                                                </svg><?php echo $denecek['user_country'] ?>
                                            </li>
                                            <li class="contacts-block__item">
                                                <a href="mailto:<?php echo $denecek['kullanici_mail'] ?>"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail">
                                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                                        <polyline points="22,6 12,13 2,6"></polyline>
                                                    </svg><?php echo $denecek['kullanici_mail'] ?></a>
                                            </li>
                                            <li class="contacts-block__item">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone">
                                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                                </svg> <?php echo $denecek['kullanici_gsm'] ?>
                                            </li>
                                            <li class="contacts-block__item">
                                                <ul class="list-inline">
                                                    <li class="list-inline-item">

                                                        <a href="<?php echo $denecek['kullanici_insta'] ?>"><i class="fab fa-instagram fa-2x" style="color:white;"></i> </a>

                                                    </li>
                                                    <li class="list-inline-item">
                                                        <a href="<?php echo $denecek['kullanici_youtube'] ?>"><i class="fab fa-youtube fa-2x" style="color:white;"></i> </a>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <a href="<?php echo $denecek['kullanici_twitter'] ?>"><i class="fab fa-twitter fa-2x" style="color:white;"></i> </a>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <a href="<?php echo $denecek['kullanici_twitch'] ?>"><i class="fab fa-twitch fa-2x" style="color:white;"></i> </a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="education layout-spacing ">
                            <div class="widget-content widget-content-area">
                                <h3 class="">Kazanç</h3>
                                <div class="timeline-alter">
                                    <div class="item-timeline">
                                        <div class="t-meta-date">
                                            <p class="">Youtube Kazanç</p>
                                        </div>
                                        <div class="t-dot">
                                        </div>
                                        <div class="t-text">
                                            <p><?php if ($denecek['youtube_salary'] != 0) echo $denecek['youtube_salary'];
                                                else echo "0"; ?> ₺</p>

                                        </div>
                                    </div>
                                    <div class="item-timeline">
                                        <div class="t-meta-date">
                                            <p class="">İnfluencer Kazanç</p>
                                        </div>
                                        <div class="t-dot">
                                        </div>
                                        <div class="t-text">
                                            <p><?php if ($denecek['inf_salary'] != 0) echo $denecek['inf_salary'];
                                                else echo "0"; ?> ₺</p>


                                        </div>
                                    </div>
                                    <div class="item-timeline">
                                        <div class="t-meta-date">
                                            <p class="">Menejerlik Kazanç</p>
                                        </div>
                                        <div class="t-dot">
                                        </div>
                                        <div class="t-text">
                                            <p><?php if ($denecek['mene_salary'] != 0) echo $denecek['mene_salary'];
                                                else echo "0"; ?> ₺</p>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="col-xl-8 col-lg-6 col-md-7 col-sm-12 layout-top-spacing">



                        <div class="bio layout-spacing ">
                            <div class="widget-content widget-content-area">
                                <h3 class="">Bio</h3>
                                <p><?php echo $denecek['kullanici_aciklama'] ?></p>

                                <div class="bio-skill-box">

                                    <div class="row">


                                        <?php
                                        $todosor = $db->prepare("SELECT * FROM photos where photos_sendby=:photos_sendby order by photos_id desc LIMIT 25");
                                        $todosor->execute(array(
                                            'photos_sendby' => $_GET['kullanici_id']
                                        ));
                                        while ($todocek = $todosor->fetch(PDO::FETCH_ASSOC)) {
                                        ?>

                                            <div class="col-12 col-xl-6 col-lg-12 mb-xl-5 mb-5 ">

                                                <img src=".<?php echo $todocek['photos_imgs'] ?>" width="75%" alt="image-gallery">

                                            </div>
                                        <?php } ?>

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
    <!-- END GLOBAL MANDATORY SCRIPTS -->
</body>

</html>