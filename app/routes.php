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
