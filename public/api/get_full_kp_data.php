<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Authentication\AuthenticationServiceInterface;
use App\Authentication\Permissions\Permission;
use App\DependencyContainer;
use App\KonkursProwadzacych\Aggregates\UserKonkursProwadzacychAggregate;
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

/** @var KPService $kp_service  */
$kp_service = DependencyContainer::get(KPService::class);
$user = $auth_serivce->get_current_session_user();
if ($user === null) {
    http_response_code(403);
    echo json_encode([
        "error" => "Permission denied"
    ]);
    die();
}

$aggregates = $kp_service->get_all_voting_page_data_for_user($user->get_usos_id());
echo json_encode(array_map(static fn (UserKonkursProwadzacychAggregate $e) => $e->to_array(), $aggregates));
?>