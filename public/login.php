<?
if (isset($_REQUEST["logout"])) {
    session_start();
    unset($_SESSION["user_email"]);
}
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML//EN">
<html>
<head>
    <title>Chandika</title>
    <link href='//fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/css/style.css"/>
</head>
<body>
<div style="vertical-align: middle;text-align: center;">
    <h1>Log in to Chandika</h1>
    <p><a href="https://github.com/login/oauth/authorize?scope=user%25email,user%25company&client_id=<?=getenv("CHANDIKA_OAUTH_CLIENT_ID")?>" class="btn-social">Sign in with GitHub</a></p>
</div>
</body>
</html>