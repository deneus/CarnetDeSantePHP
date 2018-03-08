<?php

session_start();

require __DIR__ . '/vendor/autoload.php';

use Cloutier\PhpIpfsApi\IPFS;

if (!isset($_GET['hash'])) {
    echo '<h1>There is nothing to see here.</h1>';
}

// @todo : How do we know the file type ?
// For now I will limit the file type to JPG.
$mimeType = NULL;
foreach ($_SESSION['user']['master']->records as $record) {
    foreach ($record->attachments as $attachment) {
        if ($attachment->hash === $_GET['hash']) {
            $mimeType = $attachment->mimetype;
        }
    }
}

if ($mimeType === NULL) {
    echo '<h2>An error occurred.</h2>';
}
else {

    // Read the hash.
    $ipfs = new IPFS("localhost", "8080", "5001");
    $binaries = $ipfs->cat($_GET['hash']);

    // Transform the binary stream into an image.
    $fileData = base64_encode($binaries);

    switch ($mimeType) {
        case 'image/jpeg':
            $src = 'data: image/jpg;base64,'.$fileData;
            $output =  '<img src="' . $src . '">';
            echo $output;
            break;

        case 'application/pdf':
            header('Content-type: application/pdf');
            header('Content-Disposition: inline;');
            echo $binaries;
            break;

        default:
            $output = '';
            break;
    }

}


