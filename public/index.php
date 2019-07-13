<?php
require_once __DIR__."/../bootstrap/app.php";

require_once base_path()."/App/routes/web.php";

use App\Kernel;
$app = new Kernel();

echo $app->execute();
