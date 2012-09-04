<?php
/*
 * PROJECT:           IM Statuses Engine
 * FILE DESCRIPTION:  Basic IM Status Getter class
 * VERSION:           1.0
 * CREATED:           29.08.2012
 *
 * AUTHOR:            Yuriy 'Urvin' Gorbachev
 * EMAIL:             urvindt@gmail.com
 *
 */

//----------------------------------------------------------------------------//

require_once 'tcontentdownloader.class.php';

//----------------------------------------------------------------------------//

/**
 * IM status codes enum
 */
class enmIMStatus
{
	const imsOffline       = 0x00;
	const imsOnline        = 0x01;
	const imsAway          = 0x02;
	const imsDoNotDisturb  = 0x03;
	const imsNotAvailable  = 0x04;
	const imsFreeForChat   = 0x05;
}

/**
 * IM error codes enum
 */
class enmImError
{
	const imeNoError       = 0x00;
	const imeBadIdentity   = 0x01;
	const imeUnknownStatus = 0x02;
	const imeConnectionErr = 0x03;
}

//----------------------------------------------------------------------------//

/**
 * Basic IM getter status, provides interface an functionality for child getters
 * Inherit functions doUpdateImStatus and checkImIdentity in children
 */
abstract class tBasicIMGetter
{
	/**
	 * tContentDownloader instance
	 * @var mixed
	 * @see class tContentDownloader
	 */
	protected $fCDownloader;
	/**
	 * Cached statuses array in form of identity => status_code
	 * @var array
	 */
	protected $fCachedStatuses;
	/**
	 * Last error code
	 * @var int
	 * @see class enmImError
	 */
	protected $fLastError;

	/**
	 * Gets the IM status from IM service server and updates cache array.
	 * @param string $aIdentity IM identificator
	 */
	abstract protected function doUpdateImStatus($aIdentity);

	/**
	 * Checks $aIdentity to match the IM service rules
	 * @return mixed Boolean false if doesn't match or right Identity if matches
	 */
	abstract protected function checkImIdentity($aIdentity);

	/**
	 * Update status in cache array
	 * @param string $aIdentity IM identificator
	 * @param int $aStatus Status code
	 * @see class enmIMStatus
	 */
	protected function doUpdateCachedStatus($aIdentity, $aStatus)
	{
		$this->fCachedStatuses[$aIdentity] = $aStatus;
	}

	/**
	 * IM Getter constructor
	 * @param bool $aUseCurl Use cUrl library or use native php functions
	 */
	public function __construct($aUseCurl = true)
	{
		$this->fCachedStatuses = array();
		$this->fLastError = enmImError::imeNoError;
		$this->fCDownloader = new tContentDownloader($aUseCurl);
	}

	/**
	 * Gets IM status code of $aIdentity identificator
	 * @param string $aIdentity IM service identificator
	 * @return int
	 * @see class enmIMStatus
	 */
	public function getImStatus($aIdentity)
	{
		$aIdentity = $this->checkImIdentity($aIdentity);
		if($aIdentity === false)
		{
			$this->fLastError = enmImError::imeBadIdentity;
			return enmIMStatus::imsOffline;
		}
		else
		{
			if(empty($this->fCachedStatuses[$aIdentity]))
			{
				$this->doUpdateCachedStatus($aIdentity, enmIMStatus::imsOffline);
				$this->doUpdateImStatus($aIdentity);
			}

			return $this->fCachedStatuses[$aIdentity];
		}
	}

	/**
	 * Preloads statuses into cache array
	 * @param array $aStatuses Statuses array in form of identity => status_code
	 */
	public function preloadStatuses($aStatuses)
	{
		if(is_array($aStatuses) && !empty($aStatuses))
			foreach($aStatuses as $lIdentity => $lStatus)
				if(is_numeric($lStatus) && $this->checkImIdentity($lIdentity))
					$this->doUpdateCachedStatus($lIdentity, $lStatus);
	}

	/**
	 * Returns last error code
	 * @return int
	 * @see class enmImError
	 */
	public function getLastError()
	{
		return $this->fLastError;
	}
}

//----------------------------------------------------------------------------//

?>
