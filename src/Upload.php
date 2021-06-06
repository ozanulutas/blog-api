<?php

class Upload {

    public $media;
    public $targetDir;
    public $targetFile;
    // public $uri;

    private $uploadOk;


    public function __construct($media, $targetDir) {
        $this->media = $media;
        $this->targetDir = $targetDir;
        $this->uploadOk = 1;
    }


    public function uploadFile() {
        
        $this->targetFile = $this->targetDir . basename($_FILES["yol"]["name"]);
        $fileType = strtolower(pathinfo($this->targetFile, PATHINFO_EXTENSION));

        $data["mesaj"] = array();

        if(file_exists($this->targetFile)) {
            $data["mesaj"] =  "Aynı isimde bir dosya zaten var!";
            $this->uploadOk = 0;
        }

        if($this->media == "img") {
            if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "gif") {
                $data["mesaj"] =  "Sadece JPG, JPEG, PNG & GIF formatındaki resimler desteklenmektedir.";
                $this->uploadOk = 0;
            }

        } elseif($this->media == "video") {
            if($fileType != "mp4" && $fileType != "avi" && $fileType != "mov" && $fileType != "3gp" && $fileType != "mpeg") {
                $data["mesaj"] =  "Sadece MP4, AVI, MOV, 3GP & MPEG formatındaki videolar desteklenmektedir.";
                $this->uploadOk = 0;
            }

        } elseif($this->media == "sheet") {
            if($fileType != "xls" && $fileType != "csv" && $fileType != "xlsx") {
                $data["mesaj"] =  "Sadece XLS, XLSX, CSV formatındaki dosyalar desteklenmektedir.";
                $this->uploadOk = 0;
            }
        }

        if ($this->uploadOk == 1) {
            
            if (move_uploaded_file($_FILES["yol"]["tmp_name"], $this->targetFile)) {

                return $this->targetFile;

            } else {
                $data["mesaj"] = "Dosya yüklenirken bir hata oluştu.";                
            }
        }   
        echo $data["mesaj"];

        return false;
    }


    public function uploadFromUri($fname, $uri) {

        file_put_contents($this->targetDir . $fname, file_get_contents("data://" . $uri));
    }

}