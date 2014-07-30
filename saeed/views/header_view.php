<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>
            <?php
            echo isset($data['title']) ? $data['title'] : "";
            ?>
        </title>
        <link rel="stylesheet" type="text/css" href="../css/template.css" />
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
        <link rel="stylesheet" type="text/css" href="../css/jQueryUI/smoothness/jquery-ui-1.9.2.custom.css" />
        <link rel="stylesheet" type="text/css" href="../css/Datatables/css/jquery.dataTables_themeroller.css" /> 
        <link rel="alternate" href="../services/feed.php" title="الأخبار" type="application/rss+xml" />
        <script type="text/javascript" src="../js/jquery-1.8.3.js"></script>
        <script type="text/javascript" src="../js/jquery-ui-1.9.2.custom.js"></script>
        <script type="text/javascript" src="../js/jquery.dataTables.js"></script>
        <script type="text/javascript" src="../js/template/jquery.dropotron-1.0.js"></script>
        <script>
            $(function() {
                $('#menu > ul').dropotron({
                    mode: 'fade',
                    globalOffsetY: 11,
                    offsetY: -15
                });
            });
        </script>
    </head>
    <body>   
        <div id="wrapper">
            <div id="header">
                <div id="logo">
                    <h1><a href="../controllers/IndexPage.php">ساعد للتأمين</a></h1>
                </div>
            </div>
            <div id="menu">
                <ul>
                    <li class="first"><a href="../controllers/IndexPage.php">الصفحة الرئيسية</a></li>
                    <?php
                    include_once '../helpers/helper.php';
                    if (helper::userLoggedIn() > 0) {
                        $userPermessionsArray = $_SESSION['userPermissionsArray'];
                        echo '<li><a href="../controllers/Login.php?function=logout">تسجيل الخروج</a></li>';
                        echo '<li class="first">';
                        echo '<span class="opener">لوحة التحكم<b></b></span>';
                        echo '<ul>';
                        if (isset($userPermessionsArray['manageUsers']) && $userPermessionsArray['manageUsers']) {
                            echo '<li><a href="../controllers/ManageUsers.php">ادارة المستخدمين</a></li>';
                        }
                        if (isset($userPermessionsArray['managePatients']) && $userPermessionsArray['managePatients']) {
                            echo '<li><a href="../controllers/ManagePatients.php">ادارة المرضى</a></li>';
                        }
                        if (isset($userPermessionsArray['manageCompanies']) && $userPermessionsArray['manageCompanies']) {
                            echo '<li><a href="../controllers/ManageCompanies.php">إدارة الشركات</a></li>';
                        }
                        if (isset($userPermessionsArray['manageNews']) && $userPermessionsArray['manageNews']) {
                            echo '<li><a href="../controllers/ManageNews.php">إدارة الأخبار</a></li>';
                        }
                        if (isset($userPermessionsArray['manageRoles']) && $userPermessionsArray['manageRoles']) {
                            echo '<li><a href="../controllers/ManageRoles.php">إدارة الأدوار</a></li>';
                        }
                        if (isset($userPermessionsArray['manageConfig']) && $userPermessionsArray['manageConfig']) {
                            echo '<li><a href="../controllers/ManageConfig.php">ادارة اعدادات النظام</a></li>';
                        }
                        if (isset($userPermessionsArray['browseStatistics']) && $userPermessionsArray['browseStatistics']) {
                            echo '<li><a href="../controllers/BrowseStatistics.php">استعراض الاحصائيات</a></li>';
                        }
                        if (isset($userPermessionsArray['browsePaymentsForPatient']) && $userPermessionsArray['browsePaymentsForPatient']) {
                            echo '<li><a href="../controllers/BrowsePaymentsForPatient.php">استعراض الدفعات الخاصة بمريض</a></li>';
                        }
                        if ($_SESSION['type'] == 'طبيب') {
                            echo '<li><a href="../controllers/DoctorPanel.php">لوحة تحكم الطبيب</a></li>';
                        }
                        if ($_SESSION['type'] == 'صيدلية') {
                            echo '<li><a href="../controllers/PharmacyPanel.php">لوحة تحكم الصيدلية</a></li>';
                        }
                        if ($_SESSION['type'] == 'مشفى') {
                            echo '<li><a href="../controllers/HospitalPanel.php">لوحة تحكم المشفى</a></li>';
                        }
                        echo '<li><a href="../controllers/Profile.php">الملف الشخصي</a></li>';
                        echo '</ul>';
                        echo '</li>';
                    } else {
                        echo '<li><a href="../controllers/Login.php">تسجيل الدخول</a></li>';
                    }
                    ?>    
                    <li><a href="../controllers/BrowseCenters.php">المراكز المشتركة</a></li>
                    <li><a href="../controllers/Laws.php">قوانين الشركة</a></li>
                    <li class="last"><a href="../controllers/Contact.php">اتصل بنا</a></li>
                </ul>
                <br class="clearfix" />                
            </div>

            <?php if (isset($data['viewSlider'])) { ?>
                <script type="text/javascript" src="../js/template/jquery.slidertron-1.1.js"></script>
                <script type="text/javascript">
                    $(function() {
                        $('#slider').slidertron({
                            viewerSelector: '.viewer',
                            indicatorSelector: '.indicator span',
                            reelSelector: '.reel',
                            slidesSelector: '.slide',
                            speed: 'slow',
                            advanceDelay: 4000
                        });
                    });
                </script>
                <div id="slider">
                    <div class="viewer">
                        <div class="reel">
                            <div class="slide">
                                <img src="../images/slide01.jpg" alt="" />
                            </div>
                            <div class="slide">
                                <img src="../images/slide02.jpg" alt="" />
                            </div>
                            <div class="slide">
                                <img src="../images/slide03.jpg" alt="" />
                            </div>
                            <div class="slide">
                                <img src="../images/slide04.jpg" alt="" />
                            </div>
                            <div class="slide">
                                <img src="../images/slide05.jpg" alt="" />
                            </div>
                        </div>
                    </div>
                    <div class="indicator">
                        <span>1</span>
                        <span>2</span>
                        <span>3</span>
                        <span>4</span>
                        <span>5</span>
                    </div>
                </div>
            <?php } ?>
            <div id="page">