<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Authentication\AuthenticationServiceInterface;
use App\Authentication\Permissions\Permission;
use App\DependencyContainer;
use App\KonkursProwadzacych\KPService;

/** @var AuthenticationServiceInterface $auth_serivce  */
$auth_serivce = DependencyContainer::get(AuthenticationServiceInterface::class);
$check_result = $auth_serivce->check_permission(Permission::STUDENT_VOTE);

header('Content-Type: application/json; charset=utf-8');
if (!$check_result->has_passed()) {
    http_response_code(403);
    echo json_encode([
        "error" => "Permission denied"
    ]);
    die();
}

// TODO: Move this into its own util class

$raw_input = file_get_contents('php://input');
$data = json_decode($raw_input, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode([
        "error" => "Invalid JSON format"
    ]);
    die();
}

if (empty($data)) {
    http_response_code(400);
    echo json_encode([
        "error" => "Empty request body"
    ]);
    die();
}

if (!isset($data['konkurs_id'])) {
    http_response_code(401);
    echo json_encode([
        "error" => "Missing konkurs_id field"
    ]);
    die();
}

if (!isset($data['answers'])) {
    http_response_code(401);
    echo json_encode([
        "error" => "Missing answers field"
    ]);
    die();
}

if (!is_array($data['answers'])) {
    http_response_code(401);
    echo json_encode([
        "error" => "Answers should be an array"
    ]);
    die();
}

$user = $auth_serivce->get_current_session_user();
if ($user === null) {
    http_response_code(403);
    echo json_encode([
        "error" => "Permission denied"
    ]);
    die();
}

/** @var KPService $kp_service */
$kp_service = DependencyContainer::get(KPService::class);
$result = $kp_service->commit_votes(
    $user->get_usos_id(),
    $data['konkurs_id'],
    $data['answers']
);
echo json_encode($result);
?>