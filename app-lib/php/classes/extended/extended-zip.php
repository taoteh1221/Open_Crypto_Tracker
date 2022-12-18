<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


// https://stackoverflow.com/questions/1334613/how-to-recursively-zip-a-directory-in-php


class ext_zip extends ZipArchive {


    // Member function to add a whole file system subtree to the archive
    public function addTree($source_dir, $localname = '') {
        
        if ($localname) {
        $this->addEmptyDir($localname);
        }
        
    $this->_addTree($source_dir, $localname);
    
    }


    // Internal function, to recurse
    protected function _addTree($source_dir, $localname, $password='no') {
    
    $dir = opendir($source_dir);
        
        while ( $filename = readdir($dir) ) {
            
            // Discard . and ..
            if ( $filename == '.' || $filename == '..' ) {
            continue;
            }

            // Proceed according to type
            $path = $source_dir . '/' . $filename;
            $localpath = $localname ? ($localname . '/' . $filename) : $filename;
            
            // Directory: add & recurse
            if ( is_dir($path) ) {
            $this->addEmptyDir($localpath);
            $this->_addTree($path, $localpath);
            }
            // File: just add
            elseif ( is_file($path) ) {
            
            $this->addFile($path, $localpath);
                  
                  // If we are password-protecting
                  if ( $password != 'no' ) {
                  $this->setEncryptionName($path, ZipArchive::EM_AES_256);
                  }
                  
            }
            
        }
    
    closedir($dir);
    
    }
    

    // Helper function
    public static function zip_recursively($source_dir, $target_zip, $password='no', $flags = 0, $localname = '') {
      
         if ( !extension_loaded('zip') ) {
         return 'no_extension';
         }
         elseif ( !file_exists($source_dir) ) {
         return 'no_source';
         }
    
    $zip = new self();
         
         
         // If we are password-protecting
         if ( $password != 'no' ) {
         $zip->setPassword($password);
         }
         
    $zip->open($target_zip, $flags);
    $zip->addTree($source_dir, $localname, $password);
    $zip->close();
    
    return 'done';
    
    }
    
    
}
 
// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

 ?>