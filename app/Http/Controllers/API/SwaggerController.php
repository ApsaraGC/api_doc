<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="API Documentation",
 *     version="1.0.0",
 *     description="API Documentation for System",
 *     @OA\Contact(
 *         email="test@gmail.com"
 *     )
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter your bearer token in the format: `Bearer {token}`"
 * )
 *
 * @OA\Server(
 *     url="http://api_doc.test",
 *     description=" API Server"
 * )
 */

class SwaggerController extends Controller
{
    //
}
