<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/app.php';

require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Response.php';
require_once __DIR__ . '/core/Router.php';

require_once __DIR__ . '/middleware/CorsMiddleware.php';
require_once __DIR__ . '/middleware/AuthMiddleware.php';

require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Product.php';
require_once __DIR__ . '/models/Order.php';
require_once __DIR__ . '/models/OrderItem.php';
require_once __DIR__ . '/models/Cart.php';
require_once __DIR__ . '/models/Stock.php';

require_once __DIR__ . '/services/AuthService.php';
require_once __DIR__ . '/services/ExchangeRateService.php';
require_once __DIR__ . '/services/ExportService.php';

require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/ProductController.php';
require_once __DIR__ . '/controllers/CartController.php';
require_once __DIR__ . '/controllers/OrderController.php';
require_once __DIR__ . '/controllers/StockController.php';
require_once __DIR__ . '/controllers/ExportController.php';

CorsMiddleware::handle();

$router = new Router();

require_once __DIR__ . '/routes/api.php';

$router->dispatch();