<?php

require __DIR__ . '/vendor/autoload.php';

use Cloutier\PhpIpfsApi\IPFS;

if (!isset($_GET['hash'])) {
    echo '<h1>There is nothing to see here.</h1>';
}

// @todo : How do we know the file type ?
// For now I will limit the file type to JPG.

// Read the hash.
$ipfs = new IPFS("localhost", "8080", "5001");
$binaries = $ipfs->cat($_GET['hash']);

// Transform the binary stream into an image.
$imageData = base64_encode($binaries);

// Format the image SRC:  data:{mime};base64,{data};
$src = 'data: image/jpg;base64,'.$imageData;

// Echo out a sample image
echo '<img src="' . $src . '">';
