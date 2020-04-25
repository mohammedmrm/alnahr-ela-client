<?php
    if(!isset($_SESSION)){
        session_start();
    }
    $access_roles = [1];
    if(! in_array($_SESSION['login'],$access_roles)){
        header("location: login.php");
        die();
    }
    require_once("config.php");
    ?>
<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=2, viewport-fit=cover" />
    <meta name="description" content="في هذه الصفحة الرئيسية لشركة النهر تستطيع ان تتعرف على الطلبيات الخاصة بك الواصة والراجعة والكثير من المعلومات">
    <meta name="الصفحة الرئيسية للعميل" property="og:title" content="معلومات متكاملة للعميل في هذه الصفحة خاصة بعملاء شركة النهر">
    <title>شركة النهر للحلول البرمجية</title>
    <link href="https://fonts.googleapis.com/css?family=Cairo:300,300i,400,400i,500,500i,700,700i,900,900i|Source+Sans+Pro:300,300i,400,400i,600,600i,700,700i,900,900i" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styles/style.css">
    <link rel="stylesheet" type="text/css" href="styles/framework.css">
    <link rel="stylesheet" type="text/css" href="styles/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="fonts/css/fontawesome-all.min.css">
    <link rel="stylesheet" type="text/css" href="styles/datapicker.css">

    <!-- load header -->
    <style type="text/css">
 #search{
   width: 30%;
   min-width:10px;
   margin-left: 0%;
   margin-right: 0%;
 }
 #start {
  width: 30%;
  margin-left: 1.3333%;
  margin-right: 0%;
  min-width:10px;
  border-bottom: #777777 solid 1px;
 }
 #end{
   width: 30%;
   margin-left:1.3333%;
   margin-right: 0%;
   min-width:10px;
   border-bottom: #777777 solid 1px;
 }
 #qrlink button{
   width:100%;
   background-color: #f96332;
   color:#F8F8FF;
   box-shadow: 0 5px 30px 0 rgba(0,0,0,.11),0 5px 15px 0 rgba(0,0,0,.08)!important;
 }

        .bg-green_ {
            background-color: #0F9D58;
        }

        .bg-blue_ {
            background-color: #4285F4;
            color: black;
        }

        .bg-yallow_ {
            background-color: #F4B400;
        }

        .bg-gray_ {
            background-color: #D3D3D3;
        }

        .bg-red_ {
            background-color: #DB4437;
        }

        .bg-carrot_ {
            background-color: #ED5E21 !important;
        }
    </style>
</head>

<body class="theme-light" data-background="none" data-highlight="blue2">

    <div id="page">

        <!-- load main header and footer -->
     <?php include_once("pre.php");?>
     <?php include_once("top-menu.php");?>
     <?php include_once("footer-menu.php");  ?>


        <div class="page-content header-clear-medium">

            <div class="content">
                <form id="searchForm">
                    <div class="search-box  shadow-large round-small bottom-10">
                        <i class="fa fa-search"></i>
                        <input type="text" name="search-text" placeholder="ابحث بواسطة الوصل، موبايل اواسم الزبون" aria-label="search">
                    </div>
                    <div class="clear">
                        <input type="text" name="start" aria-label="date start" id="start" class="datepicker" placeholder="من">
                        <input type="text" name="end" aria-label="date to" id="end" class="datepicker" placeholder="الى">
                        <button id="search" onclick="getorders('reload')" aria-label="search" class="shadow-large btn bg-blue_" type="button" value="">
                            بحث
                        </button>

                    </div>
                    <div class="top-5 bottom-5">
                      <a href="#" data-menu="sacnModal" id="qrlink">
                        <button id="searchbyQR" onclick="scanQR()" aria-label="search" class="btn" type="button" value="">
                             بحث من خلال QR
                        </button>
                      </a>
                    </div>
                </form>

                <!--<div class="content bottom-0 top-5 left-20 right-20">
<input type="hidden" name="currentPage" id="currentPage" value="1">

<ul class="gallery-filter-controls">
<li class="gallery-filter-active color-highlight gallery-filter-all" data-filter="all">الكل </li>
<li data-filter="1">بطريق</li>
<li data-filter="4">مستلم</li>
<li data-filter="5">رواجع</li>
<li data-filter="6">مؤجلات</li>
</ul>
</div>-->

            </div>


            <div class="content-boxed">
                <div class="content bottom-0">
                    <h3 class="bolder text-right">الطلبيات</h3>
                </div>


                <div id="orders"></div>

            </div>

            <!-- load footer -->
            <div id="footer-loader"></div>
        </div>
    <div id="sacnModal"
     class="menu  menu-box-bottom menu-box-detached round-medium"
     data-menu-height="500"
     data-menu-effect="menu-over">
     <div class="content">
            <h2 class="uppercase bold font-16 text-center top-20">البحث من خلال QR كود</h2>
            <p class="font-13 under-heading text-center bottom-20">ضع QR code مقابل الكامره</p>
            <video id="preview" style="width: 100%;height: 300px;"></video>
            <button onclick="close_qr()" class="button bg-carrot_ button-full button-l shadow-large button-round-small  top-20">اغلاق</button>
     </div>
    </div>
    </div>
    <div class="menu-hider"></div>

    <script type="text/javascript" src="scripts/jquery.js"></script>
    <script type="text/javascript" src="scripts/plugins.js"></script>
    <script type="text/javascript" src="scripts/instascan.min.js"></script>
    <script>
            var selectedCam;
            var scanner;
            function scanQR(){
              scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
              scanner.addListener('scan', function (content) {
                window.location.href = "orderDetails.php?o="+content
                console.log(content);
              });
              Instascan.Camera.getCameras().then(function (cameras) {
                if (cameras.length > 0) {
                    selectedCam = cameras[0];
                    $.each(cameras, (i, c) => {
                      if (c.name.indexOf('back') != -1) {
                          selectedCam = c;
                          return false;
                      }
                    });

                    scanner.start(selectedCam);
                } else {
                    console.error('No cameras found.');
                }

                console.log(cameras);
              }).catch(function (e) {
                console.error(e);
              });
              }
            function close_qr(){
              scanner.stop(selectedCam);
             $("#sacnModal").removeClass('menu-active');
             $(".menu-hider").removeClass('menu-active');

            }
        function getorders(action) {
            if (action == "reload") {
                $("#currentPage").val(1);
            }
            $.ajax({
                url: "php/_getOrders.php",
                type: "POST",
                data: $("#searchForm").serialize(),
                success: function(res) {
                    if (action == "reload") {
                        $("#orders").html('');
                    }
                    $("#loader").remove();
                    $("#loading-items").remove();
                    $("#currentPage").val(res.nextPage);

                    console.log(res);
                    $.each(res.data, function() {
                        if (this.order_status_id == 6) { //rejected
                            color = 'bg-red_';
                        } else if (this.order_status_id == 4) { //recieved
                            color = 'bg-green_';
                        } else if (this.order_status_id == 5) { // postponed
                            color = 'bg-carrot_';
                        } else if (this.order_status_id == 7) { // changed address
                            color = 'bg-yallow_';
                        } else if (this.order_status_id == 1) { //not recieved yes
                            color = 'bg-gray_';
                        } else {
                            color = 'bg-blue_'; //any thing ealse
                        }
                        $("#orders").append(
                            '<a href="orderDetails.php?o=' + this.id + '" >' +
                            '<div data-accordion="accordion-content-10" data-height="100" class="caption caption-margins round-tiny bottom-5" style="height: 120px;">' +
                            '<div class="caption-center">' +
                            '<h4 class="color-black center-text bottom-0 uppercase bolder">' + this.order_no + '</h4>' +
                            '<p class="color-black right-text right-10 bottom-0">' + this.customer_name + ' | ' + this.customer_phone + '</p>' +
                            '<p class="color-black right-text right-10 bottom-0">' + this.city + ' | ' + this.town + '</p>' +
                            '</div>' +
                            '<div class="filtr-item caption-overlay ' + color + ' opacity-80" data-category=' + this.order_status_id + '></div>' +
                            '<div class="caption-background "></div>' +
                            '</div>' +
                            '</a>'
                        );
                    });
                    if (res.pages > res.nextPage) {
                        $("#orders").append('<div id="loader" onclick="getorders(\'append\')" class="btn btn-link form-control color-black center-text top-10">تحميل المزيد</div>');
                        $("#orders").append('<div id="loading-items"></div>');
                    }
                },
                error: function(e) {
                    console.log(e);
                }
            });
        }
        $(document).ready(function() {
            $('#start').datepicker({
                format: 'yyyy-mm-dd'
            });
            $('#end').datepicker({
                format: 'yyyy-mm-dd'
            });
            getorders('reload');
        });
    </script>
    <script type="text/javascript" src="scripts/custom.js"></script>
    <script type="text/javascript" src="scripts/datapicker.js"></script>

    <script type="text/javascript" src="scripts/plugins.js"></script>
</body>

</html>