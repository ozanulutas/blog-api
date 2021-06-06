<?php

class Blog extends Model {

    public function __construct($dbc = null) {

        $this->dbc = $dbc;
        $this->tableName = "blog";
        $this->fields = [
            "id",
            "title",
            "summary",
            "post",
            "img",
            "date",
            "link",
            "meta_keywords",
            "meta_desc",
            "category_id",
            "author_id"
        ];
    }
    
}