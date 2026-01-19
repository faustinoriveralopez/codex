<?php
// app/routes.php

// Auth Routes
$router->get('/', 'AuthController', 'login');
$router->get('/login', 'AuthController', 'login');
$router->post('/login', 'AuthController', 'attemptLogin');
$router->get('/logout', 'AuthController', 'logout');

// Dashboard (Protected)
$router->get('/dashboard', 'DashboardController', 'index');

// Document Routes
$router->get('/documents/create', 'DocumentController', 'create');
$router->post('/documents/store', 'DocumentController', 'store');
$router->get('/documents/reception', 'DocumentController', 'reception');
$router->get('/documents/my_tray', 'DocumentController', 'my_tray');
$router->get('/documents/turnar', 'DocumentController', 'turnar');
$router->post('/documents/processTurnar', 'DocumentController', 'processTurnar');
$router->get('/documents/view', 'DocumentController', 'show');
$router->post('/documents/addActivity', 'DocumentController', 'addActivity');
$router->get('/documents/close', 'DocumentController', 'close');
$router->post('/documents/processClose', 'DocumentController', 'processClose');
$router->get('/reports/audit', 'ReportController', 'audit');
$router->get('/reports/exportAudit', 'ReportController', 'exportAudit');

// Admin Routes
$router->get('/admin/dashboard', 'AdminController', 'index');
$router->get('/admin/users', 'AdminController', 'users');
$router->get('/admin/areas', 'AdminController', 'areas');
$router->post('/admin/areas/store', 'AdminController', 'storeArea');
$router->get('/admin/closing_types', 'AdminController', 'closingTypes');
$router->post('/admin/closing_types/store', 'AdminController', 'storeClosingType');
