ZipArchiveEx is an extension of the PHP integrated ZipArchive class.


Title: Detailed description

When PHP is compiled with zip support, the programmer can make use of the ZipArchive class. Although the implementation seems to be very complete, the compression of subfolders and files in these subfolders has to be written by the script-programmer himself. ZipArchiveEx implements the addDir method, which adds exactly this missing functionality.


Title: Usage

:	# ZipArchive as usual:
:	$zip = new ZipArchiveEx();
:	$zip->open('my.zip', ZIPARCHIVE::OVERWRITE);
:
:	# Add whole directory including contents:
:	$zip->addDir('mydir');
:
:	# Only add the contents of the directory, but
: 	# not the directory-entry of "mydir" itself:
:	$zip->addDirContents('mydir');
:
:	# Close archive (as usual):
:	$zip->close();


Title: Installation

ZipArchiveEx is provided as Composer package and can be installed via Packagist.


Title: Resources

- PHP Documentation entry for the ZipArchive class: <http://php.net/manual/en/class.ziparchive.php>
- Packagist: <http://packagist.org/>


Title: History

- Version 1.0.1 released on 2012-09-24
	- Added the addDirContents() method
- Version 1.0.0 released on 2012-09-18


Title: License

ZipArchiveEx is available under the MIT license:

Copyright (c) 2012 <more@codeless.at>

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
