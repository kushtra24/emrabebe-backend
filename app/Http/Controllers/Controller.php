<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

     /**
     * Parse given string to array by (optional) delimiter
     *
     * @param $string
     * @param string $delimiter
     * @param bool $removeEmptyEntries
     * @param bool $trimEntries
     * @return array|null
     */
    public static function stringToArray($string, $delimiter = ',', $removeEmptyEntries = true, $trimEntries = true) {
        if(!is_null($string) && gettype($string) === 'string') {
            // get array
            $array = explode($delimiter, $string);
            // trim entries
            if($trimEntries) {
                $array = array_map('trim', $array);
            }
            // remove empty entries
            if($removeEmptyEntries) {
                $array = array_filter($array, 'strlen');
            }
            return $array;
        }
        return null;
    }

    /**
     * @param null $query
     * @param null $page
     * @param null $limit
     * @param null $orderByArr
     * @param string $orderType
     * @return |null
     */
    protected function executeQuery(&$query = null, $page = null, $limit = null, $orderType = 'asc', $orderByArr = 'id') {
        $result = null;
        if(!isset($query)) { return null; }

        // order by array
//        if(is_countable($orderByArr) && count($orderByArr) > 0) {
            // check sort ranking
//            if(!isset($orderType) || $orderType !== 'desc' && $orderType !== 'asc') {
//                $orderType = 'asc';
//            }
//            else {
                $query = $query->orderBy($orderByArr, $orderType);
//            }

            // create order by
//            for($i = 0, $max = count($orderByArr); $i < $max; $i++) {
//                $attr = $orderByArr[$i];
//                if(!isset($attr)) { continue; }
//                $query = $query->orderBy($attr, $orderType);
//            }
//        }

        // check for pagination
        if(isset($page) && $page > 0) {
            // check limit
            if(!isset($limit) || $limit <= 0) { $limit = 10; }
            // execute
            $result = $query->paginate($limit);
        } else {
            // check for limit
            if(isset($limit) && $limit > 0) {
                $query = $query->limit($limit);
            }
            $result = $query->get();
//            $result = $query->paginate($limit); // laravel doing pagination (slow)

        }

        return $result;
    }

    /**
     * Clean array by given $removeValue
     * and simple trim values if possible
     *
     * @param $array
     * @param null $removeValue
     * @param null $exceptionsArr
     * @return array
     */
    protected function cleanArray($array, $removeValue = null, $exceptionsArr = null){
        if(!is_countable($array)) { return $array; }

        if(is_null($removeValue)) {
            return array_filter($array, function($value, $key) use(&$array, $exceptionsArr) {
                if(gettype($value) === 'string') {
                    // trim value
                    $array[$key] = trim($value);
                }
                //check if key is in exception
                if(!is_null($exceptionsArr) && array_search($key, $exceptionsArr) !== false) { return true; }
                // check if value is not NULL and set and not an empty string
                return !(is_null($value) || !isset($value) || (gettype($value) === 'string' && trim($value) === ''));

            }, ARRAY_FILTER_USE_BOTH);
        }

        return array_filter($array, function($value, $key) use (&$array, $removeValue, $exceptionsArr) {
            if(gettype($value) === 'string') {
                // trim value
                $array[$key] = trim($value);
            }
            //check if key is in exception
            if(!is_null($exceptionsArr) && array_search($key, $exceptionsArr) !== false) { return true; } // in exception
            // check if value is NOT remove value
            return $value !== $removeValue;

        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * update base64 photos and store them
     * @param $request
     * @param $requestedPhoto
     * @param $storagePath
     * @param string $currentPhoto
     */
    public function updatePhoto(&$request, $requestedPhoto, $storagePath, $currentPhoto = '') {
        $image_64 = $requestedPhoto;  // your base64 encoded
        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
        $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
        // find substring fro replace here eg: data:image/png;base64,
        $image = str_replace($replace, '', $image_64);
        $image = str_replace(' ', '+', $image);
        $imageName = Str::random(10) . '.' . $extension;
        Storage::disk($storagePath)->put($imageName, base64_decode($image));
        $request->merge(['photo' => $imageName]);
        // delete the old photo from the storage
        $this->deleteStoragePhoto($storagePath, $currentPhoto);
    }

    /**
     * delete current storage photo
     * @param $storagePath
     * @param string $currentPhoto
     */
    public function deleteStoragePhoto($storagePath, $currentPhoto = '') {
        $currentDBPhoto = storage_path('app/public/' . $storagePath . "/").$currentPhoto;
        if (file_exists($currentDBPhoto)) {
            @unlink($currentDBPhoto);
        }
    }

    /**
     * check if query is set
     * @param $query
     * @return mixed
     */
    public function checkIfQueryIsNotSet($query) {
        if (!isset($query)) { return null; }
    }

}


