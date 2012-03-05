<?php

$fileCount = '/r/bcb/genecentric/downloads-linux';
$downloads = file_get_contents($fileCount);
file_put_contents($fileCount, $downloads + 1);

header('Location: files/genecentric-1.0.0.tar.gz');

?>
