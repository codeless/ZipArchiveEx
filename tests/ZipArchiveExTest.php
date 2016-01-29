<?php

require('vendor/autoload.php');
use \Heartsentwined\FileSystemManager\FileSystemManager;
require('src/ZipArchiveEx.php');

class ZipArchiveExTest extends PHPUnit_Framework_TestCase {

	public static $tmpDir;

	public function provideTestDirs() {
		return array(
			array('invalid dir', false, null),
			array('tests/zipme', true, null),
			array('tests/zipme', true, 'README'),
		);
	}


	public function setUp() {
		# Get temporary directory:
		self::$tmpDir = sys_get_temp_dir();
	}


	/**
	 * @dataProvider provideTestDirs
	 */
	public function testAddDir(
		$dirname,
		$expected_result,
		$manipulate,
		$zipContentsOnly=false)
	{
		# Create new archive
		$archive = '/tmp/archive.zip';
		$zip = new ZipArchiveEx();
		$zip->open($archive, ZIPARCHIVE::OVERWRITE);

		# Try to add directory:
		if ($zipContentsOnly) {
			$result = $zip->addDirContents($dirname);
		} else {
			$result = $zip->addDir($dirname);
		}
		$this->assertEquals($expected_result, $result);

		# Close archive:
		$zip->close();

		# If directory was added successfully
		if ($result) {
			# Remove extracted testdirectory from
			# former testruns:
			$extractionDir = self::$tmpDir . '/' . basename($dirname);
			FileSystemManager::rrmdir($extractionDir);

			# Extract directory
			$output = array();
			# -u Option forces update of already existing files,
			# importang for testing on travis-ci.org!
			$extractTo = ($zipContentsOnly)
				? $extractionDir
				: self::$tmpDir;
			exec('unzip -u ' . $archive . ' -d ' . $extractTo,
				$output,
				$result);
			$this->assertEquals(0, $result); # 0 = successfull

			# $manipulate holds the file to manipulate,
			# so the following assertion fails.
			if ($manipulate) {
				file_put_contents(
					$extractionDir . '/' . $manipulate,
					'Lorem ipsum dolor sit amet.');
				$expected_result = 1;
			} else {
				$expected_result = 0;
			}

			# Compare extracted directory and original one
			exec('diff -arq ' . $dirname . ' ' . $extractionDir, $output, $result);
			LogMore::debug('Output of diff-command: %s', implode(PHP_EOL, $output));
			LogMore::debug('Expecting %d, got: %d',
				$expected_result,
				$result);
			$this->assertEquals($expected_result, $result);
		}
	}

	/**
	 * @dataProvider provideTestDirs
	 */
	public function testAddDirContents(
		$dirname,
		$expected_result,
		$manipulate)
	{
		self::testAddDir($dirname, $expected_result, $manipulate, true);
	}

};

