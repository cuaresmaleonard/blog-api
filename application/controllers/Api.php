<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Api extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->model('api_model', 'api');
        $this->load->library('form_validation');
    }

    public function posts($id_param = null) {
        $sanitized_id = preg_replace('/[^-a-zA-Z0-9_]/', '', $id_param);
        // settype($sanitized_id, "integer");

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $this->read($sanitized_id);
                break;
            case 'POST':
                $this->create();
                break;
            case 'PUT':
                $this->update($sanitized_id);
                break;
            case 'DELETE':
                $this->delete($sanitized_id);
                break;
            default:
                # code...
                break;
        }

        // URL params
        // if( isset( $_GET['tesasdased'] ) ) {
        // }
        
    }

    private function read($id) {
        try {
            if($id > 0) {
                print_r($this->api->get_blog($id));
            } else {
                
                print_r($this->api->get_blogs());
            }
        } catch(Exception $e) {
            http_response_code(500);
            echo 'Message: ' .$e->getMessage();
        }

    }

    private function create() {
        try {
            // echo "posting data...";
            $inputJSON = file_get_contents('php://input');
            $_POST = json_decode($inputJSON, TRUE);

            $blog = array(
                "title" => filter_var($_POST['title'], FILTER_SANITIZE_STRING),
                "content" => filter_var($_POST['content'], FILTER_SANITIZE_STRING),
                "author" => filter_var($_POST['author'], FILTER_SANITIZE_STRING)
            );

            print_r($this->api->insert_blog($blog));
        } catch(Exception $e) {
            http_response_code(500);
            echo 'Message: ' .$e->getMessage();
        }

    }

    private function update($id = null) {
        try {
            // echo "updating data...";
            $inputJSON = file_get_contents('php://input');
            $_PUT = json_decode($inputJSON, TRUE);

            if (!$id) {
                $response["message"] = "ID is a required parameter";
                $response["status"] = "Error";
                http_response_code(500);
                echo json_encode($response);
                return;
            }
            
            $blog = array(
                "id" => filter_var($id, FILTER_SANITIZE_STRING),
                "title" => !empty($_PUT['title']) ?  filter_var($_PUT['title'], FILTER_SANITIZE_STRING) : null,
                "content" => !empty($_PUT['content']) ? filter_var($_PUT['content'], FILTER_SANITIZE_STRING) : null,
                "author" => !empty($_PUT['author']) ? filter_var($_PUT['author'], FILTER_SANITIZE_STRING) : null
            );

            if( is_null($blog['title']) && is_null($blog['content']) && is_null($blog['author']) ) {
                $response["message"] = "Nothing to update";
                $response["status"] = "Error";
                http_response_code(500);
                echo json_encode($response);
                return;
            }

            function filter_null($var)
            {
                return !is_null($var);
            }

            print_r($this->api->update_blog(array_filter($blog,"filter_null")));

        } catch(Exception $e) {
            http_response_code(500);
            echo 'Message: ' .$e->getMessage();
        }
    }

    private function delete($id = null) {
        try {
            // echo "deleting data...";
            if (!$id) {
                $response["message"] = "ID is a required parameter";
                $response["status"] = "Error";
                http_response_code(500);
                echo json_encode($response);
                return;
            }
            
            if($this->api->delete_blog($id)) {
                $response["message"] = "ID ".$id." is successfully deleted";
                $response["status"] = "success";
                echo json_encode($response);
                http_response_code(200);
            } else {
                $response["message"] = "An error was encountered";
                $response["status"] = "Error";
                echo json_encode($response);
                http_response_code(500);
            }
        } catch(Exception $e) {
            http_response_code(500);
            echo 'Message: ' .$e->getMessage();
        }

    }

    public function healthCheck() {
        $response["message"] = "App is running";
        $response["status"] = "success";
        http_response_code(200);
        echo json_encode($response);
    }

}
