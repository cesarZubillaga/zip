<?php
/**
 * Created by PhpStorm.
 * User: cesar
 * Date: 19/01/2017
 * Time: 09:22
 */
$sZip = (isset($_GET['zip'])) ? $_GET['zip'] : false;
$scan = scandir('./');
$dontShow = array('index.php', '.', '..', '.idea', '.git');
$scan = array_map(
    function ($el) use ($dontShow) {
        if (false == in_array($el, $dontShow)) {
            return $el;
        }
    },
    $scan
);
$scan = array_filter($scan);
$folderImages = array_filter(
    $scan,
    function ($el) {
        if (strpos($el, '.')) {
            return $el;
        }
    }
);
$folders = array_diff($scan, $folderImages);
$bMessage = false;
switch ($sZip) {
    case 'folders':
        $bMessage = true;
        break;
    case 'images':
        $bMessage = true;
        break;
    case 'all':
        $bMessage = true;
        break;
    default:
        if (in_array($sZip, $scan)) {
            if (strpos($sZip, '.jpg')) {
                $zipname = 'output_image.zip';
                $zipArchive = new \ZipArchive();
                $zipArchive->open($zipname, \ZipArchive::OVERWRITE);
                $zipArchive->addFile('./'.$sZip, $sZip);
                $zipArchive->close();
                header('Content-Type: application/zip');
                header('Content-disposition: attachment; filename='.$zipname);
                header('Content-Length: ' . filesize($zipname));
                readfile($zipname);
            }
        }
        break;
}
$message = ($sZip && $bMessage) ? sprintf('You have zipped %s', ucfirst($sZip)) : '';
?>
<DOCTYPE html>
    <html>
    <head>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"
              integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
              crossorigin="anonymous">
    </head>
    <body>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1>ZipArchive PHP</h1>
                <a href="http://php.net/manual/en/class.ziparchive.php">DOCS</a>
            </div>
        </div>
        <?php if ($message): ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-success">
                        <p>
                            <?php echo $message; ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-lg-12">
                <h4>What do you want to ZIP?</h4>
                <a href="?zip=folders">Folders</a>
                <a href="?zip=images">Images</a>
                <a href="?zip=all">Folder & Images</a>
            </div>
            <div class="col-lg-12">
                <h4>Select what you want to ZIP</h4>
                <div class="col-lg-6">
                    <ul>
                        <?php foreach ($folders as $folder): ?>
                            <li>
                                <a href="?zip=<?php print($folder); ?>"><?php print($folder); ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <ul>
                        <?php foreach ($folderImages as $image): ?>
                            <li>
                                <a href="?zip=<?php print($image); ?>"><?php print($image); ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </body>
    </div>
    </html>
</DOCTYPE>
