<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// https://stackoverflow.com/questions/1334613/how-to-recursively-zip-a-directory-in-php
// (COMPATIBLE WITH BUILT-IN WINDOWS 10 / 11 ZIP ARCHIVE OPENER)

class ext_zip extends ZipArchive {


    // Member function to add a whole file system subtree to the archive
    public function addSource($source, $localname = '', $password) {
        
        if ($localname) {
        $this->addEmptyDir($localname);
        }
        
        if ( is_dir($source) ) {
        $this->_addTree($source, $localname, $password);
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
        if ( $password != 'no' && $password != '' ) {
        $this->setEncryptionName($localpath, ZipArchive::EM_AES_256, $password);
        }
                
    }


    // Internal function, to recurse directory as source
    protected function _addTree($source_dir, $localname, $password) {
    
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
            $this->_addTree($path, $localpath, $password);
            }
            // File: just add
            elseif ( is_file($path) ) {
            
            $this->addFile($path, $localpath);
            
                // Password-encrypting
                if ( $password != 'no' && $password != '' ) {
                $this->setEncryptionName($localpath, ZipArchive::EM_AES_256, $password);
                }
                  
            }
            
        }
    
    closedir($dir);
    
    }
    

    // Helper function
    public static function zip_recursively($source, $target_zip, $password='no', $flags = 0, $localname = '') {
      
         if ( !extension_loaded('zip') ) {
         return 'no_extension';
         }
         elseif ( !is_dir($source) && !file_exists($source) ) {
         return 'no_source';
         }
    
    $zip = new self();
         
    $zip->open($target_zip, $flags);
    
    $zip->addSource($source, $localname, $password);
         
    $zip->close();
    
    return 'done';
    
    }
    
    
}
 
// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

 ?>