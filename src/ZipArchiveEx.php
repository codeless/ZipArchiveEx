<?php

# Include requirements if not testing:
if (!class_exists('Composer\Autoload\ClassLoader')) {
	$wd = getcwd();
	chdir(dirname(__FILE__));
	require('../vendor/autoload.php');
	chdir($wd);
}

# Start logging
LogMore::open('ZipArchiveEx');

/**
 * Class: ZipArchiveEx
 */
class ZipArchiveEx extends ZipArchive {

    // Public variable for save list exclude directory
	public $exclude_dir;

	/**
	 * Function: addDir
	 *
	 * Wrapper for the recursiveAddDir method.
	 *
	 * Parameters:
	 *
	 * 	$dirname - The directory to add.
	 *
	 * Returns:
	 *
	 * 	TRUE on success or FALSE on failure.
	 */
	public function addDir($dirname) {
		LogMore::debug('In addDir');
		return $this->recursiveAddDir($dirname);
	}

	/**
	 * Function: excludeDir
	 *
	 * Wrapper for the recursiveAddDir method.
	 *
	 * Parameters:
	 *
	 * 	$dirname - The directory to exclude.
	 *
	 * Returns:
	 *
	 * 	TRUE on success or FALSE on failure.
	 */
	public function excludeDir($dirname){
		LogMore::debug('Excluding dir '.$dirname);
        if(substr($dirname, -1) == '/'){
            $dirname = substr ($dirname, 0, -1);
        }

		if(empty($this->exclude_dir)){
			$this->exclude_dir = array($dirname);
		}else{
			array_push($this->exclude_dir,$dirname);
		}
	}


	/**
	 * Function: addDirContents
	 *
	 * Wrapper for the recursiveAddDir method. The difference
	 * between addDir() and addDirContents() is, that
	 * addDirContents will not add the root-directory as
	 * a directory itself into the zipfile, but only
	 * the contents.
	 *
	 * Parameters:
	 *
	 * 	$dirname - The directory to add.
	 *
	 * Returns:
	 *
	 * 	TRUE on success or FALSE on failure.
	 */
	public function addDirContents($dirname) {
		LogMore::debug('In addDirContents');
		return $this->recursiveAddDir($dirname, null, false);
	}

	/**
	 * Function: recursiveAddDir
	 *
	 * Recursively adds the passed directory and all files
	 * and folders beneath it.
	 *
	 * Parameters:
	 *
	 * 	$dirname - The directory to add.
	 * 	$basedir - The basedir where $dirname resides.
	 * 	$adddir - Add the basedir as directory itself
	 * 		to the zipfile?
	 *
	 * Returns:
	 *
	 * 	TRUE on success or FALSE on failure.
	 */
	private function recursiveAddDir(
		$dirname,
		$basedir=null,
		$adddir=true)
	{
		LogMore::debug('In recursiveAddDir');
		$rc = false;
		$basename_exclude = $basedir . basename($dirname);
		# If $dirname is a directory and not is exclude dir
		if (is_dir($dirname) && !in_array($basename_exclude, $this->exclude_dir)) {
			LogMore::debug('Is a directory: %s', $dirname);

			# Save current working directory
			$working_directory = getcwd();

			# Switch to passed directory
			chdir($dirname);

			# Get basename of passed directory
			$basename = $basedir . basename($dirname);

			# Add empty directory with the name of the passed directory
			if ($adddir) {
				LogMore::debug('Add empty dir %s', $basename);
				$rc = $this->addEmptyDir($basename);
				LogMore::debug('RC: %d', $rc);
				$basename = $basename . '/';
			} else {
				$basename = null;
			}

			# Get all files in the directory
			$files = glob('*');
			LogMore::debug('Number of files in %s: %d',
				$basename,
				sizeof($files));

			# Loop through files
			foreach ($files as $f) {
				# If file is directory
				if (is_dir($f)) {
					# Call recursiveAdd
					LogMore::debug('Add dir %s', $f);
					$this->recursiveAddDir($f, $basename);
				} else {
					LogMore::debug('Add file %s', $basename . $f);
					$rc = $this->addFile($f, $basename . $f);
					LogMore::debug('RC: %d', $rc);
				}
			}

			# Switch back to current working directory
			chdir($working_directory);

			$rc = true;
		}

		return $rc;
	}

};
