<?php

if (!isset($_GET['action'])) {
    die;
}

$action = $_GET['action'];

switch ($action) {
    case 'download':

        if (!isset($_GET['file'])) {
            die;
        }

        $file = $_GET['file'];
        $fileName = end(explode('----', $file));

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $fileName);
        header('Content-Transfer-Encoding: binary');
        header('Connection: Keep-Alive');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');

        readfile('storage' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $file);

        unlink('storage' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $file);

        break;
    case 'upload':

        $file = reset($_FILES);

        $newFileName = round(microtime(true) * 1000) . '----' . $file['name'];
        $pathFile = 'storage' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $newFileName;

        $moved = move_uploaded_file(
            $file['tmp_name'],
            $pathFile
        );

        if (!$moved) {
            die;
        }

        chmod($pathFile, 0777);

        die($newFileName);

        break;
    default:
        die;
}