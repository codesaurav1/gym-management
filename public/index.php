<?php
require_once __DIR__ . '/../vendor/autoload.php';
set_exception_handler(function ($e) {
  $code = $e->getCode();

  http_response_code(
    ($code >= 100 && $code <= 599) ? $code : 500
  );

  header('Content-Type: application/json');
  echo json_encode([
    'error' => $e->getMessage()
  ]);
  exit;
});

use Dotenv\Dotenv;
use App\routes\Router;
use App\middleware\Auth;
use App\middleware\Authorization;

// Load .env
$dotenv = Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

$router = new Router();

// Public route
// Auth Endpoint
$router->post('/api/signup', 'AuthController@signup');
$router->post('/api/login', 'AuthController@login');
$router->post('/api/forget', 'AuthController@ForgetPassword');
$router->post('/api/refresh-token', 'AuthController@generateAccessToken');

// Protect route 
//dashboard
$router->get('/api/dashboard', function () {
  $decoded = Auth::handle(); // middleware check
  (new \App\controller\DashBoardController())->index();
});

// Brach Endpoint
$router->post('/api/branch', function () {
  $decoded = Auth::handle(); // middleware check
  $autheriseUser = Authorization::handle(['owner']); // middleware Authorization check
  (new \App\controller\BranchController())->CreateBranch();
});

$router->get('/api/branch', action: function () {
  $decoded = Auth::handle(); // middleware check
  $autheriseUser = Authorization::handle(['owner']); // middleware Authorization check
  (new \App\controller\BranchController())->GetAllBranches();
});


// Branch Admin 
$router->post('/api/branch-admin', function () {
  $decoded = Auth::handle(); // middleware check
  $autheriseUser = Authorization::handle(['owner']); // middleware Autherization check
  (new \App\controller\BranchAdminController())->CreateBranchAdmin();
});

// Branch Staff
$router->post('/api/branch-staff', function () {
  $decoded = Auth::handle(); // middleware check
  $autheriseUser = Authorization::handle(['admin', 'owner']); // middleware Autherization check
  (new \App\controller\BranchStaffController())->CreateBranchStaff();
});


// Member Endpoint
$router->post('/api/member', function () {
  $decoded = Auth::handle(); // middleware check
  $autheriseUser = Authorization::handle(['owner', 'admin']); // middleware Authorization check
  (new \App\controller\MembershipController())->CreateMember();
});
$router->get('/api/member', function () {
  $decoded = Auth::handle(); // middleware check
  $autheriseUser = Authorization::handle(['owner', 'admin']); // middleware Authorization check
  (new \App\controller\MembershipController())->getMember();
});

$router->get('/api/member', function () {
  $decoded = Auth::handle(); // middleware check
  $autheriseUser = Authorization::handle(['admin']); // middleware Authorization check
  (new \App\controller\MembershipController())->getMember();
});

// get-all-member can only owner can see member data to his branch
$router->get('/api/get-all-member', function () {
  $decoded = Auth::handle(); // middleware check
  $autheriseUser = Authorization::handle(['owner']); // middleware Authorization check
  (new \App\controller\MembershipController())->getAllMember();
});


// Subscription Creation 
$router->post('/api/subscription', function () {
  $decoded = Auth::handle(); // middleware check
  $autheriseUser = Authorization::handle(['owner', 'admin']); // middleware Authorization check
  (new \App\controller\SubscriptionController())->CreateSubscription();
});

// Public route
// get Subscription
$router->get('/api/subscription', 'SubscriptionController@GetSubscription');

// Protect route
// put Subscription
$router->put('/api/subscription', function () {
  $decoded = Auth::handle(); // middleware check
  $autheriseUser = Authorization::handle(['owner', 'admin']); // middleware Authorization check
  (new \App\controller\SubscriptionController())->UpdateSubscription();
});

// Delete Subscription
$router->delete('/api/subscription', function () {
  $decoded = Auth::handle(); // middleware check
  $autheriseUser = Authorization::handle(['owner', 'admin']); // middleware Authorization check
  (new \App\controller\SubscriptionController())->DeleteSubscription();
});

// Member Subscription 
// Create member subscription
$router->post('/api/member-subscription/:id', function ($params) {
  $decoded = Auth::handle(); // middleware check
  $autheriseUser = Authorization::handle(['member']); // middleware Authorization check

  $sub_id = $params['id']; // This is the UUID

  (new \App\controller\MemberSubscriptonController())->CreateMemberSubscription($sub_id);
});

$router->get('/api/member-subscription', function () {
  $decoded = Auth::handle(); // middleware check
  $autheriseUser = Authorization::handle(['member']); // middleware Authorization check
  (new \App\controller\MemberSubscriptonController())->GetMemberSubscription();
});

$router->put('/api/member-subscription', function () {
  $decoded = Auth::handle(); // middleware check
  $autheriseUser = Authorization::handle(['member']); // middleware Authorization check
  (new \App\controller\MemberSubscriptonController())->UpdateMemberSubscription();
});

$router->delete('/api/member-subscription', function () {
  $decoded = Auth::handle(); // middleware check
  $autheriseUser = Authorization::handle(['member']); // middleware Authorization check
  (new \App\controller\MemberSubscriptonController())->DeleteMemberSubscription();
});

// Staff Attendance end point 
$router->post('/api/staff/attendance/check-in', function () {
  $decoded = Auth::handle(); // middleware check
  $autheriseUser = Authorization::handle(['staff']); // middleware Authorization check
  (new \App\controller\StaffAttandanceController())->CheckIn();
});
$router->post('/api/staff/attendance/check-out', function () {
  $decoded = Auth::handle(); // middleware check
  $autheriseUser = Authorization::handle(['staff']); // middleware Authorization check
  (new \App\controller\StaffAttandanceController())->CheckOut();
});
$router->get('/api/staff/attendance/me', function () {
  $decoded = Auth::handle(); // middleware check
  $autheriseUser = Authorization::handle(['staff']); // middleware Authorization check
  (new \App\controller\StaffAttandanceController())->GetStaffAttendance();
});
//Owner → Branch Staff Attendance
$router->get('/api/staff/attendance/branch/:branch_id', function ($branch_id) {
  $decoded = Auth::handle(); // middleware check
  $autheriseUser = Authorization::handle(['owner']); // middleware Authorization check
  (new \App\controller\StaffAttandanceController())->GetStaffAttendanceByBranchId($branch_id);
});
// Admin → Specific Staff Attendance
$router->get('/api/staff/attendance/staff/:staff_id', function ($staff_user_id) {
  $decoded = Auth::handle(); // middleware check
  $autheriseUser = Authorization::handle(['admin', 'owner']); // middleware Authorization check
  (new \App\controller\StaffAttandanceController())->GetStaffAttendanceByStaffId($staff_user_id);
});

// Today’s Attendance
$router->get('/api/staff/attendance/today', function () {
  $decoded = Auth::handle(); // middleware check
  $autheriseUser = Authorization::handle(['admin']); // middleware Authorization check
  (new \App\controller\StaffAttandanceController())->GetTodayStaffAttendance();
});

// Delete / Fix Attendance (Admin)
$router->delete('/api/staff/attendance/:attendance_id', function ($attendance_id) {
  $decoded = Auth::handle(); // middleware check
  $autheriseUser = Authorization::handle(['admin']); // middleware Authorization check
  (new \App\controller\StaffAttandanceController())->DeleteStaffAttendance($attendance_id);
});

// Define routes
$router->get('/api/hello', 'Controller@sayHello');
$router->get('/api/members', 'Controller@getMembers');


// Dispatch
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

?>