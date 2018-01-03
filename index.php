<?php

require __DIR__ . '/vendor/autoload.php';

use Cloutier\PhpIpfsApi\IPFS;

// PRE REQUIS
// run > ipfs daemon (https://ipfs.io/docs/getting-started/)
// web interface http://localhost:5001/webui

// https://github.com/cloutier/php-ipfs-api
// connect to ipfs daemon API server
$ipfs = new IPFS("localhost", "8080", "5001"); // leaving out the arguments will default to these values

// Adds content to IPFS.
$hash = $ipfs->add("Hello world");
echo "hash = {$hash} <br /><br />";

// Retrieves the contents of a single hash.
$content = $ipfs->cat($hash);
echo "content = {$content} <br /><br />";

// Returns object size.
$size = $ipfs->size($hash);
echo "size = {$size} <br /><br />";


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


?>