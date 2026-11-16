<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/** @var object $router **/

// FRONTEND
$router->get('/', 'LandingController::index');
$router->get('/landing', 'LandingController::index');

// LOGIN AND SIGNUP
$router->get('/login', 'LoginController::index');
$router->post('/auth/process', 'LoginController::authenticate');

$router->get('/signup', 'LoginController::showSignup');
$router->post('/signup/process', 'LoginController::register');

$router->get('/logout', 'LoginController::logout');

// DASHBOARD
$router->get('/dashboard', 'DashboardController::index');

// CUSTOMER AND ADMIN PAGES
$router->get('/rooms', 'RoomsController::index');
$router->post('/rooms/store', 'RoomsController::store');
$router->get('/reservations', 'ReservationsController::index');
$router->get('/restaurant', 'RestaurantController::index');
$router->post('/restaurant/add_to_cart', 'RestaurantController::add_to_cart');
$router->get('/orders', 'OrdersController::index');
$router->post('/orders/update_cart', 'OrdersController::update_cart');
$router->get('/orders/remove/{id}', 'OrdersController::remove_item');
$router->post('/orders/checkout', 'OrdersController::checkout');
$router->get('/inventory', 'InventoryController::index');
$router->get('/diveservices', 'DiveservicesController::index');
$router->post('/diveservices/store', 'DiveservicesController::store');
$router->get('/transactions', 'TransactionsController::index');

// ADMIN SETTINGS
$router->get('/admin-settings', 'AdminSettingsController::index');

// API
$router->get('/api/health', 'VillasApiController::health');
$router->get('/api/dashboard', 'VillasApiController::dashboard');

$router->get('/api/{resource}', 'VillasApiController::index');
$router->get('/api/{resource}/{id}', 'VillasApiController::show');

$router->post('/api/{resource}', 'VillasApiController::store');
$router->put('/api/{resource}/{id}', 'VillasApiController::update');
$router->patch('/api/{resource}/{id}', 'VillasApiController::update');
$router->delete('/api/{resource}/{id}', 'VillasApiController::destroy');

// Stripe Payment Routes
$router->get('/payment/checkout/{id}', 'PaymentController::checkout');
$router->get('/payment/success', 'PaymentController::success');

//Map
$router->get('/map', 'DiveservicesController::map');

//Settings
$router->get('/settings', 'SettingsController::index');
$router->post('/settings/update_profile', 'SettingsController::update_profile');
$router->post('/settings/update_password', 'SettingsController::update_password');

// ADMIN & STAFF DASHBOARDS
$router->get('/admin/dashboard', 'AdminController::index');
$router->get('/staff/dashboard', 'StaffController::index');

// ADMIN MANAGE ROUTES
$router->get('/admin/rooms', 'AdminController::manage_rooms');
$router->post('/admin/rooms/store', 'AdminController::store_room');
$router->post('/admin/rooms/delete/{id}', 'AdminController::delete_room');

$router->get('/admin/diveservices', 'AdminController::manage_diveservices');
$router->post('/admin/diveservices/store', 'AdminController::store_diveservice');
$router->post('/admin/diveservices/delete/{id}', 'AdminController::delete_diveservice');

$router->get('/admin/restaurant', 'AdminController::manage_restaurant');
$router->post('/admin/restaurant/store', 'AdminController::store_menuitem');
$router->post('/admin/restaurant/delete/{id}', 'AdminController::delete_menuitem');

// MANAGE USERS
$router->get('/admin/users', 'AdminController::manage_users');
$router->post('/admin/users/store', 'AdminController::store_user');
$router->post('/admin/users/update', 'AdminController::update_user');
$router->post('/admin/users/delete/{id}', 'AdminController::delete_user');

// EDIT ROUTES PARA SA ROOMS
$router->get('/admin/rooms/edit/{id}', 'AdminController::edit_room');
$router->post('/admin/rooms/update', 'AdminController::update_room');

// EDIT ROUTES PARA SA DIVE SERVICES
$router->get('/admin/diveservices/edit/{id}', 'AdminController::edit_diveservice');
$router->post('/admin/diveservices/update', 'AdminController::update_diveservice');

// EDIT ROUTES PARA SA RESTAURANT
$router->get('/admin/restaurant/edit/{id}', 'AdminController::edit_menuitem');
$router->post('/admin/restaurant/update', 'AdminController::update_menuitem');

// STAFF ROUTES
$router->get('/staff/orders', 'StaffController::manage_orders');
$router->post('/staff/orders/update', 'StaffController::update_order_status');
$router->get('/staff/reservations', 'StaffController::manage_reservations');
$router->post('/staff/reservations/update', 'StaffController::update_reservation_status');
$router->get('/staff/divebookings', 'StaffController::manage_divebookings');
$router->post('/staff/divebookings/update', 'StaffController::update_divebooking_status');