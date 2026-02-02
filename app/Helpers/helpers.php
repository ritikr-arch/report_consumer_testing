<?php
use App\Models\Setting;
if (!function_exists('apiResponse')) {


    /**


     * Return a standardized JSON API response.


     *


     * @param  bool   $success  Indicates success (true) or error (false)


     * @param  string $message  A message to be returned


     * @param  mixed  $data     Additional data (default: null)


     * @param  int    $status   HTTP status code (default: 200)


     * @return \Illuminate\Http\JsonResponse


     */


    function apiResponse(bool $success, string $message, $data = null, int $status = 200){
        $response = [
            'success' => $success,
            'message' => $message,
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }
        return response()->json($response, $status);
    }

    function customt_date_format($date){
        $format = Setting::pluck('date_format')->first();
        if($format){
            return date($format, strtotime($date));
        }else{
            return date('d-m-Y', strtotime($date));
        }
    }

    function priceCollectionHeading(){
        $price_collection = Setting::pluck('price_collection')->first();
        if($price_collection){
            return $price_collection;
        }
    }
     function adminEmail(){
        $admin_email = Setting::pluck('admin_email')->first();
        if($admin_email){
            return $admin_email;
        }
    }

    function snakeToTitleCase($string) {
        // Replace underscores with spaces
        $string = str_replace('_', ' ', $string);
        // Convert to title case
        return ucwords($string);
    }

    


}
