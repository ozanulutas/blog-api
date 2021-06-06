<?php

class Category extends Model {

    public function __construct($dbc = null) {

        $this->dbc = $dbc;

        $this->tableName = 'category';

        $this->fields = [
            'id',
            'name',
            'link'
        ];
    }
    
}