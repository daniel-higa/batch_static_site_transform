<?php

class ReadFiles {
    var $tree = array();
    var $type;
    var $path;
    
    public function __construct($path) {
        $this->path = $path;
        $this->name = array_pop(explode('/', $path));
    }
    
    public function is_dir(){
        return is_dir($this->path);
    }
    
    public function getName() {
        return $this->name;
    }
    
    
    public function getDirs() {
        if (!$this->is_dir()) {
            return false;
        }
        $dirs = array();
        $dir = opendir($this->path);
        while (false !== ($row = readdir($dir))) {
            if (SKIP_HIDDEN and $row[0] == '.') {
                //reserved
            } else {
                if (is_dir($child = $this->path . '/' . $row)) {
                    $dirs[] = new ReadFiles($child);
                }
            }
        }
        return $dirs;
    }
    
    public function getFiles() {
        if (!$this->is_dir()) {
            return false;
        }
        $files = array();
        $dir = opendir($this->path);
        while (false !== ($row = readdir($dir))) {
            if (SKIP_HIDDEN and $row[0] == '.') {
                //reserved
            } else {
                if (is_file($child = $this->path . '/' . $row)) {
                    $files[] = new ReadFiles($child);
                }
            }
        }
        return $files;
    }
    
    public function getFirstHTML() {
        $files = $this->getFiles();
        foreach($files as $f) {
            if (preg_match('/(.html)|(.htm)$/', $f->getName())) {
                return $f;
            }
        }
    }

    public function getAllJPG() {
        $files = $this->getFiles();
        $jpgs = array();
        foreach($files as $f) {
            if (preg_match('/(.jpg)|(.jpeg)$/', $f->getName())) {
                $jpgs[] = $f;
            }
        }
        return $jpgs;
    }

    public function getAllImages() {
        $files = $this->getFiles();
        $imgs = array();
        foreach($files as $f) {
            if (preg_match('/(.jpg)|(.jpeg)|(.png)$/', $f->getName())) {
                $imgs[] = $f;
            }
        }
        return $imgs;
    }

}
