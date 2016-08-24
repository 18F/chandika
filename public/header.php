<!DOCTYPE html>
<html lang="en">
<head>
    <title>Chandika</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
<script src="/js/bootstrap.min.js"></script>
<!-- Navigation -->
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand"
               href="index.php">Chandika</a>
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
                    <a class="page-scroll" href="show_services.php">Systems</a>
                </li>
                <li>
                    <a class="page-scroll" href="show_billing.php">Billing</a>
                </li>
                <? if ($auth->belongsTo(Authenticator::administrator)) {?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Manage
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="page-scroll" href="show_accounts.php">Accounts</a>
                            </li>
                            <li>
                                <a class="page-scroll" href="show_administrators.php">Admins</a>
                            </li>
                            <li>
                                <a class="page-scroll" href="show_api_keys.php">API keys</a>
                            </li>
                        </ul>
                    </li>
                <?}?>
                <? if (isset($_SESSION["user_email"])) { ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"><?= $_SESSION["user_email"]?>
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="/admin/login.php?logout=true">Sign out</a></li>
                        </ul>
                    </li>
                <?}?>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>

