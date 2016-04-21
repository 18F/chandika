<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML//EN">
<html>
<head>
    <title>Chandika</title>
    <link href='//fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <link href='//s3.amazonaws.com/myusa-static/button.min.css' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="/css/style.css"/>
</head>
<body>
<div style="vertical-align: middle;text-align: center;">
    <h1>Log in to Chandika</h1>
    <p><a href="<?=getenv("CHANDIKA_OAUTH_URL")?>users/sign_in?client_id=<?=getenv("CHANDIKA_OAUTH_CLIENT_ID")?>/" class="btn btn-social btn-myusa">Connect with MyUSA</a></p>
</div>
</body>
</html>