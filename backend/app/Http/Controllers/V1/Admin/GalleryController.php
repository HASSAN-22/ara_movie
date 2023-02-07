<?php

namespace App\Http\Controllers\V1\Admin;

use App\Auxiliary\Uploader\Upload;
use App\Auxiliary\Uploader\Uploader;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GalleryController extends Controller
{

    public function showGallery(Request $request){
        $this->authorize('viewAny',User::class);
        $allPath = [];
        $pattern = "/^(.*[\/]$)/i";

        $path = $request->directory ?? '/uploader/gallery/';
        if(preg_match($pattern, $path) != 1){
            $path .= '/';
        }

        chdir(public_path($path));
        foreach (scandir(getcwd()) as $item){
            if(!in_array($item,['.','..'])){
                $allPath[] = "{$path}{$item}";
            }
        }
        return response(['status'=>'success','data'=>$allPath]);
    }

    public function makeFolder(Request $request){
        $this->authorize('create',User::class);
        $request->validate(['folderName'=>['required','string','max:255']]);
        $dir = public_path($request->folder);
        if(file_exists($dir)){
            return response(['status'=>'error','errors'=>['files'=>['فولدر وجود دارد']]],422);
        }
        mkdir($dir);
        return response(['status'=>'success']);
    }
    public function galleryRename(Request $request){
        $request->validate([
           'newName' => ['required','string','max:255']
        ]);
        $current = $request->current;
        if(file_exists(public_path($current.$request->oldName))){
            rename(public_path($current.$request->oldName), public_path($current.$request->newName));
            return response(['status'=>'success']);
        }
        return response(['status'=>'error','errors'=>['file'=>['خطا: مسیر فایل وجود ندارد']]],422);
    }
    public  function galleryUpload(Request $request){
        $this->authorize('create',User::class);
        $result = $this->rules($_FILES);
        if($result !== true){
            return $result;
        }
        $file = Uploader::upload(Upload::setOptions($_FILES['file'],$request->folder.'/'));
        return response(['status'=>'success','ff'=>$file]);
    }

    private function rules($file){
        if(empty($file)){
            return response(['status'=>'error','errors'=>['file'=>['فیلد تصویر یا ویدیو خالی است']]],422);
        }
        $pattern = "/^.*(php.?|phtml|asp|aspx|axd|asx|asmx|ashx|cfm|yaws|htm|html|xhtml|shtml|jhtml|jsp|jspx|wss|do|action|pl|py|rb|rhtml|rss|xml|cgi|dll|fcgi)$/i";
        $ext = last(explode('.',$file['file']['name']));
        if(preg_match($pattern, $ext) == 1){
            return response(['status'=>'error','errors'=>['file'=>['فرمت فایل مناسب نیست']]],422);
        }
        return true;
    }
    public function galleryRemove(Request $request)
    {
        $this->authorize('create',User::class);
        $dir = public_path($request->gallery);
        if(file_exists($dir)){
            if(is_dir($dir)){
                array_map('unlink', glob("$dir/*.*"));
                rmdir($dir);
            }else{
                unlink($dir);
            }
            return response(['status'=>'success']);
        }
        return response(['status'=>'error'],500);

    }
}
