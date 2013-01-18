<?php
function convert($html) {
   return strip_tags($html);
}

function format_jpg($file, $save_path) {
    $img = new SimpleImage();
    $img->load($file->path);
    if ($img->getWidth() > MAX_WIDTH) {
        $img->resizeToWidth(MAX_WIDTH);
    }
    if ($img->getHeight() > MAX_HEIGHT) {
        $img->resizeToHeight(MAX_HEIGHT);
    }
    $img->watermark(WATERMARK);
    $img->save($save_path . '/' . $file->getName());
}


class SimpleImage {
    function load($filename) {
        $image_info = getimagesize($filename);
        $this->image_type = $image_info[2];
        if( $this->image_type == IMAGETYPE_JPEG ) {
            $this->image = imagecreatefromjpeg($filename);
        } elseif( $this->image_type == IMAGETYPE_GIF ) {
            $this->image = imagecreatefromgif($filename);
        } elseif( $this->image_type == IMAGETYPE_PNG ) {
            $this->image = imagecreatefrompng($filename);
        }
    }
    
    function getWidth() {
        return imagesx($this->image);
    }
    function getHeight() {
        return imagesy($this->image);
    }

    function resizeToHeight($height) {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width,$height);
    }
 
    function resizeToWidth($width) {
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resize($width,$height);
    }
   
    function resize($width,$height) {
       $new_image = imagecreatetruecolor($width, $height);
       imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
       $this->image = $new_image;
    }
   
    function watermark($watermark) {
       $im = imagecreatefrompng($watermark);
       $height = max($this->getHeight(), imagesy($im));
       $width = max($this->getHeight(), imagesx($im));
       //$im2 = imagecreatetruecolor($width, $height);
       //imagecopyresampled($im2, $im, 0, 0, 0, 0, $width, $height, imagesx($im), imagesy($im));
       imagecopy($this->image, $im, 0,0,0,0, $width, $height);
    }     

    function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
        if( $image_type == IMAGETYPE_JPEG ) {
            imagejpeg($this->image,$filename,$compression);
        } elseif( $image_type == IMAGETYPE_GIF ) {
            imagegif($this->image,$filename);
        } elseif( $image_type == IMAGETYPE_PNG ) {
            imagepng($this->image,$filename);
        }
        if( $permissions != null) {
            chmod($filename,$permissions);
       }
    }
}
