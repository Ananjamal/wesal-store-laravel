<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponsable;

/**
 * Base API Controller.
 * All API controllers must extend this class to get the unified response trait.
 */
abstract class ApiController extends Controller
{
    use ApiResponsable;
}
