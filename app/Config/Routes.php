<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//$routes->get('/', 'Home::index');
$routes->get('/', 'LoginController::index');
$routes->post('/login', 'LoginController::login');
$routes->get('/logout', 'LoginController::logout');

$routes->get('/homepage', 'Home::index');
$routes->get('/silinenler', 'Home::silinenler');
$routes->get('/addcustomer', 'AddCustomerController::index');

$routes->post('/savecustomer', 'AddCustomerController::savecustomer');
$routes->get('/customerdelete/(:num)', 'AddCustomerController::deletecustomer/$1');
$routes->get('/customerview/(:num)', 'AddCustomerController::customerview/$1');
$routes->get('/customereditview/(:num)', 'AddCustomerController::customereditview/$1');
$routes->post('/editcustomer/(:num)', 'AddCustomerController::editcustomer/$1');
$routes->get('/addpersonel', 'PersonelController::index');

$routes->post('/updateuser', 'PersonelController::updateuser');
$routes->post('/insertuser', 'PersonelController::insertuser');
$routes->get('/deleteuser/(:num)', 'PersonelController::deleteuser/$1');

$routes->get('/grafik', 'GrafikController::index');


$routes->get('/addgold', 'AddGoldController::index');
$routes->post('takoz/ilerletAjax/(:num)', 'Home::ilerletAjax/$1');
$routes->post('hurda/ilerletAjax/(:num)', 'Home::ilerletAjaxHurda/$1');
$routes->get('home/ayarevi', 'Home::ayarevi');

$routes->post('takoz/cesniEkle', 'Home::cesniEkle');
$routes->post('takoz/kaydet', 'AddGoldController::kaydet');

$routes->post('takoz/kalancesniEkle', 'Home::kalancesniEkle');

$routes->get('home/eritme', 'Home::eritme');

$routes->post('home/uretTakoz', 'Home::uretTakoz');

$routes->post('takozHurda/hurdaTakozYap', 'Home::hurdaTakozYap');


$routes->get('/kasaHesap', 'Home::kasaHesap');



$routes->post('cesni/ilerletCesniAjax/(:num)', 'Home::ilerletCesniAjax/$1');

$routes->post('cesni/incele', 'Home::inceleCesni');
