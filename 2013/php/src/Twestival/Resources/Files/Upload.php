<?php namespace Twestival\Resources\Files;

class Upload
{
	protected $uploadPath;
	protected $uploadPrefix;
	protected $uploadUri;
	protected $fields;
	protected $maxFileSize;
	protected $mimeTypes;
	protected $imageDimensions = array();
	
	function __construct($uploadPath, $uploadUri, $fields)
	{
		$path = $uploadPath;
		$prefix = '';
		if(substr($path, -1) != '/')
		{
			$pathInfo = pathinfo($path);
			$path = $pathInfo['dirname'];
			$prefix = $pathInfo['basename'];
		}
		$this->uploadPath = $path;
		$this->uploadPrefix = $prefix;
		
		$uri = $uploadUri;
		if(substr($uri, -1) != '/')
		{
			$uri .= '/';
		}
		$this->uploadUri = $uri;
		
		if(!is_array($fields))
		{
			$fields = preg_split('/[\s,]+/', $fields);
		}
		$this->fields = $fields;
	}
	
	function setMaxFileSize($maxFileSize)
	{
		$maxFileSize = intval($maxFileSize);
	}
	function setMimeTypes($mimeTypes)
	{
		if(!is_array($mimeTypes))
		{
			$mimeTypes = preg_split('/[\s,]+/', $mimeTypes);
		}
		$this->mimeTypes = $mimeTypes;
	}
	function setExactImageWidth($exactImageWidth)
	{
		$this->imageDimensions['exactImageWidth'] = intval($exactImageWidth);
	}
	function setMaxImageWidth($maxImageWidth)
	{
		$this->imageDimensions['maxImageWidth'] = intval($maxImageWidth);
	}
	function setExactImageHeight($exactImageHeight)
	{
		$this->imageDimensions['exactImageHeight'] = intval($exactImageHeight);
	}
	function setMaxImageHeight($maxImageHeight)
	{
		$this->imageDimensions['maxImageHeight'] = intval($maxImageHeight);
	}

	
	function process(&$files)
	{
		$processed = array();
		foreach($files as $field => $file)
		{
			if(!in_array($field, $this->fields))
			{
				$this->deleteTemp($file);
				continue;
			}
			
			$errors = array();
			
			$this->checkUpload($file, $errors);

			if(!$errors)
			{
				$this->checkMaxFileSize($file, $errors);
			}
			
			if(!$errors)
			{
				$this->checkMimeTypes($file, $errors);
			}
			
			if(!$errors)
			{
				$this->checkImageDimensions($file, $errors);
			}
			
			$result = array();
			if($errors)
			{
				$this->deleteTemp($file);
				$processed[$field] = array(
						'Errors' => $errors
				);
			}
			else
			{
				$filename = $this->saveTemp($file);
				$uri = $this->buildUri($filename);
				$processed[$field] = array(
						'Filename' => $filename,
						'Uri' => $uri
				);
			}
			
		}
		return $processed;
	}
	
	private function checkUpload($file, &$errors)
	{
		switch($file['error'])
		{
			case UPLOAD_ERR_OK:
				return;
			case UPLOAD_ERR_INI_SIZE:
				array_push($errors, $this->buildFileSizeError($file, ini_get('upload_max_filesize')));
				break;
			case UPLOAD_ERR_FORM_SIZE:
				array_push($errors, $this->buildFileSizeError($file, $_POST('MAX_FILE_SIZE')));
				break;
			default:
				$fileName = $file['name'];
				array_push($errors, "File $fileName failed to upload");
				break;
		}
	}
	
	private function checkMaxFileSize($file, &$errors)
	{
		if($this->maxFileSize)
		{
			if($file['size'] > $this->maxFileSize)
			{
				array_push($errors, $this->buildFileSizeError($file, $this->maxFileSize));
			}
		}
	}
	
	private function buildFileSizeError($file, $maxFileSize)
	{
		$fileName = $file['name'];
		$formattedFileSize = $this->formatBytes($file['size']);
		$formattedMaxSize = $this->formatBytes($maxFileSize);
		return "File $fileName is too large: must be less than $formattedMaxSize, but is $formattedFileSize.";
		
	}
	
	private function checkMimeTypes($file, &$errors)
	{
		if($this->mimeTypes)
		{
			if(!in_array($file['type'], $this->mimeTypes))
			{
				$fileName = $file['name'];
				$mimeType = $file['type'];
				$formattedMimeTypes = implode(', ', $this->mimeTypes);
	
				array_push($errors, "File $fileName is not a supported type: must be one of $formattedMimeTypes, but is $mimeType.");
			}
		}
	}

	private function checkImageDimensions($file, &$errors)
	{
		if($this->imageDimensions)
		{
			$result = getimagesize($file['tmp_name']);
			if(!$result)
			{
				array_push($errors, "Image $fileName is not a supported image type: could not determine image width or height.");
			}
			
			$dimensions = $this->imageDimensions;
			list($width, $height) = $result;
			
			if(isset($dimensions['exactImageWidth']))
			{
				$exactImageWidth = $dimensions['exactImageWidth'];
				if($width != $exactImageWidth)
				{
					array_push($errors, "Image $fileName width is incorrect: width must be $exactImageWidth pixels, but is $width pixels.");
				}
			}

			if(isset($dimensions['maxImageWidth']))
			{
				$maxImageWidth = $dimensions['maxImageWidth'];
				if($width > $maxImageWidth)
				{
					array_push($errors, "Image $fileName width is too large: width must be less than $maxImageWidth pixels, but is $width pixels.");
				}
			}
			
			if(isset($dimensions['exactImageHeight']))
			{
				$exactImageHeight = $dimensions['exactImageHeight'];
				if($exactImageHeight && $height != $exactImageHeight)
				{
					array_push($errors, "Image $fileName height is incorrect: height must be $exactImageHeight pixels , but is $height pixels.");
				}
			}
			
			if(isset($dimensions['maxImageHeight']))
			{
				$maxImageHeight = $dimensions['maxImageHeight'];
				if($maxImageHeight && $height > $maxImageHeight)
				{
					array_push($errors, "Image $fileName height is too large: height must be less than $maxImageHeight, but is $height pixels.");
				}
			}
		}
	}
	
	private function saveTemp($file)
	{
		$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
		
		$saveFilename = uniqid($this->uploadPrefix) . '.' . $extension;

		if(!is_dir($this->uploadPath))
		{
			mkdir($this->uploadPath, 0700, TRUE);
		}
		
		move_uploaded_file($file['tmp_name'], $this->uploadPath . '/' . $saveFilename);
		
		return $saveFilename;
	}
	
	private function buildUri($filename)
	{
		return $this->uploadUri . $filename;
	}
	
	private function deleteTemp($file)
	{
		$tempFile = $file['tmp_name'];
		if($tempFile && file_exists($tempFile))
		{
			unlink($tempFile);
		}
	}
	
	private function formatBytes($bytes, $precision = 2) { 
		$units = array('b', 'kb', 'mb', 'gb', 'tb'); 
		
		$bytes = max($bytes, 0); 
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
		$pow = min($pow, count($units) - 1); 
		
		$bytes /= (1 << (10 * $pow)); 
		
		return round($bytes, $precision) . ' ' . $units[$pow]; 
	} 
}
