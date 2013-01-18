<?php

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
       $im2 = imagecreatetruecolor($width, $height);
       imagesavealpha($im2, true);
       $trans_colour = imagecolorallocatealpha($im2, 0, 0, 0, 127);
       imagefill($im2, 0, 0, $trans_colour);
       imagecopyresampled($im2, $im, 0, 0, 0, 0, $this->getWidth(), $this->getHeight(), imagesx($im), imagesy($im));
       imagecopy($this->image, $im2, 0,0,0,0, $width, $height);
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
