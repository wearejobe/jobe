<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Upload extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'filename'
      ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public static function getAvatarFile($fileID){
        $avatar = Self::find($fileID);
        if($avatar){
            $user_folder = md5($avatar->user_id);
            $fileURL = 'storage/'.$user_folder.'/'.$avatar->filename;
            return asset($fileURL);
        }else{
            return asset('images/default-avatar.svg');
        }

    }
    
}
