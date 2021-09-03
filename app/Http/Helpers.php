<?php
namespace App\Http;

Class Helpers {

    public function apiArrayResponseBuilder($statusCode = null, $message = null, $data = [])
    {
        $arr = [
            'status_code' => (isset($statusCode)) ? $statusCode : 500,
            'message' => (isset($message)) ? $message : 'error'
        ];

        if (count($data) > 0) {
            $arr['data'] = $data;
        } else {
            $arr['data'] = [
                'current_page' => null,
                'data' => [],
                'first_page_url' => null,
                'from' => null,
                'last_page' => null,
                'last_page_url' => null,
                'next_page_url' => null,
                'path' => null,
                'per_page' => null,
                'prev_page_url' => null,
                'to' => null,
                'total' => 0,
            ];
        }

        return response()->json($arr, $arr['status_code']);
        //return $arr;

    }
}
