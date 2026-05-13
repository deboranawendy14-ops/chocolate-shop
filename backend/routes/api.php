<?php
// AUTH
$router->post('/api/auth/register',        function() { (new AuthController())->register(); });
$router->post('/api/auth/login',           function() { (new AuthController())->login(); });
$router->post('/api/auth/forgot-password', function() { (new AuthController())->forgotPassword(); });
$router->post('/api/auth/reset-password',  function() { (new AuthController())->resetPassword(); });
$router->get('/api/auth/me',               function() { (new AuthController())->me(); });

// PRODUTOS
$router->get('/api/products',              function() { (new ProductController())->index(); });
$router->get('/api/products/categories',   function() { (new ProductController())->categories(); });
$router->get('/api/products/{id}',         function($id) { (new ProductController())->show($id); });
$router->post('/api/products',             function() { (new ProductController())->store(); });
$router->put('/api/products/{id}',         function($id) { (new ProductController())->update($id); });
$router->delete('/api/products/{id}',      function($id) { (new ProductController())->destroy($id); });

// CARRINHO
$router->get('/api/cart',                  function() { (new CartController())->index(); });
$router->post('/api/cart',                 function() { (new CartController())->store(); });
$router->put('/api/cart/{id}',             function($id) { (new CartController())->update($id); });
$router->delete('/api/cart/{id}',          function($id) { (new CartController())->destroy($id); });

// PEDIDOS
$router->get('/api/orders',                function() { (new OrderController())->index(); });
$router->get('/api/orders/{id}',           function($id) { (new OrderController())->show($id); });
$router->post('/api/orders',               function() { (new OrderController())->store(); });
$router->put('/api/orders/{id}/status',    function($id) { (new OrderController())->updateStatus($id); });

// STOCK
$router->get('/api/stock',                 function() { (new StockController())->index(); });
$router->put('/api/stock/{id}',            function($id) { (new StockController())->update($id); });
$router->get('/api/stock/low',             function() { (new StockController())->lowStock(); });

// EXPORTAÇÃO
$router->get('/api/export/orders/csv',     function() { (new ExportController())->exportOrdersCsv(); });
$router->get('/api/export/stock/csv',      function() { (new ExportController())->exportStockCsv(); });