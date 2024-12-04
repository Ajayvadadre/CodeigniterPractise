<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'LoginController::index');
$routes->get('/register', 'RegistarController::index');
$routes->get('/getSingleUser/(:num)', 'Home::getSingleUser/$1');
$routes->get('/dashboard', 'Home::index');
$routes->get('/ExportData', 'Home::exportData');
$routes->post('/filter', 'Home::index');
$routes->post('/UploadData', 'Home::uploadData');
$routes->post('/saveUser', 'Home::saveUser');
$routes->post('/updateUser', 'Home::updateUser');
$routes->post('/create', 'Home::deleteAllUser');
$routes->post('/deleteUser', 'Home::deleteUser');
$routes->post('/deleteAllUser', 'Home::deleteAllUser');
$routes->post('/login', 'LoginController::authenticate');
$routes->post('/register/saveData', 'RegistarController::saveData');
// $routes->post('/register', 'Home::register');    