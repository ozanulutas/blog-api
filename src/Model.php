<?php

class Model {

    protected $dbc;
    protected $tableName;
    protected $fields;


    public function setValues($values = array()) {

        foreach ($this->fields as $fieldName) {
            $this->$fieldName = isset($values[$fieldName]) ? $values[$fieldName] : NULL;
        }
    }


    public function findBy($fieldName, $fieldValue) {        

        $sql = "SELECT * FROM " . $this->tableName . " WHERE " . $fieldName . " = :value";
        $stmt = $this->dbc->prepare($sql);
        $stmt->execute(['value' => $fieldValue]);
        $row = $stmt->fetch();        
        
        $this->setValues($row);

        return $this;
    }


    public function list($contidions = array()) {

        $where = "";
        $orderBy = "";
        $records = array();


        if(isset($contidions["where"])) {
            $where = " WHERE";
            foreach($contidions["where"] as $field => $value) {
                $where .= " $field = :$field AND";
            }
            $where = preg_replace('/\W\w+\s*(\W*)$/', '$1', $where);
        }
        if(isset($contidions["order"])) {
            $orderBy = " ORDER BY " . implode(", ", $contidions["order"][0]) . " " . $contidions["order"][1];
        }


        $sql = "SELECT * FROM " . $this->tableName . $where . $orderBy;
        $stmt = $this->dbc->prepare($sql);
        $stmt->execute($contidions["where"]);

        while ($row = $stmt->fetch()) {
            
            $obj = new $this;
            $obj->setValues($row);
            unset($obj->dbc);
            unset($obj->tableName);
            unset($obj->fields);
            $records[] = $obj;
        }

        return $records;

        // echo "<pre>";
        // echo $where;
        // echo "</pre>";
    }


    public function insert() {

        $param = array();

        $sql = "INSERT INTO " . $this->tableName . " (";
        foreach (array_slice($this->fields, 1) as $fieldName) {
            $sql .= "$fieldName, ";
        }
        $sql = rtrim($sql, ', ');        
        $sql .= ") VALUES (";
        foreach (array_slice($this->fields, 1) as $fieldName) {
            $sql .= ":$fieldName, ";
            $param[$fieldName] = $this->$fieldName;
        }
        $sql = rtrim($sql, ', ');
        $sql .= ")";
        
        $stmt = $this->dbc->prepare($sql);
        $stmt->execute($param);

        $this->id = $this->dbc->lastInsertId();
    }

    public function update() {

        $updFields = $this->fields;
        $id = array_shift($updFields);        
        
        $sql = "UPDATE " . $this->tableName . " SET ";
        foreach ($updFields as $fieldName) {
            $sql .= "$fieldName = :$fieldName, ";
            $param[$fieldName] = $this->$fieldName;
        }
        $sql = rtrim($sql, ', ');
        $sql .= " WHERE $id = :$id";
        
        $param[$id] = $this->$id;

        $stmt = $this->dbc->prepare($sql);
        $stmt->execute($param);
    }


    public function delete() {

        $value = $this->fields[0];

        $sql = "DELETE FROM " . $this->tableName . " WHERE " . $this->fields[0] . " = :value";
        $stmt = $this->dbc->prepare($sql);
        
        $stmt->execute([':value' => $this->$value]);
    }

    public function deleteBy($fieldName = null, $fieldValue = null) {

        $sql = "DELETE FROM " . $this->tableName . " WHERE $fieldName = :value";

        $stmt = $this->dbc->prepare($sql);
        
        $stmt->execute([':value' => $fieldValue]);
    }

    public function deleteIn($fieldName, $fieldValues = array()) {

        $in = str_repeat('?, ', count($fieldValues) - 1) . '?';
        $sql = "DELETE FROM " . $this->tableName . " WHERE $fieldName IN ($in)";

        $stmt = $this->dbc->prepare($sql);
        $stmt->execute($fieldValues);
    }

/*
    public function listAlt($fieldName = null, $fieldValue = null, $order = array(), $sort = null) {

        $where = $fieldName ? " WHERE $fieldName = :value" : "";
        $orderBy = $order ? (' ORDER BY ' . implode(', ', $order)) : '';
        $sortBy = $sort ? " $sort" : '';

        $sql = "SELECT * FROM " . $this->tableName . $where . $orderBy . $sortBy;
        $stmt = $this->dbc->prepare($sql);
        $stmt->execute(['value' => $fieldValue]);

        $list = array();
        while ($row = $stmt->fetch()) {
            $obj = new $this;
            $obj->setValues($row);
            $list[] = $obj;
        }

        return $list;
    }


    public function whereIn($fieldName, $fieldValues = array()) {

        $in = str_repeat('?, ', count($fieldValues) - 1) . '?';
        $sql = "SELECT * FROM " . $this->tableName . " WHERE $fieldName IN ($in)";

        $stmt = $this->dbc->prepare($sql);
        $stmt->execute($fieldValues);

        $list = array();
        while ($row = $stmt->fetch()) {
            $obj = new $this;
            $obj->setValues($row);
            $list[] = $obj;
        }

        return $list;
    }


    public function whereInBtwDate($fieldName, $fieldValues = array(), $date = array(), $order = array(), $sort = null) {

        $date['bitis_tarih'] = isset($date[1]) ? (" AND " . $date[1] . " >= CURDATE() ") : "";
        $date['bitis_saat'] = isset($date[2]) ? (" AND " . $date[2] . " >= CURTIME() ") : "";

        $in = str_repeat('?, ', count($fieldValues) - 1) . '?';
        $orderBy = $order ? (' ORDER BY ' . implode(', ', $order)) : '';
        $sortBy = $sort ? $sort : '';
        $sql = "SELECT * FROM " . $this->tableName . " WHERE $fieldName IN ($in) AND " . $date[0] . " <= CURDATE() " . $date['bitis_tarih'] . $date['bitis_saat'] . " $orderBy $sortBy";

        $stmt = $this->dbc->prepare($sql);
        $stmt->execute($fieldValues);

        $list = array();
        while ($row = $stmt->fetch()) {
            $obj = new $this;
            $obj->setValues($row);
            $list[] = $obj;
        }

        return $list;
    }
    */


 /*   public function upload($media) {

        if($media == 'img')
            $targetDir = UPLOAD_DIR . 'img/slide/';
        elseif($media == 'video')
            $targetDir = UPLOAD_DIR . 'video/';
        
        $targetFile = $targetDir . basename($_FILES['yol']['name']);
        $uploadOk = 1;
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        if(file_exists($targetFile)) {
            echo "Aynı isimde bir dosya zaten var!";
            $uploadOk = 0;
        }

        if($media == 'img') {
            if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "gif") {
                echo "Sadece JPG, JPEG, PNG & GIF formatındaki resimler desteklenmektedir.";
                $uploadOk = 0;
            }
        }
        elseif($media == 'video') {
            if($fileType != "mp4" && $fileType != "avi" && $fileType != "mov" && $fileType != "3gp" && $fileType != "mpeg") {
                echo "Sadece MP4, AVI, MOV, 3GP & MPEG formatındaki videolar desteklenmektedir.";
                $uploadOk = 0;
            }
        }

        if ($uploadOk == 0) {
            echo "Dosya desteklenmiyor.";
        }
        else {                        
            if (move_uploaded_file($_FILES["yol"]["tmp_name"], $targetFile)) {
                echo htmlspecialchars( basename( $_FILES["yol"]["name"])). " dosyası başarıyla yüklendi.";
                //$data['yol'] = $targetFile;
                $this->setValues(['yol' => $targetFile]);
                $this->insert();
            } 
            else {
                echo "Dosya yüklenirken bir hata oluştu.";                
            }
        }   
    }*/

}