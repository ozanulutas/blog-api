<?php

define( "DB_HOST", "localhost" );
define( "DB_NAME", "zorlusms");
define( "DB_USERNAME", "root" );
define( "DB_PASSWORD", "" );


require_once "src/DatabaseConnection.php";
require_once "src/Model.php";
require_once "src/Controller.php";
require_once "src/Auth.php";

require_once "model/Author.php";
require_once "model/Blog.php";
require_once "model/Category.php";

