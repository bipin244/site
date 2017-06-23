<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Page;
use Validation;
use Auth;
use Session;
use App\Http\Requests;

class UploadController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        header("Content-Type: text/plain");
        
        $uploader = new \UploadHandler();
        // Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
        $uploader->allowedExtensions = array('jpeg', 'jpg', 'png'); // all files types allowed by default
        // Specify max file size in bytes.
        $uploader->sizeLimit = null;
        // Specify the input name set in the javascript.
        $uploader->inputName = "qqfile"; // matches Fine Uploader's default inputName value by default
        // If you want to use the chunking/resume feature, specify the folder to temporarily save parts.
        $uploader->chunksFolder = "../../../../storage/chunks";

        // Assumes you have a chunking.success.endpoint set to point here with a query parameter of "done".
        // For example: /myserver/handlers/endpoint.php?done
        if (isset($_GET["done"])) {
            $result = $uploader->combineChunks(public_path() . "/uploads");
        }
        // Handles upload requests
        else {
            // Call handleUpload() with the name of the folder, relative to PHP's getcwd()
            $result = $uploader->handleUpload(public_path() . "/uploads");

            // To return a name used for uploaded file you can use the following line.
            $result["uploadName"] = $uploader->getUploadName();
        }

        return json_encode($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $uploader = new \UploadHandler();
        // Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
        $uploader->allowedExtensions = array('jpeg', 'jpg', 'png'); // all files types allowed by default
        // Specify max file size in bytes.
        $uploader->sizeLimit = null;
        // Specify the input name set in the javascript.
        $uploader->inputName = "qqfile"; // matches Fine Uploader's default inputName value by default
        // If you want to use the chunking/resume feature, specify the folder to temporarily save parts.
        $uploader->chunksFolder = "../../../../storage/chunks";

        $result = $uploader->handleDelete("../../../../public/uploads");
        echo json_encode($result);
    }

    public function uploadDelete(Request $request){
        $uploader = new \UploadHandler();
        // Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
        $uploader->allowedExtensions = array('jpeg', 'jpg', 'png'); // all files types allowed by default
        // Specify max file size in bytes.
        $uploader->sizeLimit = null;
        // Specify the input name set in the javascript.
        $uploader->inputName = "qqfile"; // matches Fine Uploader's default inputName value by default
        // If you want to use the chunking/resume feature, specify the folder to temporarily save parts.
        $uploader->chunksFolder = "../../../../storage/chunks";

        $result = $uploader->handleDelete("../../../../public/uploads");
        echo json_encode($result);
    }
}
