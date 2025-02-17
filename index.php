<?php

require __DIR__ . '/vendor/autoload.php';

use App\Bin\MainBinProvider;
use App\Common\Reader;
use App\Rate\MainRateProvider;
use Dotenv\Dotenv;


$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = new App\App(
        new MainBinProvider(),
        new MainRateProvider($_ENV['RATE_API_KEY'] ?? ''),
        new Reader()
);

$app->run($argv[1]);

//echo implode('', $commissions);