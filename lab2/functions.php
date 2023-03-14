<?php
function getUrl(){
    $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $url = explode('/', $url);

    if (isset($url[3]) && $url[3] !== 'products') {
        header("ERROR 404 Not Found");
        exit();
    }
    
    $product_id = null;
    
    if (isset($url[4])) {
        $product_id = (int) $url[4];
       return $product_id;
    }  
}

function handle_requests($dbconnect, $product_id){
    
    $method = $_SERVER["REQUEST_METHOD"];
    
    switch($method){
        
        case 'POST':
            $post = json_decode(file_get_contents('php://input'), true);
            $response = $dbconnect->save($post);
            echo json_encode($response);
            break;
            
        case 'GET':
            if ($product_id) {
                $response =  $dbconnect->get_record_by_id($product_id);
                if($response){
                   
                    echo json_encode($response);
                }else{
                    $response = ["Error" => "Not Found"];
                    http_response_code(404); 
                }
            }else{
                $response = $dbconnect->get_data();
                echo json_encode($response);
            }
            
            break;
            
        case 'DELETE':
            if ($product_id) {
                if($dbconnect->search("id", $product_id)){
                    $dbconnect->connect();
                    $response = $dbconnect->delete($product_id);
                }else{
                    $response = ["Error" => "Not Found"];
                    http_response_code(404); 
                }
                echo json_encode($response);
            }
            break;

        case 'PUT':
            if ($product_id) {
                if($dbconnect->search("id", $product_id)){
                    $dbconnect->connect();
                    $update = json_decode(file_get_contents('php://input'), true);
                    $response = $dbconnect->update($update,$product_id);
                }else{
                    $response = ["Error" => "Not Found"];
                    http_response_code(404);
                }
                echo json_encode($response);
            }
            break;

        default:
            $response = ["Error" => "Request Method Not Supported"];
            http_response_code(405);
            echo json_encode($response);
            break;
    }
    
}