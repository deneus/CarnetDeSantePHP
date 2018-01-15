<?php

require __DIR__ . '/vendor/autoload.php';

use Cloutier\PhpIpfsApi\IPFS;
use HealthChain\modules\accessDelegation;
use HealthChain\modules\Home ;
use HealthChain\modules\newEntry;
use HealthChain\modules\Register;

$GLOBALS['ipfs'] = new IPFS("localhost", "8080", "5001");
$GLOBALS['entry_separator']  = '###';

// --------------------------------------------------
// Router.
if (!isset($_GET['q'])) {
    $_GET['q'] = 'home';
}
switch ($_GET['q']) {
    case 'newEntry':
        $page = new newEntry();
        break;

    case 'accessDelegation':
        $page = new accessDelegation();
        break;
    case 'login':
        //TODO APU
        break;
    case 'register':
        $page = new Register();
        break;
    case 'home':
    default;
        $page = new home();
        break;

}

$content = $page->outputHtmlContent();
$header = $page->outputHtmlHeader();



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

<html>
    <head>
        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css"
              integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
        <!-- Dynatable -->
        <link  rel="stylesheet" href="src/lib/dynatable/jquery.dynatable.css" §>
        <script type="application/javascript" src="src/lib/dynatable/vendor/jquery-1.7.2.min.js"></script>
        <script type="application/javascript" src="src/lib/dynatable/jquery.dynatable.js" ></script>
        <!-- dropzone -->
        <link  rel="stylesheet" href="src/layout/css/dropzone.css" §>
        <script src="src/layout/js/dropzone.js"></script>
        <!-- custom -->
        <link rel="stylesheet" href="src/layout/css/global.css" />
    </head>

<body>

    <?php echo $header; ?>

    <div class="row no-gutters ">
        <div class="col-10 offset-1">
        <?php echo $content; ?>
        </div>
    </div>

    <footer class="row bg-info pt-5 pb-5 text-right">
        <div class="col-10 offset-1 font-italic">
            <div>Proudly developed by deneus and Pug. </div>
            <div>Produced in, 2018.</div>
        </div>
    </footer>

</body>
</html>

