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
$routes->group('v1', function($routes) {
    // v1 API Post
    $routes->get('post', 'Api::posts');
    $routes->get('post/(:segment)', 'Api::post/$1');

    // V1 Admin Post
    $routes->group('admin/post', function($routes) {
        $routes->get('', 'API::postsAdmin');
        $routes->get('(:segment)', 'Api::postAdmin/$1');
        $routes->post('', 'Api::createPost');
        $routes->put('(:num)', 'Api::updatePost/$1');
        $routes->delete('(:num)', 'Api::deletePost/$1');
    });

    // V1 Admin Label
    $routes->group('admin/label', function($routes) {
        $routes->get('', 'Labels::showLabels');
        $routes->get('(:segment)', 'Labels::showLabel/$1');
        $routes->post('', 'Labels::addLabels');
        $routes->put('(:num)', 'Labels::addLabels');
        $routes->delete('(:num)', 'Labels::deleteLabels/$1');
    });

    // V1 Admin Banner
    $routes->post('admin/banner', 'Banner::postBanner');
    $routes->get('admin/banner', 'Banner::getBanner');
    $routes->delete('admin/banner/(:num)', 'Banner::deletBanners/$1');

    // V1 Admin Facility
    $routes->get('admin/facility', 'Facility::getFacility');
    $routes->get('admin/facility/(:num)', 'Facility::getFacilitys/$1');
    $routes->post('admin/facility', 'Facility::createFacility');
    $routes->delete('admin/facility/(:num)', 'Facility::delFacility/$1');

    // V1 Admin Galeri
    $routes->get('admin/galeri', 'Gallery::getGal');
    $routes->post('admin/galeri', 'Gallery::createGallery');
    $routes->delete('admin/galeri/(:num)', 'Gallery::deleteGaleri/$1');

    // V1 Admin Kategori Galeri
    $routes->post('admin/kategori', 'Gallery::addKategori');
    $routes->get('admin/kategori', 'Gallery::getKategori');
    $routes->delete('admin/kategori/(:num)', 'Gallery::deleteKategori/$1');

    // v1 Api Login
    $routes->post('login', 'Auth::signIn');
    $routes->post('logout', 'Auth::logoutUser');

    // v1 Api create User
    $routes->post('sign-up', 'Auth::createUser');
    $routes->get('users', 'Auth::getUsers');
});


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
