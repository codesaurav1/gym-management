<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use App\routes\Router;
use App\middleware\Auth;
use App\middleware\Authorization;

// Load .env
$dotenv = Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

$router = new Router();

// Auth Endpoint
// Public route
$router->post('/api/signup', 'AuthController@signup');
$router->post('/api/login', 'AuthController@login');
$router->post('/api/refresh-token', 'AuthController@generateAccessToken');

// Protect route dashboard
$router->get('/api/dashboard', function () {
  $decoded = Auth::handle(); // middleware check
  (new \App\controller\DashBoardController())->index();
});

// Brach Endpoint
$router->post('/api/branch', function () {
  $decoded = Auth::handle(); // middleware check
  $autheriseUser = Authorization::handle(['admin']); // middleware Authorization check
  (new \App\controller\BranchController())->CreateBranch();
});

$router->get('/api/branch', function () {
  $decoded = Auth::handle(); // middleware check
  $autheriseUser = Authorization::handle(['admin']); // middleware Authorization check
  (new \App\controller\BranchController())->GetAllBranches();
});

// Member Endpoint
$router->post('/api/member', function () {
  $decoded = Auth::handle(); // middleware check
  $autheriseUser = Authorization::handle(['admin']); // middleware Authorization check
  (new \App\controller\MemberController())->CreateMember();
});

// Membership Endpoint
$router->get('/api/plans', function(){
  $decoded = Authorization::handle(); // middleware check
  (new \App\controller\MembershipController())->CreatePlan();
});


// Define routes
$router->get('/api/hello', 'Controller@sayHello');
$router->get('/api/members', 'Controller@getMembers');


// Dispatch
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

