<?php
define('BASE_PATH', __DIR__ . '/');
session_start();
require_once BASE_PATH . 'config/database.php';
require_once BASE_PATH . 'core/helpers.php';

// CSRF token initialize 
generateCsrfToken();
// Auto-load controllers and models
spl_autoload_register(function($class) {
    $paths = [
        BASE_PATH . 'controllers/',
        BASE_PATH . 'models/',
    ];
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) { require_once $file; return; }
    }
});

$page   = $_GET['page']   ?? 'login';
$action = $_GET['action'] ?? 'index';

// Route map
$routes = [
    'login'         => ['AuthController', 'login'],
    'register'      => ['AuthController', 'register'],
    'logout'        => ['AuthController', 'logout'],
    'dashboard'     => ['DashboardController', 'index'],
    'profile'       => ['ProfileController', $action],
    'venues'        => ['VenueController', $action],
    'events'        => ['EventController', $action],
    'tiers'         => ['TierController', $action],
    'discounts'     => ['DiscountController', $action],
    'checkin'       => ['CheckinController', $action],
    'bookings'      => ['BookingController', $action],
    'refunds'       => ['RefundController', $action],
    'reviews'       => ['ReviewController', $action],
    'announcements' => ['AnnouncementController', $action],
    'analytics'     => ['AnalyticsController', $action],
    'api_checkin'   => ['CheckinController', 'ajaxCheckin'],
];

if (!isset($routes[$page])) { $page = 'login'; }

[$controllerName, $method] = $routes[$page];

// Auth guard
$publicPages = ['login', 'register'];
if (!in_array($page, $publicPages)) {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organiser') {
        header('Location: ' . BASE_PATH . 'index.php?page=login');
        exit;
    }
}

$controller = new $controllerName();
$controller->$method();