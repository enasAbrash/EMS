<?php

namespace App\Traits;
trait ApiResponseTrait
{
    public function successResponse($data, $message = null, $token=null, $code = 200,)
    {
        if($data == null and $token==null)
            {
                return response()->json([
                    'status' => true,
                    'message' => $message,
                ], $code);
            }
        else if($data== null and $token != null)
        {
            return response()->json([
                'status' => true,
                'message' => $message,
                'token' => $token
            ], $code);
        }
        else {
            return response()->json([
                'status' => true,
                'message' => $message,
                'data'=>$data,
                'token'=>$token,
            ], $code);
        }
    }

    public function errorResponse($message, $code)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }
}
