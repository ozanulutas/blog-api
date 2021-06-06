<?php

class Author extends Model {

    public function __construct($dbc = null) {

        $this->dbc = $dbc;

        $this->tableName = 'author';

        $this->fields = [
            'id',
            'name',
            'description',
            'img'
        ];
    }
    
}