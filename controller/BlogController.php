<?php

class BlogController extends Controller {


    // protected function runBeforeAction() {

    //     if(empty($_SESSION["k_id"])) {
    //         echo "Yetkisiz Giriş!";
    //         return false;
    //     }
    //     return true;
    // }


    public function defaultAction() {

        $dbh = DatabaseConnection::getInstance();
        $dbc = $dbh->getConnection();
        $blog = new Blog($dbc);        
        
        $data = array();

        $data["blogs"] = $blog->list([
            "where" => [
                "author_id" => 1,
                "category_id" => 6
            ],
            "order" => [
                [
                    "date", "id"
                ],
                "DESC"
            ],
        ]);  

        // echo "<pre>";
        // var_dump($data["blogs"]);
        // echo "</pre>";
        echo json_encode($data["blogs"]);
    }


    public function createAction() {

        $dbh = DatabaseConnection::getInstance();
        $dbc = $dbh->getConnection();

        if(isset($_POST)) {  
            $_POST = json_decode(file_get_contents("php://input"), true);

            $blog = new Blog($dbc);
            $blog->setValues($_POST);
            $blog->insert();   
            
            echo json_encode($blog); 
        }
    }


    public function editAction() {

        $dbh = DatabaseConnection::getInstance();
        $dbc = $dbh->getConnection();

        if($_POST) {
            $_POST = json_decode(file_get_contents("php://input"), true);

            $blog = new Blog($dbc);
            $blog->setValues($_POST);
            $blog->update();

            echo json_encode($blog); 
        }
    }


    public function deleteAction() {

        $dbh = DatabaseConnection::getInstance();
        $dbc = $dbh->getConnection();

        $blog = new Blog($dbc);
        $blog->findBy("id", $_GET["id"]);
        $blog->delete();

        echo json_encode($blog); 
    }
}