<?php
/*
 * PROJECT:           IM Statuses Engine
 * FILE DESCRIPTION:  Content downloader class
 * VERSION:           1.0
 * CREATED:           03.08.2012
 *
 * AUTHOR:            Yuriy 'Urvin' Gorbachev
 * EMAIL:             urvindt@gmail.com
 *
 */

//----------------------------------------------------------------------------//

/**
 * Web page content downloader class
 */
class tContentDownloader
{
	/**
	 * Use or not use cUrl library, if not - using native PHP functions
	 * @var bool
	 */
	protected $fUseCURL;
	/**
	 * cUrl instance
	 * @var mixed
	 */
	protected $fCURL;

	//--------------------------------------------------------------------------//

	/**
	 * Class constuctor
	 * @param bool $aUseCurl A parameter that allows use cUrlLibrary
	 */
	public function __construct($aUseCurl = true)
	{
		$this->fUseCURL = $aUseCurl;
		$this->doInitCURL();
	}

	public function __destruct()
	{
		$this->doDestructCURL();
	}

	//--------------------------------------------------------------------------//

	/**
	 * Init cUrl instance if allowed
	 */
	private function doInitCURL()
	{
		if($this->fUseCURL)
		{
			$this->fCURL = curl_init();
			curl_setopt($this->fCURL, CURLOPT_USERAGENT,      'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
			curl_setopt($this->fCURL, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($this->fCURL, CURLOPT_CONNECTTIMEOUT, 10);
			curl_setopt($this->fCURL, CURLOPT_FAILONERROR,    true);
			curl_setopt($this->fCURL, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($this->fCURL, CURLOPT_AUTOREFERER,    true);
			curl_setopt($this->fCURL, CURLOPT_SSL_VERIFYPEER, false);
		}
	}

	/**
	 * Destruct cUrl instance
	 */
	private function doDestructCURL()
	{
		if($this->fCURL)
			curl_close($this->fCURL);
	}

	//--------------------------------------------------------------------------//

	/**
	 * Get contents of $aUrl page
	 * @param string $aUrl URL of page downloading
	 * @param bool $aGetHeader Allows including of HTTP headers in answer
	 * @return string
	 */
	public function getURLContents($aUrl, $aGetHeader = false)
	{
		$lResult = '';

		if($this->fUseCURL)
		{
			curl_setopt($this->fCURL, CURLOPT_URL, $aUrl);
			if($aGetHeader)
			{
				curl_setopt($this->fCURL, CURLOPT_VERBOSE, 1);
				curl_setopt($this->fCURL, CURLOPT_HEADER, 1);
			}
			else
			{
				curl_setopt($this->fCURL, CURLOPT_VERBOSE, 0);
				curl_setopt($this->fCURL, CURLOPT_HEADER, 0);
			}

			$lResult = curl_exec($this->fCURL);
			if(curl_errno($this->fCURL))
				$lResult = '';
		}
		else
		{
			if($aGetHeader)
				$lResult = get_headers($aUrl);
			$lResult .= file_get_contents($aUrl);
		}

		return $lResult;
	}

	//--------------------------------------------------------------------------//
}

?>