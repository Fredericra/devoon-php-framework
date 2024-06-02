<?php
declare(strict_types=1);
session_start();
require "ProviderMain/LoadingClass/Save.php";

use Provider\LoadingClass\Save;
use ProviderMain\Database\DB\DB;
use ProviderMain\Guest\Guest;
use ProviderMain\SecuriteFile\Securite;

Save::AutoSaving();
Securite::Style();
DB::Accessing();
Save::Copy();

set_exception_handler("ProviderMain\Error\ErrorValue::Error");
set_error_handler("ProviderMain\Error\ErrorValue::Error");
Securite::Securite();
Securite::require("apirest",[],"apirest");
DB::Connecting();
