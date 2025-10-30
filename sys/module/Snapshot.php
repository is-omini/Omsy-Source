<?php
class Snapshot {
	// Temp ????
	// 40% ChatGPT ????

    public $CMS;
    private $config;
    private $devMode = false;
    private $updateAdress;

    public function getDevMode() { return $this->devMode; }

    function __construct($CMS) {
    	$this->CMS = $CMS;
    	$this->config = $this->CMS->Config;

    	if($this->config->server->update == 'publish-dev/') {
    		$this->devMode = true;
    		$this->config->server->update = __root__ . $this->config->server->update;
    		$this->updateAdress = 'https://static.floagg.com/publish-dev/'; //$this->config->server->update;
    	}
    }

    private function zipFolder($source, $destination, $fileList = []) {
	    if (!extension_loaded('zip')) {
	        die("L'extension ZIP n'est pas activée sur votre serveur.");
	    }
	    
	    if (!file_exists($source)) {
	        die("Le dossier source n'existe pas.");
	    }
	    
	    $zip = new ZipArchive();
	    if ($zip->open($destination, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
	        die("Impossible de créer le fichier ZIP.");
	    }
	    
	    $source = realpath($source);
	    
	    foreach ($fileList as $file) {
	        $filePath = realpath($source . DIRECTORY_SEPARATOR . $file);
	        if ($filePath && file_exists($filePath)) {
	            if (is_dir($filePath)) {
	                $zip->addEmptyDir($file);
	                $files = new RecursiveIteratorIterator(
	                    new RecursiveDirectoryIterator($filePath, RecursiveDirectoryIterator::SKIP_DOTS),
	                    RecursiveIteratorIterator::SELF_FIRST
	                );
	                foreach ($files as $subFile) {
	                    $subFilePath = realpath($subFile);
	                    $relativePath = $file . DIRECTORY_SEPARATOR . substr($subFilePath, strlen($filePath) + 1);
	                    if (is_dir($subFilePath)) {
	                        $zip->addEmptyDir($relativePath);
	                    } else {
	                        $zip->addFile($subFilePath, $relativePath);
	                    }
	                }
	            } else {
	                $zip->addFile($filePath, $file);
	            }
	        }
	    }
	    
	    $zip->close();
	    echo "Dossier compressé avec succès en $destination";
	}

    public function createSnapshot($sourceFolder, $fileName) {
    	if(!$this->devMode) die("Not dev-mode !");

    	$fileUpdatorDepot = $this->config->server->update.'manifest.json';
    	$mani = json_decode(file_get_contents($fileUpdatorDepot), true);

		$time = time();
		$filename = "shapshot-$fileName-$time.zip";

		// Exemple d'utilisation :
		//$sourceFolder = __root__ . "$folder/";  // Remplacez par le chemin du dossier à compresser
		$zipFile = $this->config->server->update.$filename;       // Nom du fichier ZIP de sortie
		$this->zipFolder(__root__, $zipFile, $sourceFolder);

		$newArray = [
			"name" => $filename,
			"download" => $this->updateAdress.$filename,
			"time" => $time
		];
		$mani[] = $newArray;
		file_put_contents(__root__ . 'publish-dev/manifest.json', json_encode($mani, JSON_PRETTY_PRINT));
    }

    public function getAllSnapshot() {
    	$fileUpdatorDepot = $this->config->server->update.'manifest.json';
    	return @json_decode(file_get_contents($fileUpdatorDepot), true) ?? [];
    }
}