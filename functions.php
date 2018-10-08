
    public static function generateCompressedImage($image){
        $name = explode('/',$image);
        $image_name=end($name);

        $name = explode('.',$image_name);

        $final_name =  $name[0] . ".jpg";

        $filetype = end($name);
        //$result = substr($image_name,0,-(strlen($filetype)+1))."_square_thumb.". $filetype;

        if ($filetype=='jpeg' || $filetype=='jpg')
            $im = imagecreatefromjpeg($image);
        elseif ($filetype=='png')
            $im = imagecreatefrompng($image);
        else
            return false;

        $ini_x_size = getimagesize($image)[0];
        $ini_y_size = getimagesize($image)[1];

        //resize to 2000 px
        if ($ini_x_size>2000 || $ini_y_size>2000) {
            $ratio = $ini_x_size / $ini_y_size; // width/height
            if ($ratio > 1) {
                $width = 2000;
                $height = 2000 / $ratio;
            } else {
                $width = 2000 * $ratio;
                $height = 2000;
            }
        }else{
            $width = $ini_x_size;
            $height = $ini_y_size;
        }

        $bg = imagecreatetruecolor($width, $height);
        imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
        imagealphablending($bg, TRUE);

        imagecopyresampled($bg, $im, 0, 0, 0, 0, $width, $height, $ini_x_size, $ini_y_size);

        imagedestroy($image);
        $quality = 75; // 0 = worst / smaller file, 100 = better / bigger file
        imagejpeg($bg, $final_name, $quality);

        imagedestroy($bg);
        return $final_name;
    }
    
public static function generateThumb($image){

        $name = explode('/',$image);
        $image_name=end($name);

        $name = explode('.',$image_name);
        $filetype = end($name);
        $result = substr($image_name,0,-(strlen($filetype)+1))."_thumb.". $filetype;

        if ($filetype=='jpeg' || $filetype=='jpg')
            $im = imagecreatefromjpeg($image);
        elseif ($filetype=='png')
            $im = imagecreatefrompng($image);
        else
            return false;

        //crop to square
        $ini_x_size = getimagesize($image)[0];
        $ini_y_size = getimagesize($image)[1];
        if ($ini_x_size>600 || $ini_y_size>600) {
            $ratio = $ini_x_size / $ini_y_size; // width/height
            if ($ratio > 1) {
                $width = 600;
                $height = 600 / $ratio;
            } else {
                $width = 600 * $ratio;
                $height = 600;
            }
        }else{
            $width = $ini_x_size;
            $height = $ini_y_size;
        }

        $crop_measure = min($ini_x_size, $ini_y_size)-1;

        $thumb = imagecreatetruecolor($width, $height);
        
        if ($filetype=='png') {
            $color = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
            imagefill($thumb, 0, 0, $color);
            imagecolortransparent($thumb, $color);
            imagealphablending($thumb, true);
        }
        imagecopyresampled($thumb, $im, 0, 0, 0, 0, $width, $height, $ini_x_size, $ini_y_size);

        //save image
        if ($filetype=='jpeg' || $filetype=='jpg')
            imagejpeg($thumb, $result, 75);
        elseif ($filetype=='png')
            imagepng($thumb, $result, 8);

        return $result;

    }


    public static function generateSquareThumb($image){

        $name = explode('/',$image);
        $image_name=end($name);

        $name = explode('.',$image_name);
        $filetype = end($name);
        $result = substr($image_name,0,-(strlen($filetype)+1))."_square_thumb.". $filetype;

        if ($filetype=='jpeg' || $filetype=='jpg')
            $im = imagecreatefromjpeg($image);
        elseif ($filetype=='png')
            $im = imagecreatefrompng($image);
        else
            return false;

        //crop to square
        $ini_x_size = getimagesize($image)[0];
        $ini_y_size = getimagesize($image)[1];
        $crop_measure = min($ini_x_size, $ini_y_size)-1;

        $to_crop_array = array('x' => $ini_x_size/2-$crop_measure/2+1 , 'y' => $ini_y_size/2-$crop_measure/2+1, 'width' => $crop_measure, 'height'=> $crop_measure);
        $thumb_im = imagecrop($im, $to_crop_array);

        //resize to 600x600 or less
        $resize_measure=min($crop_measure, 600);
        $thumb = imagecreatetruecolor($resize_measure, $resize_measure);

        if ($filetype=='png') {
            $color = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
            imagefill($thumb, 0, 0, $color);
            imagecolortransparent($thumb, $color);
            imagealphablending($thumb, true);
        }

        imagecopyresized($thumb, $thumb_im, 0, 0, 0, 0, $resize_measure, $resize_measure, $crop_measure, $crop_measure);

        //save image
        if ($filetype=='jpeg' || $filetype=='jpg')
            imagejpeg($thumb, $result, 75);
        elseif ($filetype=='png')
            imagepng($thumb, $result, 8);

        return $result;

    }


    public static function generateSameRatioImage($image){
        $ratio = 0.58;
        $name = explode('/',$image);
        $image_name=end($name);
        $image_path = substr($image,0,-(strlen($image_name)+1));

        $name = explode('.',$image_name);
        $filetype = end($name);
        $result =  $image_path . "/" . substr($image_name,0,-(strlen($filetype)+1))."_ratio.". $filetype;

        if (strtolower($filetype)=='jpeg' || strtolower($filetype)=='jpg')
            $im = imagecreatefromjpeg($image);
        elseif (strtolower($filetype)=='png')
            $im = imagecreatefrompng($image);
        else
            return false;

        //crop to square
        $ini_x_size = getimagesize($image)[0];
        $ini_y_size = getimagesize($image)[1];

        $crop_measure = min($ini_x_size, $ini_y_size)-1;

        $to_crop_array = array('x' => $ini_x_size/2-$crop_measure/2+1 , 'y' => $ini_y_size/2-($crop_measure*$ratio)/2+1, 'width' => $crop_measure, 'height'=> $crop_measure*$ratio);
        $thumb_im = imagecrop($im, $to_crop_array);


        //resize to 1024x1024 or less
        $resize_measure=min($crop_measure, $this->thumbs_max_dimension);
        $thumb = imagecreatetruecolor($resize_measure, $resize_measure*$ratio);

        if (strtolower($filetype)=='png') {
            $color = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
            imagefill($thumb, 0, 0, $color);
            imagecolortransparent($thumb, $color);
            imagealphablending($thumb, true);
        }

        imagecopyresized($thumb, $thumb_im, 0, 0, 0, 0, $resize_measure, $resize_measure, $crop_measure, $crop_measure);

        //save image
        if (strtolower($filetype)=='jpeg' || strtolower($filetype)=='jpg')
            imagejpeg($thumb, $result, 75);
        elseif (strtolower($filetype)=='png')
            imagepng($thumb, $result, 8);

        return $result;
    }
