<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML//EN">
<html>
<head>
    <title>Chandika</title>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:300,600,800"/>
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="/css/style.css"/>
    <link rel="stylesheet" type="text/css" href="/css/jquery-ui.css"/>
    <style type="text/css">
    </style>
</head>
<body>
<script src="/js/jquery-2.2.0.min.js"></script>
<script src="/js/jquery-ui-1.11.4.js"></script>
<!-- Navigation -->
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header page-scroll">
            <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand page-scroll"
               href="index.php">Chandika</a> <?if (isset($_SESSION["user_email"])) {?><p class="navbar-brand">You are logged in as <?= $_SESSION["user_email"]?> | <a href="/login.php?logout=true">Logout</a></p><?}?>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li class="hidden">
                    <a href="#page-top"></a>
                </li>
                <li>
                    <a class="page-scroll" href="index.php">Home</a>
                </li>
                <li>
                    <a class="page-scroll" href="show_services.php">Services</a>
                </li>
                <li>
                    <a class="page-scroll" href="show_accounts.php">Accounts</a>
                </li>
                <li>
                    <a class="page-scroll" href="show_administrators.php">Admins</a>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>

