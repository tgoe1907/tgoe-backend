<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->post('/', 'Home::index');
$routes->get('/logout', 'Home::logout');
$routes->get('/admin/member-data-confirmation', 'Admin\MemberDataConfirmation::index');
$routes->get('/admin/member-data-confirmation/refresh', 'Admin\MemberDataConfirmation::refreshCache');
$routes->get('/admin/member-data-confirmation/downloadlist/(:segment)/(:segment)', 'Admin\MemberDataConfirmation::downloadList/$1/$2');
$routes->get('/admin/data-quality-check', 'Admin\DataQualityCheck::index');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
