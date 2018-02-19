<?php

use PhpIpfs\Ipfs;

session_start();

require __DIR__ . '/vendor/autoload.php';

$json = json_encode($_SESSION['user']['master']);
//$hash = $this->ipfs->add($json);

$filePath = 'src\test\master.json';

$hash = Ipfs::add($filePath);
// $hash['Name']
// $hash['Hash']
// $hash['Size']

$cmdGet = Ipfs::get($hash['Hash']);
$cmdLs = Ipfs::ls($hash['Hash']);
// Lis le hash.
$cmdCat = Ipfs::cat($hash['Hash']);
$cmdBlockStat = Ipfs::blockStat($hash['Hash']);
// Ca marche pas: le block est pinned
$cmdBlockRm = Ipfs::blockRm(TRUE, $hash['Hash']);
if (isset($cmdBlockRm['Error']) && $cmdBlockRm['Error'] === 'pinned: recursive') {
    // Remove pin.
    $cmdPinRm = Ipfs::pinRm($hash['Hash']);
    // Try to delete the block again.
    $cmdBlockRm2 = Ipfs::blockRm(TRUE, $hash['Hash']);

    // IF I TRY TO READ THE HASH AGAIN, I CRASH IPFS
    // $cmdCat2 = Ipfs::cat($hash['Hash']);
}



$t = 1;

