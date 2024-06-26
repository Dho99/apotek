<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DropzoneController extends Controller
{
    public function uploadFile(Request $request,$path)
    {
        $validate = $request->validate([
            'file' => 'required|file:image|mimes:png,jpg|max:5024',
        ]);
        $fileName = $request->file('file')->getClientOriginalName();
        $hashName = (time()*mt_rand(0,999)).'.'.$request->file->getClientOriginalExtension();
        $localFileName = $path.'/'.$hashName;
        $storeImage = $request->file('file')->storeAs($localFileName);
        return $localFileName;
    }

    public function deleteFile(Request $request)
    {
        $filePath = $request->filePath;
        try{
            Storage::delete($filePath);
            return true;
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
}
