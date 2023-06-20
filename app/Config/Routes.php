<?php

namespace Config;


// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

// v1 API Post
$routes->get('v1/post', 'Api::posts');
$routes->get('v1/post/(:segment)', 'Api::post/$1');

// V1 Admin Post
$routes->get('v1/admin/post', 'API::postsAdmin');
$routes->get('v1/admin/post/(:segment)', 'Api::postAdmin/$1');
$routes->post('v1/admin/post', 'Api::createPost');
$routes->put('v1/admin/post/(:num)', 'Api::updatePost/$1');
$routes->delete('v1/admin/post/(:num)', 'Api::deletePost/$1');

// V1 Admin Label
$routes->get('v1/admin/label','Labels::showLabels');
$routes->get('v1/admin/label/(:segment)','Labels::showLabel/$1');
$routes->post('v1/admin/label','Labels::addLabels');
$routes->put('v1/admin/label/(:num)','Labels::addLabels');
$routes->delete('v1/admin/label/(:num)','Labels::deleteLabels/$1');

// V1 Admin Banner
$routes->post('v1/admin/banner','Banner::postBanner');
$routes->get('v1/admin/banner', 'Banner::getBanner');
$routes->delete('v1/admin/banner/(:num)', 'Banner::deletBanners/$1');

//  V1 Admin Facility
$routes->get('v1/admin/facility', 'Facility::getFacility');
$routes->post('v1/admin/facility', 'Facility::createFacility');
$routes->delete('v1/admin/facility/(:num)', 'Facility::delFacility/$1');

//  V1 Admin Galeri
$routes->get('v1/admin/galeri', 'Gallery::getGal');
$routes->post('v1/admin/galeri', 'Gallery::createGallery');

// v1 Api Login
$routes->post('v1/login', 'Auth::signIn');
$routes->post('v1/logout', 'Auth::logoutUser');

// v1 Api create User
$routes->post('v1/sign-up', 'Auth::createUser');
$routes->get('v1/users', 'Auth::getUsers');


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
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
