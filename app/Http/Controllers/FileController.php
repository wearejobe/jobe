<?php

namespace App\Http\Controllers;

use App, Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Upload;
use Illuminate\Support\Str;

class FileController extends Controller
{
    //
    public $publicFolder = 'public/';
    public $uploadsFolder = 'fls/';
    public $trashFolder = 'trash/';
    public function __construct()
    {
        $this->middleware('verified');
    }
    public function forceRemove(Request $request){
        $user_folder = md5(auth()->user()->id);
        $file = App\Upload::find($request->id);
        if($file):
            $fileURL = $this->uploadsFolder.'/'.$user_folder.'/'.$file->filename;
            Storage::disk('local')->delete($fileURL);
            $file->forceDelete();
        endif;

        return response()->json([
            'message' => 'Deleted : ' . $file->id
        ]);
    }
    public function remove(Request $request){
        $user_folder = md5(auth()->user()->id);
        $file = App\Upload::find($request->id);
        if($file):
            $fileURL = $this->uploadsFolder.'/'.$user_folder.'/'.$file->filename;
            $new_fileURL =  $this->trashFolder.'/'.$user_folder.'/'.$file->filename;
            //Storage::disk('local')->delete($fileURL);
            Storage::move($fileURL, $new_fileURL);
            $file->delete();
        endif;

        return response()->json([
            'message' => 'Deleted : ' . $file->id
        ]);
    }
    public function upload(Request $request){
        $user_folder = md5(auth()->user()->id);
        $uploadedFile = $request->file('file');
        $filename = time().$uploadedFile->getClientOriginalName();

        Storage::disk('local')->putFileAs(
            $this->uploadsFolder.$user_folder.'/',
            $uploadedFile,
            $filename
        );

        $upload = new Upload;
        $upload->filename = $filename;

        $upload->user()->associate(auth()->user());

        $upload->save();

        return response()->json([
            'id' => $upload->id
        ]);
    }
    public function uploadImgData(Request $request){
        $user_folder = md5(auth()->user()->id);
        $uploadedFile = base64_decode(substr($request->file, strpos($request->file, ',') + 1));
        //dd($uploadedFile);
        $filename = 'profile_'.time().'_'.$user_folder . '.png';

        $imgFullPath = $this->publicFolder.$user_folder.'/'.$filename;
        Storage::put($imgFullPath,$uploadedFile);

        $upload = new Upload;
        $upload->filename = $filename;

        $upload->user()->associate(auth()->user());

        $upload->save();

        return response()->json([
            'id' => $upload->id
        ]);
    }
    public function download($code){
        $fileID = Str::afterlast($code,'_');
        $file = App\Upload::find($fileID);
        if($file):
            $user_folder = md5($file->user_id);
            $fileURL = $this->uploadsFolder.'/'.$user_folder.'/'.$file->filename;
            return Storage::download($fileURL);
        else:
            return abort(404);
        endif;
    }
}
