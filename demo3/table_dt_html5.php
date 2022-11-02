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
    <title>DataTables HTML5 Export | Creators Menejerlik ve Mail Üyelik Sistemine Hoşgeldiniz </title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico"/>
    <link href="assets/css/loader.css" rel="stylesheet" type="text/css" />
    <script src="assets/js/loader.js"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
    <!-- BEGIN PAGE LEVEL CUSTOM STYLES -->
    <link rel="stylesheet" type="text/css" href="plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="plugins/table/datatable/custom_dt_html5.css">
    <link rel="stylesheet" type="text/css" href="plugins/table/datatable/dt-global_style.css">
    <!-- END PAGE LEVEL CUSTOM STYLES -->
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


                    <li class="menu">
                        <a href="./map_jvector.php"  aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                            <i class="fas fa-map-signs"></i>
                                <span>Harita</span>
                            </div>

                        </a>

                    </li>
                </ul>
                <!-- <div class="shadow-bottom"></div> -->
                
            </nav>

        </div>
        <!--  END SIDEBAR  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                
                <div class="row layout-top-spacing" id="cancel-row">
                
                    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                        <div class="widget-content widget-content-area br-6">
                            <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>İsim</th>
                                        <th>İş</th>
                                        <th>Yer</th>
                                        <th>Yaş</th>
                                        <th>Kayıt Tarihi</th>
                                        <th>Kazanç</th>

                                        <th>Fotoğraf</th>
                                        <th class="dt-no-sorting"></th>
                                    </tr>
                                </thead>
                                <tbody>

<?php 
    $todosor=$db->prepare("SELECT * FROM kullanici order by kullanici_id ");
    $todosor->execute(array());

    while($todocek=$todosor->fetch(PDO::FETCH_ASSOC)){
?>

                                    <tr>
                                        <td><?php echo $todocek['kullanici_adsoyad'] ?></td>
                                        <td><?php echo $todocek['user_job'] ?></td>
                                        <td><?php echo $todocek['user_country'] ?></td>
                                        
                                        
                                        <td><?php echo (date("Y")-$todocek['user_year'])  ?></td>
                                        <td><?php
                                        $katilma= explode(" ",$todocek['kullanici_zaman']);
                                        
                                        echo $katilma[0]; ?></td>
                                        <td><?php echo ($todocek['youtube_salary']+$todocek['inf_salary']+$todocek['mene_salary'])  ?> ₺</td>

                                        <td>
                                            <div class="d-flex">
                                                <div class="usr-img-frame mr-2 rounded-circle">
                                                    <img alt="avatar" src=".<?php echo $todocek['kullanici_resim']; ?>" class="img-fluid rounded-circle" >
                                                </div>
                                            </div>
                                        </td>
                                        <td>    
                                            <div class="btn-group">
                                                <a href="./another_profile.php?kullanici_id=<?php echo $todocek['kullanici_id'] ?>"   style="background-color:#060818; color: white; padding:15px;">Görüntüle</a>
                                                <button type="button" class="btn btn-dark btn-sm dropdown-toggle dropdown-toggle-split" id="dropdownMenuReference1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuReference1">
                                                    <a class="dropdown-item" href="./netting/islem.php?kullanici_sil=ok&kullanici_id=<?php echo $todocek['kullanici_id'] ?>">Sil</a>
                                                    <a class="dropdown-item" href="./netting/islem.php?yetkilendir=ok&kullanici_id=<?php echo $todocek['kullanici_id'] ?>">Yetkilendir</a>
                                                    <a class="dropdown-item" href="#">Düzenle</a>
                                                </div>
                                        </td>
                                    </tr>
<?php } ?>
                                </tbody>
                            </table>
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

    <!-- BEGIN PAGE LEVEL CUSTOM SCRIPTS -->
    <script src="plugins/table/datatable/datatables.js"></script>
    <!-- NOTE TO Use Copy CSV Excel PDF Print Options You Must Include These Files  -->
    <script src="plugins/table/datatable/button-ext/dataTables.buttons.min.js"></script>
    <script src="plugins/table/datatable/button-ext/jszip.min.js"></script>    
    <script src="plugins/table/datatable/button-ext/buttons.html5.min.js"></script>
    <script src="plugins/table/datatable/button-ext/buttons.print.min.js"></script>
    <script>
        $('#html5-extension').DataTable( {
            "dom": "<'dt--top-section'<'row'<'col-sm-12 col-md-6 d-flex justify-content-md-start justify-content-center'B><'col-sm-12 col-md-6 d-flex justify-content-md-end justify-content-center mt-md-0 mt-3'f>>>" +
        "<'table-responsive'tr>" +
        "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
            buttons: {
                buttons: [
                    { extend: 'copy', className: 'btn btn-sm' },
                    { extend: 'csv', className: 'btn btn-sm' },
                    { extend: 'excel', className: 'btn btn-sm' },
                    { extend: 'print', className: 'btn btn-sm' }
                ]
            },
            "oLanguage": {
                "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
               "sLengthMenu": "Results :  _MENU_",
            },
            "stripeClasses": [],
            "lengthMenu": [7, 10, 20, 50],
            "pageLength": 7 
        } );
    </script>


<script>
function myFunction() {
  var copyText = document.getElementById("myInput");
  copyText.select();
  copyText.setSelectionRange(0, 99999)
  document.execCommand("copy");
  alert("Copied the text: " + copyText.value);
}
</script>

    <!-- END PAGE LEVEL CUSTOM SCRIPTS -->
</body>
</html>