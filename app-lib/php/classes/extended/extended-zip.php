<?php
/*
 * Copyright 2014-2026 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// https://stackoverflow.com/questions/1334613/how-to-recursively-zip-a-directory-in-php
// (COMPATIBLE WITH BUILT-IN WINDOWS 10 / 11 ZIP ARCHIVE OPENER)

class ext_zip extends ZipArchive {


    // Member function to add a whole file system subtree to the archive
    public function addSource($source, $localname = '', $password, $skip_sub_dir_array) {
        
        if ($localname) {
        $this->addEmptyDir($localname);
        }
        
        if ( is_dir($source) ) {
        $this->_addTree($source, $localname, $password, $skip_sub_dir_array);
        }
        elseif ( is_file($source) ) {
        $this->_addFile($source, $localname, $password);
        }
    
    }


    // Internal function, to add single file as source
    protected function _addFile($source_file, $localname, $password) {
        
    $path = $source_file;
    $localpath = $localname ? ($localname . '/' . basename($source_file) ) : basename($source_file);
            
    $this->addFile($path, $localpath);
            
        // Password-encrypting
        if ( $password && $password != '' ) {
        $this->setEncryptionName($localpath, ZipArchive::EM_AES_256, $password);
        }
                
    }


    // Internal function, to recurse directory as source
    protected function _addTree($source_dir, $localname, $password, $skip_sub_dir_array) {
    
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
            if (
            is_dir($path) && sizeof($skip_sub_dir_array) < 1
            || is_dir($path) && sizeof($skip_sub_dir_array) > 0 && !in_array($filename, $skip_sub_dir_array)
            ) {
            $this->addEmptyDir($localpath);
            $this->_addTree($path, $localpath, $password, $skip_sub_dir_array);
            }
            // File: just add
            elseif ( is_file($path) ) {
            
            $this->addFile($path, $localpath);
            
                // Password-encrypting
                if ( $password && $password != '' ) {
                $this->setEncryptionName($localpath, ZipArchive::EM_AES_256, $password);
                }
                  
            }
            
        }
    
    closedir($dir);
    
    }
    

    // Helper function
    public static function zip_recursively($source, $target_zip, $password=false, $skip_sub_dir=false, $localname = '') {
      
      
         if ( !extension_loaded('zip') ) {
         return 'no_extension';
         }
         elseif ( !is_dir($source) && !file_exists($source) ) {
         return 'no_source';
         }

         
         // Subdirs to skip
         if ( !$skip_sub_dir ) {
         $skip_sub_dir_array = array();
         }
         else {
         $skip_sub_dir_array = array_map( "trim", explode(',', $skip_sub_dir) );
         }
         
    
    $zip = new self();
         
    $zip->open($target_zip, ZipArchive::CREATE);
    
    $zip->addSource($source, $localname, $password, $skip_sub_dir_array);
         
    $zip->close();
    
    return 'done';
    
    }
    
    
}
 
// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

 ?>