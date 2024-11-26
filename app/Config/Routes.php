<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'LoginController::index');
$routes->get('/register', 'RegistarController::index');
$routes->post('/register/saveData', 'RegistarController::saveData');
$routes->post('/login', 'LoginController::authenticate');
$routes->get('/dashboard', 'Home::index');
// $routes->post('/register', 'Home::register');
$routes->post('/saveUser', 'Home::saveUser');
$routes->get('/getSingleUser/(:num)', 'Home::getSingleUser/$1');
$routes->post('/updateUser', 'Home::updateUser');
$routes->post('/deleteUser', 'Home::deleteUser');
$routes->post('/deleteAllUser', 'Home::deleteAllUser');
$routes->post('/create', 'Home::deleteAllUser');