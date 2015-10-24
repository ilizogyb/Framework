<?php

$activeIfRoute = function ($item) use (&$route) {
    return $route['_name'] === $item?'class="active"':'';
};
?>
<!DOCTYPE html>
<html lang="en-us">

<head>
    <title>Education</title>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link href="/css/bootstrap.min.css" type="text/css" rel="stylesheet"/>
    <link href="/css/bootstrap-theme.min.css" type="text/css" rel="stylesheet"/>
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css" rel="stylesheet">

    <link href='//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700' rel='stylesheet' type='text/css'>
    <link href="/css/theme.css" rel="stylesheet">

</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="http://mindk.com"><img class="brand-logo" src="/images/img-logo-mindk-white.png"
                                                                 alt="Education"></a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li <?php echo $activeIfRoute('home') ?>><a href="<?php echo $getRoute('home')?>">Home</a></li>
                <li <?php echo $activeIfRoute('add_post') ?>><a href="<?php echo $getRoute('add_post')?>">Add Post</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php if (is_null($user)) { ?>
                    <li <?php echo $activeIfRoute('signin') ?>><a href="<?php echo $getRoute('signin')?>">Sign in</a></li>
                    <li <?php echo $activeIfRoute('login') ?>><a href="<?php echo $getRoute('login')?>">Login</a></li>
                <?php } else { ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="glyphicon glyphicon-user"></i>
                            <?php echo 'Hello, '.$user->email ?> <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li <?php echo $activeIfRoute('profile') ?>><a href="<?php echo $getRoute('profile')?>">Profile</a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo $getRoute('logout')?>">Logout</a></li>
                        </ul>
                    </li>

                <?php } ?>
            </ul>
        </div>
    </div>
</div>
<div class="container theme-showcase" role="main">
    <div class="row">
    
        <div role="alert" class="alert alert-danger">
            <strong><?php echo "Error $code: "?></strong> <?php echo $message ?>
        </div>
        <div>
            <?php if (isset($image)) {
                    echo '<img class="img-thumbnail error-image" src="/images/' . $image .'" alt="Education" >';
            }?>
        </div>
    </div>
</div>
</body>
</html>