<?php
session_start();

// Routing sederhana
$page = isset($_GET['page']) ? $_GET['page'] : 'landing';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Autoload controllers
function autoloadController($className) {
    $file = 'controller/' . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}
spl_autoload_register('autoloadController');

// Routing logic
switch ($page) {
// Di bagian routing auth, tambahkan:
case 'auth':
    $controller = new AuthController();
    switch ($action) {
        case 'login':
            $controller->login();
            break;
        case 'register':
            $controller->register();
            break;
        case 'logout':
            $controller->logout();
            break;
        case 'profile':
            $controller->profile();
            break;
        case 'forgot-password':
            $controller->forgotPassword();
            break;
        default:
            $controller->login();
    }
    break;
        
    case 'alat':
        $controller = new AlatController();
        switch ($action) {
            case 'create':
                $controller->create();
                break;
            case 'edit':
                $controller->edit();
                break;
            case 'delete':
                $controller->delete();
                break;
            default:
                $controller->index();
        }
        break;
        
// Di bagian routing peminjaman, tambahkan:
case 'peminjaman':
    $controller = new PeminjamanController();
    switch ($action) {
        case 'create':
            $controller->create();
            break;
        case 'riwayat':
            $controller->riwayat();
            break;
        case 'approve':
            $controller->approve();
            break;
        case 'reject':
            $controller->reject();
            break;
        case 'start':
            $controller->start();
            break;
        case 'complete':
            $controller->complete();
            break;
        case 'pending':
            $controller->pending();
            break;
        default:
            $controller->index();
    }
    break;
        
    case 'dashboard':
        $controller = new DashboardController();
        if (isset($_SESSION['role'])) {
            switch ($_SESSION['role']) {
                case 'admin':
                    $controller->admin();
                    break;
                case 'guru':
                    $controller->guru();
                    break;
                case 'siswa':
                    $controller->siswa();
                    break;
                default:
                    header('Location: index.php?page=auth&action=login');
            }
        } else {
            header('Location: index.php?page=auth&action=login');
        }
        break;
        
    default:
        require_once 'view/landing.php';
}
?>