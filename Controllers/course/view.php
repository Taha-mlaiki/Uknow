<?php
require_once "../../vendor/autoload.php";
use Models\Course;

header('Content-Type: application/json');


try {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $page = isset($data['page']) ? (int)$data['page'] : 1;
    $limit = isset($data['limit']) ? (int)$data['limit'] : 9;
     $search = isset($data['search']) ? trim($data['search']) : null;

     
    $result = Course::getAllPublishedCourses($page, $limit);
    
    echo json_encode([
        'status' => 'success',
        'courses' => $result['courses'],
        'totalPages' => $result['totalPages'],
        'currentPage' => $result['currentPage'],
        'totalRecords' => $result['totalRecords']
    ]);

} catch (Exception $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'An error occurred: ' . $e->getMessage()
    ]);
}
?>