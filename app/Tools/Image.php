<?php

namespace App\Tools;

class Image
{
    public function urlToBase64($path){
        try {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        } catch (\Throwable $th) {
            return NULL;
        }
    }

    public function saveInDisk($request, $folder){
        try {
            if($request->hasFile('image')){
                $filenameWithExt = $request->file('image')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('image')->getClientOriginalExtension();
                $filenameWithTime = $filename.'_'.time();
                $fileNameToStore = $filenameWithTime . '.' .$extension;
                $request->file('image')->storeAs('public/' . $folder, $fileNameToStore);
                return 'storage/app/public/' . $folder .'/' . $fileNameToStore;
            } else {
                return NULL;
            }
        } catch (\Throwable $th) {
            return NULL;
        }
    }

}