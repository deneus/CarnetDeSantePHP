<?php

session_start();

date_default_timezone_set('Australia/Sydney');

require __DIR__ . '/vendor/autoload.php';

use Cloutier\PhpIpfsApi\IPFS;
use HealthChain\modules\classes\Encryption;
use HealthChain\modules\classes\Neo\Contract;
use HealthChain\modules\classes\Neo\NeoAPI;
use HealthChain\modules\classes\User;
use HealthChain\modules\pages\accessDelegation;
use HealthChain\modules\pages\Home ;
use HealthChain\modules\pages\NewRecord;
use HealthChain\modules\pages\Register;
use HealthChain\modules\pages\Login;
use HealthChain\modules\pages\Logout;

$GLOBALS['ipfs'] = new IPFS("localhost", "8080", "5001");
$GLOBALS['instance_id'] = 'a831rwxi1a3gzaorw1w2z49dlsor';

$protocol = empty($_SERVER['HTTPS']) ? 'http' : 'https';
$domain = $_SERVER['SERVER_NAME'];
$port = $_SERVER['SERVER_PORT'];
$GLOBALS['directory'] = "${protocol}://${domain}";
$GLOBALS['mainnet'] = false; // Mainnet = prod, testnet = dev
$GLOBALS['version'] = "2.6.0";

function storeIntoBlockchain($hash, $address)
{
    $params = array('hash' => Contract::CONTRACT_HASH,
        'NEOaddress' => $address,
        'ipfsMaster' => $hash);

    $result = NeoAPI::call(User::NEO_METHOD_REGMASTER, NeoAPI::METHOD_POST,
        $params);
}

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
    // Process autologin from QrCode.
    if (substr($query, 0, 6)) {
        $explode = explode('__', $query);
        $query = $explode[0];
    }
    if (!in_array($query,$availablePages) ) {
        header('Location: '.$directory.'/');
    }
}

switch ($query) {
    // Not logged pages.
    case 'login':
        unset($_SESSION['user']);
        unset($_SESSION['uploaded_file']);
        $page = new Login();
        break;
    case 'loginPost':
        $login = new Login();
        $loginStatus = $login->loginPost($_POST);
        if ($loginStatus) {
            header('Location: '.$directory.'/');
        }
        else {
            // @todo anthony: please update that as well.
            header('Location: '.$directory.'/?q=login&error=1');
        }

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
        unset($_SESSION['uploaded_file']);
        header('Location: '.$directory.'/');
    case 'newRecord':
        $page = new NewRecord();
        break;
    case 'accessDelegation':
        $page = new AccessDelegation();
        break;
    case 'terminateAccess':
        break;
    case 'home':
        $page = new Home();
    default;
        break;
}

$title = $page->outputTitle();
$cssClass = $page->cssClassForContent();
$cssClassForBanner = $page->cssClassForBanner();
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
    <link rel="stylesheet" href="src/layout/css/anonymous.css" />
    <link rel="stylesheet" href="src/layout/css/global_v2.css" />
</head>

<body class="page-<?php echo strtolower($title); ?>">

<div canvas="container" class="overflow-x-hidden  <?php echo $cssClass ?>">
    <?php if ($userLoggedIn): ?>
    <!-- header -->
    <header>
        <div class="row no-gutters bg-light-grey">
            <div class="col-12 col-sm-7 col-lg-8 pt-2 col-xl-9 h-45">
                <span class="ml-3">Your health booklet</span>
            </div>
            <div class="col-12 col-sm-5 col-lg-4 col-xl-3 pt-2 bg-info text-white h-45">
                <span class="ml-3"><i class="fa fa-user mr-3"></i><?php echo $_SESSION['user']['master']->fullName; ?></span>
            </div>
        </div>


        <div class="row no-gutters main-menu pt-3 pb-3">
            <!-- menu from sm to xl -->
            <ul class="margin-0-auto pl-0 d-none d-sm-flex">
                <li class="text-center mx-4">
                    <a class="main-color" href="<?php echo $directory; ?>/home.html"><i class="fa fa-home fa-3x"></i><br />Home</a>
                </li>
                <li class="text-center mx-4">
                    <a class="main-color" href="<?php echo $directory; ?>/newRecord.html"><i class="fa fa-plus fa-3x"></i><br />New record</a>
                </li>
                <?php if(User::isUserDoctor()): ?>
                    <li class="text-center mx-4 text-danger">
                        <a href="<?php echo $directory; ?>/terminateAccess.html"><i class="fa fa-user-md fa-3x"></i><br />Terminate access</a>
                    </li>
                <?php else : ?>
                    <li class="text-center mx-4">
                        <a class="main-color" href="<?php echo $directory; ?>/accessDelegation.html"><i class="fa fa-user-md fa-3x"></i><br />Access delegation</a>
                    </li>
                <?php endif; ?>
                <li class="text-center mx-sm-4">
                    <a class="main-color" href="<?php echo $directory; ?>/logout.html"><i class="fa fa-sign-out-alt fa-3x"></i><br />Sign out</a>
                </li>
            </ul>
            <!-- menu from sm to xl -->
            <!-- menu below sm to -->
            <ul class="d-block d-sm-none w-100 pl-0 list-group">
                <li class="list-group-item text-left d-block">
                    <a class="main-color pl-4" href="<?php echo $directory; ?>/home.html"><i class="fa fa-home fa-2x mr-4"></i>Home</a>
                </li>
                <li class="list-group-item text-left d-block">
                    <a class="main-color pl-4" href="<?php echo $directory; ?>/newRecord.html"><i class="fa fa-plus fa-2x mr-4"></i>New record</a>
                </li>
                <?php if(User::isUserDoctor()): ?>
                    <li class="list-group-item text-left d-block text-danger">
                        <a class=" pl-4" href="<?php echo $directory; ?>/terminateAccess.html"><i class="fa fa-user-md fa-2x mr-4"></i>Terminate access</a>
                    </li>
                <?php else : ?>
                    <li class="list-group-item text-left d-block">
                        <a class="main-color pl-4" href="<?php echo $directory; ?>/accessDelegation.html"><i class="fa fa-user-md fa-2x mr-4"></i>Access delegation</a>
                    </li>
                <?php endif; ?>
                <li class="list-group-item text-left d-block">
                    <a class="main-color pl-4" href="<?php echo $directory; ?>/logout.html"><i class="fa fa-sign-out-alt fa-2x mr-4"></i>Sign out</a>
                </li>
            </ul>
            <!-- menu below sm to -->
        </div>

        <div class="row no-gutters h-200 bg-banner <?php echo $cssClassForBanner; ?>">
            <div class="col-12 text-white">
                <div class="col-11 offset-0 mt-2"><a class="text-white" href="<?php echo $directory; ?>/dashboard.html"><i class="fa fa-long-arrow-alt-left mr-2"></i>Dashboard</a></div>
                <h1 class="col-10 offset-1 pl-0 mt-4"><?php echo $title; ?></h1>
            </div>
        </div>

    </header>
    <!-- end: header -->
    <?php endif ?>

    <!-- content -->
    <div class="row no-gutters pt-4 pb-4">
        <div class="col-10 offset-1">
            <?php echo $content; ?>
        </div>
    </div>
    <!-- end: content -->

    <!-- footer -->
    <footer class="row bg-info pt-3 pb-3 no-gutters small text-white">
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

<!--
    L1JQqSX4M1HS9nY3nMHs3w2DPzbMxcLo2dwXfk584jJx7GqnUMCM
-->