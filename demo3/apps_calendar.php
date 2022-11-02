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
    <title>Fullcalendar Drag and Drop Event | Creators Menejerlik ve Mail Üyelik Sistemine Hoşgeldiniz </title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL STYLE -->
    <link href="plugins/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css" />
    <link href="plugins/fullcalendar/custom-fullcalendar.advance.css" rel="stylesheet" type="text/css" />
    <link href="plugins/flatpickr/flatpickr.css" rel="stylesheet" type="text/css">
    <link href="plugins/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
    <link href="assets/css/forms/theme-checkbox-radio.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL STYLE -->
    <style>.widget-content-area { border-radius: 6px;
            -webkit-box-shadow: 0 6px 10px 0 rgba(0,0,0,.14), 0 1px 18px 0 rgba(0,0,0,.12), 0 3px 5px -1px rgba(0,0,0,.2);
            -moz-box-shadow: 0 6px 10px 0 rgba(0,0,0,.14), 0 1px 18px 0 rgba(0,0,0,.12), 0 3px 5px -1px rgba(0,0,0,.2);
            box-shadow: 0 6px 10px 0 rgba(0,0,0,.14), 0 1px 18px 0 rgba(0,0,0,.12), 0 3px 5px -1px rgba(0,0,0,.2); margin-bottom: 10px; }
        .daterangepicker.dropdown-menu {
            z-index: 1059;
        }
    </style>

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
                    <?php  } ?>
                </ul>
                <!-- <div class="shadow-bottom"></div> -->
                
            </nav>

        </div>
        <!--  END SIDEBAR  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div class="row layout-top-spacing" id="cancel-row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <div class="statbox widget box box-shadow">
                            <div class="widget-content widget-content-area">
                                <div class="calendar-upper-section">
                                    <div class="row">
                                        <div class="col-md-8 col-12">
                                            <div class="labels">
                                                <p class="label label-primary">İş</p>
                                                <p class="label label-warning">Creators</p>
                                                <p class="label label-success">Kids</p>
                                                <p class="label label-danger">Akademi</p>
                                            </div>
                                        </div>                                                
                                        <div class="col-md-4 col-12">
                                            <form action="javascript:void(0);" class="form-horizontal mt-md-0 mt-3 text-md-right text-center">
                                                <button id="myBtn" class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar mr-2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> Ekle</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div id="calendar"></div>
                            </div>
                        </div>
                    </div>

                    <!-- The Modal -->
                    <div id="addEventsModal" class="modal animated fadeIn">

                        <div class="modal-dialog modal-dialog-centered">
                            
                            <!-- Modal content -->
                            <div class="modal-content">

                                <div class="modal-body">

                                    <span class="close">&times;</span>

                                    <div class="add-edit-event-box">
                                        <div class="add-edit-event-content">
                                            <h5 class="add-event-title modal-title">Olay Ekle</h5>
                                            <h5 class="edit-event-title modal-title">Edit Events</h5>

                                            <form action="./netting/islem.php"  enctype="multipart/form-data" method="POST">

                                                <div class="row">

                                                    <div class="col-md-12">
                                                        <label for="start-date" class="">Takvim Başlık:</label>
                                                        <div class="d-flex ">
                                                            <input type="text" name="calender_title"  placeholder="Başlık Girin" class="form-control" >
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 col-sm-6 col-12">
                                                        <div class="form-group start-date">
                                                            <label for="start-date" class="">Başlangıç:</label>
                                                            <div class="d-flex">
                                                                <input id="start-date" name="calender_begin" placeholder="Başlangıç Tarihi" class="form-control" type="text">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6 col-12">
                                                        <div class="form-group end-date">
                                                            <label for="end-date" class="">Bitiş:</label>
                                                            <div class="d-flex">
                                                                <input id="end-date" name="calender_end" placeholder="Bitiş  Tarihi" type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>  
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label for="start-date" class="">Açıklama:</label>
                                                        <div class="d-flex event-description">
                                                            <textarea name="calender_content" placeholder="Açıklama" rows="3" class="form-control" ></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="event-badge">
                                                            <p class="">Nişan:</p>

                                                            <div class="d-sm-flex d-block">
                                                                <div class="n-chk">
                                                                    <label class="new-control new-radio radio-primary">
                                                                      <input type="radio" class="new-control-input" name="calender_class" value="bg-primary">
                                                                      <span class="new-control-indicator"></span>İş
                                                                    </label>
                                                                </div>

                                                                <div class="n-chk">
                                                                    <label class="new-control new-radio radio-warning">
                                                                      <input type="radio" class="new-control-input" name="calender_class" value="bg-warning">
                                                                      <span class="new-control-indicator"></span>Creators
                                                                    </label>
                                                                </div>

                                                                <div class="n-chk">
                                                                    <label class="new-control new-radio radio-success">
                                                                      <input type="radio" class="new-control-input" name="calender_class" value="bg-success">
                                                                      <span class="new-control-indicator"></span>Kids
                                                                    </label>
                                                                </div>

                                                                <div class="n-chk">
                                                                    <label class="new-control new-radio radio-danger">
                                                                      <input type="radio" class="new-control-input" name="calender_class" value="bg-danger">
                                                                      <span class="new-control-indicator"></span>Akademi
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="calender_user" value="<?php echo $kullanicicek['kullanici_id']  ?>">
<div class="row">
<button id="discard" type="submit" class="btn btn-secondary" data-dismiss="modal">Sil</button>
                                    <button id="add-e" name="calenderkayit" type="submit" class="btn btn-primary">Ekle</button>
                                    <button id="edit-event" type="submit" class="btn">Kaydet</button>
</div>
                                                        </div>
                                                    </div>
                                                </div>
                                    
                                            </form>
                                        </div>
                                    </div>

                                </div>

                                <div class="modal-footer">

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


<?php 


$todosor=$db->prepare("SELECT * FROM kalender where calender_user=:calender_user order by calender_id desc LIMIT 25");
$todosor->execute(array(
'calender_user' => $kullanicicek['kullanici_id']
));
                $dene=0;
while($todocek=$todosor->fetch(PDO::FETCH_ASSOC)){
?>



<input type="hidden" id="calenderid<?php echo $dene ;?>" value="event-<?php echo $dene ;?>">
<input type="hidden" id="calendertitle<?php echo $dene ?>" value="<?php 
if($todocek['calender_title']!=''){echo $todocek['calender_title'];}
else{echo " ";} 

?>">
<input type="hidden" id="calendercontent<?php echo $dene ?>" value="<?php  if($todocek['calender_content']!=''){echo $todocek['calender_content'];}else{echo " ";}  ?>">
<input type="hidden" id="calenderclass<?php echo $dene ?>" value="<?php if($todocek['calender_class']!=''){echo $todocek['calender_class'];}else{echo " ";} ?>">
<input type="hidden" id="calenderbegindate<?php echo $dene ?>" value="<?php 

if($todocek['calender_begin']!=''){
    $timebegins=explode(" ",$todocek['calender_begin']);
$timecalenderbegin = explode("-",$timebegins[0]);

echo $timecalenderbegin[2];
}else{
    echo '2021-05-20';
}

?>">
<input type="hidden" id="calenderbegintime<?php echo $dene ?>" value="<?php 

if($todocek['calender_begin']!=''){
    $timebegin=explode(" ",$todocek['calender_begin']);
    echo $timebegin[1];

}else{
    echo '12:00';
}

?>">
<input type="hidden" id="calenderenddate<?php echo $dene ?>" value="<?php

if($todocek['calender_begin']!=''){
    $timeends=explode(" ",$todocek['calender_end']);
    $timecalenderend = explode("-",$timeends[0]);
    
    echo $timecalenderend[2];
}else{
    echo '2021-05-20';
}






?>">
<input type="hidden" id="calenderendtime<?php echo $dene ?>" value="<?php 

if($todocek['calender_begin']!=''){
    $timeend=explode(" ",$todocek['calender_end']);
    echo $timeend[1];
    
}else{
    echo '12:00';
}



?>">




<?php 
$dene++;


} ?>


    
    <!-- START GLOBAL MANDATORY SCRIPTS -->
    <script src="assets/js/libs/jquery-3.1.1.min.js"></script>
    <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
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
    <script src="plugins/fullcalendar/moment.min.js"></script>
    <script src="plugins/flatpickr/flatpickr.js"></script>
    <script src="plugins/fullcalendar/fullcalendar.min.js"></script>
    <!-- END PAGE LEVEL SCRIPTS -->

    <!--  BEGIN CUSTOM SCRIPTS FILE  -->

    <!--  END CUSTOM SCRIPTS FILE  -->


    <script>

$(document).ready(function() {

// Get the modal
var modal = document.getElementById("addEventsModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the Add Event button
var addEvent = document.getElementById("add-e");
// Get the Edit Event button
var editEvent = document.getElementById("edit-event");
// Get the Discard Modal button
var discardModal = document.querySelectorAll("[data-dismiss='modal']")[0];

// Get the Add Event button
var addEventTitle = document.getElementsByClassName("add-event-title")[0];
// Get the Edit Event button
var editEventTitle = document.getElementsByClassName("edit-event-title")[0];

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// Get the all <input> elements insdie the modal
var input = document.querySelectorAll('input[type="text"]');
var radioInput = document.querySelectorAll('input[type="radio"]');

// Get the all <textarea> elements insdie the modal
var textarea = document.getElementsByTagName('textarea');

// Create BackDrop ( Overlay ) Element
function createBackdropElement () {
    var btn = document.createElement("div");
    btn.setAttribute('class', 'modal-backdrop fade show')
    document.body.appendChild(btn);
}

// Reset radio buttons

function clearRadioGroup(GroupName) {
  var ele = document.getElementsByName(GroupName);
    for(var i=0;i<ele.length;i++)
    ele[i].checked = false;
}

// Reset Modal Data on when modal gets closed
function modalResetData() {
    modal.style.display = "none";
    for (i = 0; i < input.length; i++) {
        input[i].value = '';
    }
    for (j = 0; j < textarea.length; j++) {
        textarea[j].value = '';
      i
    }
    clearRadioGroup("marker");
    // Get Modal Backdrop
    var getModalBackdrop = document.getElementsByClassName('modal-backdrop')[0];
    document.body.removeChild(getModalBackdrop)
}

// When the user clicks on the button, open the modal
btn.onclick = function() {
    modal.style.display = "block";
    addEvent.style.display = 'block';
    editEvent.style.display = 'none';
    addEventTitle.style.display = 'block';
    editEventTitle.style.display = 'none';
    document.getElementsByTagName('body')[0].style.overflow = 'hidden';
    createBackdropElement();
    enableDatePicker();
}

// Clear Data and close the modal when the user clicks on Discard button
discardModal.onclick = function() {
    modalResetData();
    document.getElementsByTagName('body')[0].removeAttribute('style');
}

// Clear Data and close the modal when the user clicks on <span> (x).
span.onclick = function() {
    modalResetData();
    document.getElementsByTagName('body')[0].removeAttribute('style');
}

// Clear Data and close the modal when the user clicks anywhere outside of the modal.
window.onclick = function(event) {
    if (event.target == modal) {
        modalResetData();
        document.getElementsByTagName('body')[0].removeAttribute('style');
    }
}

newDate = new Date()
monthArray = [ '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12' ]

function getDynamicMonth( monthOrder ) {
    var getNumericMonth = parseInt(monthArray[newDate.getMonth()]);
    var getNumericMonthInc = parseInt(monthArray[newDate.getMonth()]) + 1;
    var getNumericMonthDec = parseInt(monthArray[newDate.getMonth()]) - 1;

    if (monthOrder === 'default') {

        if (getNumericMonth < 10 ) {
            return '0' + getNumericMonth;
        } else if (getNumericMonth >= 10) {
            return getNumericMonth;
        }

    } else if (monthOrder === 'inc') {

        if (getNumericMonthInc < 10 ) {
            return '0' + getNumericMonthInc;
        } else if (getNumericMonthInc >= 10) {
            return getNumericMonthInc;
        }

    } else if (monthOrder === 'dec') {

        if (getNumericMonthDec < 10 ) {
            return '0' + getNumericMonthDec;
        } else if (getNumericMonthDec >= 10) {
            return getNumericMonthDec;
        }
    }
}

/* initialize the calendar
-----------------------------------------------------------------*/

var calendar = $('#calendar').fullCalendar({
    header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay'
    },
    events: [
        {
            id: 'event-0',
            title: document.getElementById("calendertitle0").value,
            start: newDate.getFullYear() + '-'+ getDynamicMonth('default') +'-'+document.getElementById("calenderbegindate0").value+'T'+document.getElementById("calenderbegintime0").value,
            end: newDate.getFullYear() + '-'+ getDynamicMonth('default') +'-'+document.getElementById("calenderenddate0").value+'T'+document.getElementById("calenderendtime0").value,
            className: document.getElementById("calenderclass0").value,
            description: document.getElementById("calendercontent0").value
        },
        {
            id: 'event-1',
            title: document.getElementById("calendertitle1").value,
            start: newDate.getFullYear() + '-'+ getDynamicMonth('default') +'-'+document.getElementById("calenderbegindate1").value+'T'+document.getElementById("calenderbegintime1").value,
            end: newDate.getFullYear() + '-'+ getDynamicMonth('default') +'-'+document.getElementById("calenderenddate1").value+'T'+document.getElementById("calenderendtime1").value,
            className: document.getElementById("calenderclass1").value,
            description: document.getElementById("calendercontent1").value
        },{
            id: 'event-2',
            title: document.getElementById("calendertitle2").value,
            start: newDate.getFullYear() + '-'+ getDynamicMonth('default') +'-'+document.getElementById("calenderbegindate2").value+'T'+document.getElementById("calenderbegintime2").value,
            end: newDate.getFullYear() + '-'+ getDynamicMonth('default') +'-'+document.getElementById("calenderenddate2").value+'T'+document.getElementById("calenderendtime2").value,
            className: document.getElementById("calenderclass2").value,
            description: document.getElementById("calendercontent2").value
        },{
            id: 'event-3',
            title: document.getElementById("calendertitle3").value,
            start: newDate.getFullYear() + '-'+ getDynamicMonth('default') +'-'+document.getElementById("calenderbegindate3").value+'T'+document.getElementById("calenderbegintime3").value,
            end: newDate.getFullYear() + '-'+ getDynamicMonth('default') +'-'+document.getElementById("calenderenddate3").value+'T'+document.getElementById("calenderendtime3").value,
            className: document.getElementById("calenderclass3").value,
            description: document.getElementById("calendercontent3").value
        },{
            id: 'event-4',
            title: document.getElementById("calendertitle4").value,
            start: newDate.getFullYear() + '-'+ getDynamicMonth('default') +'-'+document.getElementById("calenderbegindate4").value+'T'+document.getElementById("calenderbegintime4").value,
            end: newDate.getFullYear() + '-'+ getDynamicMonth('default') +'-'+document.getElementById("calenderenddate4").value+'T'+document.getElementById("calenderendtime4").value,
            className: document.getElementById("calenderclass4").value,
            description: document.getElementById("calendercontent4").value
        },{
            id: 'event-5',
            title: document.getElementById("calendertitle5").value,
            start: newDate.getFullYear() + '-'+ getDynamicMonth('default') +'-'+document.getElementById("calenderbegindate5").value+'T'+document.getElementById("calenderbegintime5").value,
            end: newDate.getFullYear() + '-'+ getDynamicMonth('default') +'-'+document.getElementById("calenderenddate5").value+'T'+document.getElementById("calenderendtime5").value,
            className: document.getElementById("calenderclass5").value,
            description: document.getElementById("calendercontent5").value
        },{
            id: 'event-6',
            title: document.getElementById("calendertitle6").value,
            start: newDate.getFullYear() + '-'+ getDynamicMonth('default') +'-'+document.getElementById("calenderbegindate6").value+'T'+document.getElementById("calenderbegintime6").value,
            end: newDate.getFullYear() + '-'+ getDynamicMonth('default') +'-'+document.getElementById("calenderenddate6").value+'T'+document.getElementById("calenderendtime6").value,
            className: document.getElementById("calenderclass6").value,
            description: document.getElementById("calendercontent6").value
        },{
            id: 'event-7',
            title: document.getElementById("calendertitle7").value,
            start: newDate.getFullYear() + '-'+ getDynamicMonth('default') +'-'+document.getElementById("calenderbegindate7").value+'T'+document.getElementById("calenderbegintime7").value,
            end: newDate.getFullYear() + '-'+ getDynamicMonth('default') +'-'+document.getElementById("calenderenddate7").value+'T'+document.getElementById("calenderendtime7").value,
            className: document.getElementById("calenderclass7").value,
            description: document.getElementById("calendercontent7").value
        },{
            id: 'event-8',
            title: document.getElementById("calendertitle8").value,
            start: newDate.getFullYear() + '-'+ getDynamicMonth('default') +'-'+document.getElementById("calenderbegindate8").value+'T'+document.getElementById("calenderbegintime8").value,
            end: newDate.getFullYear() + '-'+ getDynamicMonth('default') +'-'+document.getElementById("calenderenddate8").value+'T'+document.getElementById("calenderendtime8").value,
            className: document.getElementById("calenderclass8").value,
            description: document.getElementById("calendercontent8").value
        },{
            id: 'event-9',
            title: document.getElementById("calendertitle9").value,
            start: newDate.getFullYear() + '-'+ getDynamicMonth('default') +'-'+document.getElementById("calenderbegindate9").value+'T'+document.getElementById("calenderbegintime9").value,
            end: newDate.getFullYear() + '-'+ getDynamicMonth('default') +'-'+document.getElementById("calenderenddate9").value+'T'+document.getElementById("calenderendtime9").value,
            className: document.getElementById("calenderclass9").value,
            description: document.getElementById("calendercontent9").value
        },{
            id: 'event-10',
            title: document.getElementById("calendertitle10").value,
            start: newDate.getFullYear() + '-'+ getDynamicMonth('default') +'-'+document.getElementById("calenderbegindate10").value+'T'+document.getElementById("calenderbegintime10").value,
            end: newDate.getFullYear() + '-'+ getDynamicMonth('default') +'-'+document.getElementById("calenderenddate10").value+'T'+document.getElementById("calenderendtime10").value,
            className: document.getElementById("calenderclass10").value,
            description: document.getElementById("calendercontent10").value
        }



    ],
    editable: true,
    eventLimit: true,
    eventMouseover: function(event, jsEvent, view) {
        $(this).attr('id', event.id);

        $('#'+event.id).popover({
            template: '<div class="popover popover-primary" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>',
            title: event.title,
            content: event.description,
            placement: 'top',
        });

        $('#'+event.id).popover('show');
    },
    eventMouseout: function(event, jsEvent, view) {
        $('#'+event.id).popover('hide');
    },
    eventClick: function(info) {

        addEvent.style.display = 'none';
        editEvent.style.display = 'block';

        addEventTitle.style.display = 'none';
        editEventTitle.style.display = 'block';
        modal.style.display = "block";
        document.getElementsByTagName('body')[0].style.overflow = 'hidden';
        createBackdropElement();

        // Calendar Event Featch
        var eventTitle = info.title;
        var eventDescription = info.description;

        // Task Modal Input
        var taskTitle = $('#write-e');
        var taskTitleValue = taskTitle.val(eventTitle);

        var taskDescription = $('#taskdescription');
        var taskDescriptionValue = taskDescription.val(eventDescription);

        var taskInputStarttDate = $("#start-date");
        var taskInputStarttDateValue = taskInputStarttDate.val(info.start.format("YYYY-MM-DD HH:mm:ss"));

        var taskInputEndDate = $("#end-date");
        var taskInputEndtDateValue = taskInputEndDate.val(info.end.format("YYYY-MM-DD HH:mm:ss"));
    
        var startDate = flatpickr(document.getElementById('start-date'), {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            defaultDate: info.start.format("YYYY-MM-DD HH:mm:ss"),
        });

        var abv = startDate.config.onChange.push(function(selectedDates, dateStr, instance) {
            var endtDate = flatpickr(document.getElementById('end-date'), {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                minDate: dateStr
            });
        })

        var endtDate = flatpickr(document.getElementById('end-date'), {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            defaultDate: info.end.format("YYYY-MM-DD HH:mm:ss"),
            minDate: info.start.format("YYYY-MM-DD HH:mm:ss")
        });

        $('#edit-event').off('click').on('click', function(event) {
            event.preventDefault();
            /* Act on the event */
            var radioValue = $("input[name='marker']:checked").val();

            var taskStartTimeValue = document.getElementById("start-date").value;
            var taskEndTimeValue = document.getElementById("end-date").value;

            info.title = taskTitle.val();
            info.description = taskDescription.val();
            info.start = taskStartTimeValue;
            info.end = taskEndTimeValue;
            info.className = radioValue;

            $('#calendar').fullCalendar('updateEvent', info);
            modal.style.display = "none";
            modalResetData();
            document.getElementsByTagName('body')[0].removeAttribute('style');
        });
    }
})


function enableDatePicker() {
    var startDate = flatpickr(document.getElementById('start-date'), {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        minDate: new Date()
    });

    var abv = startDate.config.onChange.push(function(selectedDates, dateStr, instance) {

        var endtDate = flatpickr(document.getElementById('end-date'), {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            minDate: dateStr
        });
    })

    var endtDate = flatpickr(document.getElementById('end-date'), {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        minDate: new Date()
    });
}


function randomString(length, chars) {
    var result = '';
    for (var i = length; i > 0; --i) result += chars[Math.round(Math.random() * (chars.length - 1))];
    return result;
}
$("#add-e").off('click').on('click', function(event) {
    var radioValue = $("input[name='marker']:checked").val();
    var randomAlphaNumeric = randomString(10, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    var inputValue = $("#write-e").val();
    var inputStarttDate = document.getElementById("start-date").value;
    var inputEndDate = document.getElementById("end-date").value;

    var arrayStartDate = inputStarttDate.split(' ');

    var arrayEndDate = inputEndDate.split(' ');

    var startDate = arrayStartDate[0];
    var startTime = arrayStartDate[1];

    var endDate = arrayEndDate[0];
    var endTime = arrayEndDate[1];

    var concatenateStartDateTime = startDate+'T'+startTime+':00';
    var concatenateEndDateTime = endDate+'T'+endTime+':00';

    var inputDescription = document.getElementById("taskdescription").value;
    var myCalendar = $('#calendar');
    myCalendar.fullCalendar();
    var myEvent = {
      timeZone: 'UTC',
      allDay : false,
      id: randomAlphaNumeric,
      title:inputValue,
      start: concatenateStartDateTime,
      end: concatenateEndDateTime,
      className: radioValue,
      description: inputDescription
    };
    myCalendar.fullCalendar( 'renderEvent', myEvent, true );
    modal.style.display = "none";
    modalResetData();
    document.getElementsByTagName('body')[0].removeAttribute('style');
});


// Setting dynamic style ( padding ) of the highlited ( current ) date

function setCurrentDateHighlightStyle() {
    getCurrentDate = $('.fc-content-skeleton .fc-today').attr('data-date');
    if (getCurrentDate === undefined) {
        return;
    }
    splitDate = getCurrentDate.split('-');
    if (splitDate[2] < 10) {
        $('.fc-content-skeleton .fc-today .fc-day-number').css('padding', '3px 8px');
    } else if (splitDate[2] >= 10) {
        $('.fc-content-skeleton .fc-today .fc-day-number').css('padding', '3px 4px');
    }
}
setCurrentDateHighlightStyle();

const mailScroll = new PerfectScrollbar('.fc-scroller', {
    suppressScrollX : true
});

var fcButtons = document.getElementsByClassName('fc-button');
for(var i = 0; i < fcButtons.length; i++) {
    fcButtons[i].addEventListener('click', function() {
        const mailScroll = new PerfectScrollbar('.fc-scroller', {
            suppressScrollX : true
        });        
        $('.fc-scroller').animate({ scrollTop: 0 }, 100);
        setCurrentDateHighlightStyle();
    })
}
});


    </script>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />


</body>
</html>