<?php

/**
 * <b>Classe Upload</b>
 * [HELPER]
 * Classe responsavel por executar upload de imagens, arquivos e mídias no sistema!
 * @copyright (c) 2016, Diego Tesch 
 */
class Upload {

    private $file;
    private $name;
    private $send;

    /** IMAGE UPLOAD */
    private $width;
    private $image;

    /** RESULTADOS */
    private $result;
    private $error;

    /** DIRETÓRIOS */
    private $folder;
    private static $baseDir;

    public function __construct($baseDir = null) {
        self::$baseDir = ((string) $baseDir ? $baseDir . '/' : '../uploads/');
        if (!file_exists(self::$baseDir) && !is_dir(self::$baseDir)) {
            mkdir(self::$baseDir, 0777);
        }
    }

    public function Image(array $image, $name = null, $width = null, $folder = null) {
        $this->file = $image;
        $this->name = ((string) $name ? $name : substr($image['name'], 0, strrpos($image['name'], '.')));
        $this->width = ((int) $width ? $width : 1024);
        $this->folder = ((string) $folder ? $folder : 'images');

        $this->CheckFolder($this->folder);
        $this->setFileName();
        $this->UploadImage();
    }
    
    public function File(array $file, $name = null, $folder = null, $maxFileSize = null){
        $this->file = $file;
        $this->name = ((string) $name ? $name : substr($file['name'], 0, strrpos($file['name'], '.')));
        $this->folder = ((string) $folder ? $folder : 'files');
        $max = ((int) $maxFileSize ? $maxFileSize : 7);
        
        $fileAccept = [
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/msword',
            'application/pdf'
        ];
        
        if($this->file['size'] > ($max*(1024*1024))){
            $this->result = false;
            $this->error = "Arquivo muito grande, tamanho máximo permitido de {$max}Mb";
        }else if(!in_array($this->file['type'], $fileAccept)){
            $this->result = false;
            $this->error = "Tipo de arquivo não suportado. Envie .PDF ou .DOCX!";
        }else{
            $this->CheckFolder($this->folder);
            $this->setFileName();
            $this->MoveFile();
        }
    }
    
    public function Media(array $media, $name = null, $folder = null, $maxFileSize = null){
        $this->file = $media;
        $this->name = ((string) $name ? $name : substr($media['name'], 0, strrpos($media['name'], '.')));
        $this->folder = ((string) $folder ? $folder : 'media');
        $max = ((int) $maxFileSize ? $maxFileSize : 100);
        
        $fileAccept = [
            'audio/mp3',
            'video/mp4'
        ];
        
        if($this->file['size'] > ($max*(1024*1024))){
            $this->result = false;
            $this->error = "Arquivo muito grande, tamanho máximo permitido de {$max}Mb";
        }else if(!in_array($this->file['type'], $fileAccept)){
            $this->result = false;
            $this->error = "Tipo de arquivo não suportado. Envie audio MP3 ou video MP4!";
        }else{
            $this->CheckFolder($this->folder);
            $this->setFileName();
            $this->MoveFile();
        }
    }
    
    public function getResult() {
        return $this->result;
    }

    public function getError() {
        return $this->error;
    }

    //PRIVATES
    
    private function CheckFolder($folder) {
        list($y, $m) = explode('/', date('Y/m'));
        $this->CreateFolder("{$folder}");
        $this->CreateFolder("{$folder}/{$y}");
        $this->CreateFolder("{$folder}/{$y}/{$m}/");
        $this->send = "{$folder}/{$y}/{$m}/";
    }

    //Verifica e cria o diretorio base
    private function CreateFolder($folder) {
        if (!file_exists(self::$baseDir . $folder) && !is_dir(self::$baseDir . $folder)) {
            mkdir(self::$baseDir . $folder, 0777);
        }
    }

    //Verifica e monta o nome dos arquivos tratando a string
    private function setFileName() {
        $filename = Check::Name($this->name) . strchr($this->file['name'], '.');
        if (file_exists(self::$baseDir . $this->send . $filename)) {
            $filename = Check::Name($this->name) . '-' . time() . strchr($this->file['name'], '.');
        }
        $this->name = $filename;
    }
    
    private function UploadImage() {
        switch($this->file['type']){
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/pjpeg':
                $this->image = imagecreatefromjpeg($this->file['tmp_name']);
                break;
            case 'image/png':
            case 'image/x-png':
                $this->image = imagecreatefrompng($this->file['tmp_name']);
                break;
        }
        
        if(!$this->image){
            $this->result = false;
            $this->error = 'Tipo de arquivo inválido, envie imagens JPG ou PNG!';
        }else{
            $x = imagesx($this->image);
            $y = imagesy($this->image);
            $imageX = ($this->width < $x ? $this->width : $x);
            $imageH = ($imageX * $y) / $x;
            
            $newImage = imagecreatetruecolor($imageX, $imageH);
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            imagecopyresampled($newImage, $this->image, 0, 0, 0, 0, $imageX, $imageH, $x, $y);
            
            switch($this->file['type']){
                case 'image/jpg':
            case 'image/jpeg':
            case 'image/pjpeg':
                imagejpeg($newImage, self::$baseDir . $this->send . $this->name);
                break;
            case 'image/png':
            case 'image/x-png':
                imagepng($newImage, self::$baseDir . $this->send . $this->name);
                break;
            }
            
            if(!$newImage){
                $this->result = false;
                $this->error = 'Tipo de arquivo inválido, envie imagens JPG ou PNG!';
            }else{
                $this->result = $this->send . $this->name;
                $this->error = null;
            }
            
            imagedestroy($this->image);
            imagedestroy($newImage);
        }
    }

    //Envia arquivos e mídias
    private function MoveFile() {
        if(move_uploaded_file($this->file['tmp_name'], self::$baseDir . $this->send . $this->name)){
            $this->result = $this->send . $this->name;
            $this->error = null;
        }else{
            $this->result = false;
            $this->error = 'Erro ao mover o arquivo! Por favor, tente mais tarde!';
        }
        
    }
}
