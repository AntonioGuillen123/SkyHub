<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *       title="Sky Hub API", 
 *       version="1.0"
 * )
 *
 * @OA\Server(
 *       url=L5_SWAGGER_CONST_HOST,
 *       description="Main Server"
 * )
 * 
 * @OA\Tag(
 *  name="Airplane",
 *  description="URLs of the Airplanes"
 * )
 * 
 * @OA\Tag(
 *  name="Flight",
 *  description="URLs of the Flights"
 * )
 */
abstract class Controller
{
    //
}
