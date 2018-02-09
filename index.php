<?php

session_start();

require __DIR__ . '/vendor/autoload.php';

use Cloutier\PhpIpfsApi\IPFS;
use HealthChain\modules\pages\accessDelegation;
use HealthChain\modules\pages\Home ;
use HealthChain\modules\pages\NewEntry;
use HealthChain\modules\pages\Register;
use HealthChain\modules\pages\Login;
use HealthChain\modules\pages\Logout;

$GLOBALS['ipfs'] = new IPFS("localhost", "8080", "5001");
$GLOBALS['instance_id'] = 'a831rwxi1a3gzaorw1w2z49dlsor';
$GLOBALS['directory'] = '/HealthChainPHP';

// --------------------------------------------------
// Router.
$query = '';
if (isset($_GET['q'])) {
    $query = htmlspecialchars($_GET['q'], ENT_QUOTES);
}

if (isset($_SESSION['user'])) {
    $userLoggedIn = TRUE;
    if ($query === '') {
        $query = 'home';
    }
}
else {
    $userLoggedIn = FALSE;
    $availablePages = [
        'login',
        'register',
        'registerPost',
        'loginPost',
    ];
    if ($query === '') {
        $query = 'login';
    }
    if (!in_array($query,$availablePages) ) {
        header('Location: '.$directory.'/');
    }
}

switch ($query) {
    // Not logged pages.
    case 'login':
        $page = new Login();
        break;
    case 'loginPost':
        $login = new Login();
        $login->loginPost($_POST);
        header('Location: '.$directory.'/');
    break;
    case 'register':
        $page = new Register(Register::ACTION_DISPLAY_FORM);
        break;
    case 'registerPost':
        $page = new Register(Register::ACTION_SUBMIT_FORM);
        break;

    // Logged pages.
    case 'logout':
        unset($_SESSION['user']);
        header('Location: '.$directory.'/');
    case 'newEntry':
        $page = new NewEntry();
        break;
    case 'accessDelegation':
        $page = new AccessDelegation();
        break;
        break;
    case 'home':
        $page = new Home();
    default;
        break;
}

$title = $page->outputTitle();
$cssClass = $page->cssClassForContent();
$content = $page->outputHtmlContent();

/*
// PRE REQUIS
// run > ipfs daemon (https://ipfs.io/docs/getting-started/)
// web interface http://localhost:5001/webui

// https://github.com/cloutier/php-ipfs-api
// connect to ipfs daemon API server
$ipfs = new IPFS("localhost", "8080", "5001"); // leaving out the arguments will default to these values

// Adds content to IPFS.
$hash = $ipfs->add("Hello world");
echo "hash = {$hash} <br /><br />";
// OUTPUT: hash = QmS9JQfRcJbKKF7iQCF7nuHwSvbwJ1BY6Axiyd64ERecY1


// Retrieves the contents of a single hash.
$content = $ipfs->cat($hash);
echo "content = {$content} <br /><br />";
// OUTPUT: content = Hello world a831rwxi1a3gzaorw1w2z49dlsor

// Returns object size.
$size = $ipfs->size($hash);
echo "size = {$size} <br /><br />";
// OUTPUT: size = 51


// ------------------------------------------------
// A PARTIR DE LA, CA MARCHE PAS.

// OBJ est vide.
$obj = $ipfs->ls($hash);
print_r($obj);

foreach ($obj as $e) {
    print_r($e);
    echo $e['Hash'];
    echo $e['Size'];
    echo $e['Name'];
}

// LA METHOD id() N'EXISTE PAS
// print_r($ipfs->id());
*/

?>

<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $title; ?> | Your Health Booklet</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css"
              integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
        <!-- Dynatable -->
        <link  rel="stylesheet" href="src/lib/dynatable/jquery.dynatable.css" />
        <!-- dropzone -->
        <link  rel="stylesheet" href="src/layout/css/dropzone.css" />
        <!-- menu -->
        <link rel="stylesheet" href="src/layout/css/slidebars.css" />
        <link rel="stylesheet" href="src/layout/css/jquery.mmenu.all.css" />
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
        <link rel="stylesheet" href="/resources/demos/style.css" />
        <!-- custom -->
        <link rel="stylesheet" href="src/layout/css/global.css" />
    </head>

<body class="page-<?php echo strtolower($title); ?>">

    <div canvas="container" class="overflow-x-hidden  <?php echo $cssClass ?>">
        <!-- header -->
        <div class="text-white bg-info pt-2 pb-2">
            <?php if ($userLoggedIn): ?>
            <div class="ml-3 float-left">
                <div class="open-menu"><i class="fa fa-bars fa-3x"></i></div>
                <div class="close-menu"><i class="fa fa-times fa-3x"></i></div>
            </div>
            <?php endif ?>
            <div style="text-align: center">
                <h1 class="font-weight-bold">Your Health Booklet!</h1>
            </div>
        </div>
        <!-- end: header -->

        <!-- content -->
        <div class="row no-gutters pt-5 pb-5">
            <div class="col-10 offset-1">
                <?php echo $content; ?>
            </div>
        </div>
        <!-- end: content -->

        <!-- footer -->
        <footer class="row bg-info pt-3 pb-3 no-gutters small">
            <?php if ($userLoggedIn) : ?>
                <div class="col-10 offset-1 text-right">
                    <div>Proudly developed by deneus and Pug. </i></div>
                    <div><i class="far fa-copyright"></i> 2018 All right reserved</div>
                </div>
            <?php else : ?>
                <div class="col-10 offset-1 text-center">
                    <div class="margin-0-auto col-md-8 col-lg-6">
                        <div>Proudly developed by deneus and Pug. </i></div>
                        <div><i class="far fa-copyright"></i> 2018 All right reserved</div>
                    </div>
                </div>
            <?php endif ?>


        </footer>
        <!-- end footer -->
    </div>

    <?php if ($userLoggedIn): ?>
    <!-- navigation -->
    <nav off-canvas="main-menu left shift" id="menu" class="mm-menu mm-menu_offcanvas mm-menu_opened">
        <div class="mm-panels">
            <div id="panel-menu" class="mm-panel mm-panel_opened">
                <div class="">
                    <img src="src/layout/images/logo.png" />
                </div>

                <ul class="mm-listview">
                    <li class="mm-listitem"><a href="<?php echo $directory; ?>/home.html"><i class="fa fa-home mr-3"></i>Home</a></li>
                    <li class="mm-listitem"><a href="<?php echo $directory; ?>/newEntry.html"><i class="fa fa-plus mr-3"></i>New entry</a></li>
                    <li class="mm-listitem"><a href="<?php echo $directory; ?>/?q=accessDelegation"><i class="fa fa-user-md mr-3"></i>Access delegation</a></li>
                    <li class="mm-listitem"><a href="<?php echo $directory; ?>/logout.html"><i class="fa fa-sign-out-alt mr-3"></i>Sign out</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- end: navigation -->
    <?php endif ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="src/lib/dynatable/jquery.dynatable.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="src/layout/js/lib/dropzone.js"></script>
    <script src="src/layout/js/lib/clipboard.js"></script>
    <script src="src/layout/js/lib/jquery.easing.1.3.js"></script>
    <script src="src/layout/js/lib/slidebars.js"></script>
    <script src="src/layout/js/scripts.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>

</body>
</html>
