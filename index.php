<?php

//this is an extra check to prevent file structure climbing
$AllowedRoutes = [
    'blog',
    'events',
    'media',
    'resources',
    'sermons',
    'sermons_old',
    'stat'
];


//Get url without parms
$full_url = explode('?', $_SERVER['REQUEST_URI']);
//Split into Array
$Routes =  explode('/', $full_url[0]);
//Remove first item of array to account for initial /
array_shift($Routes);
$fileUrl = (!empty($Routes[0]) ? './Views/'.$Routes[0].'.html' : "");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Welcome to Goodlow EFC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="Welcome to the Goodlow Efree Church">
    <meta http-equiv="Cache-Control" content="max-age=3600"> <!-- Cache for 1 hour -->

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="shortcut icon" type="image/png" href="/MediaFiles/favicon.png"/>
    <link rel="stylesheet" type="text/css" href="/css/table.css">
    <link rel="stylesheet" type="text/css" href="/css/main.css">
    <script src='/js/SermonGridLoad.js'></script>
    <script src='/js/main.js'></script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-60957304-1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-60957304-1');
    </script>

    </head>
    <body>
    <div itemscope itemtype="https://schema.org/WebSite">
        <meta itemprop="url" content="https://goodlowchurch.ca/"/>
        <meta itemprop="name" content="Goodlow EFree Church"/>
    </div>
        <?php
            include_once('Build/header.php');
        ?>
        <div class="content">

            <?php

            if(empty($Routes[0])){
                include('./Views/home.html');
            }elseif(is_file($fileUrl) and in_array($Routes[0], $AllowedRoutes)){
                include($fileUrl);
            }else{
                include('./404.html');
            }

            ?>
    
        </div>

        <?php
            include_once('Build/footer.php');
        ?>
    </body>
    
    <script src='/js/menu.js'></script>
    <script>
        GetBulletin();
    </script>
</html>