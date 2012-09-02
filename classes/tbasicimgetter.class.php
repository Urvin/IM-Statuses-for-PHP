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

class enmIMStatus
{
	const imsOffline       = 0x00;
	const imsOnline        = 0x01;
	const imsAway          = 0x02;
	const imsDoNotDisturb  = 0x03;
	const imsNotAvailable  = 0x04;
	const imsFreeForChat   = 0x05;
}

class enmImError
{
	const imeNoError       = 0x00;
	const imeBadIdentity   = 0x01;
	const imeUnknownStatus = 0x02;
	const imeConnectionErr = 0x03;
}

//----------------------------------------------------------------------------//

abstract class tBasicIMGetter
{
	protected $fCDownloader;
	protected $fCachedStatuses;
	protected $fLastError;

	abstract protected function doUpdateImStatus($aIdentity);
	abstract protected function checkImIdentity($aIdentity);

	protected function doUpdateCachedStatus($aIdentity, $aStatus)
	{
		$this->fCachedStatuses[$aIdentity] = $aStatus;
	}

	public function __construct($aUseCurl = true)
	{
		$this->fCachedStatuses = array();
		$this->fLastError = enmImError::imeNoError;
		$this->fCDownloader = new tContentDownloader($aUseCurl);
	}

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

	public function preloadStatuses($aStatuses)
	{
		foreach($aStatuses as $lIdentity => $lStatus)
			$this->fCachedStatuses[$lIdentity] = $lStatus;
	}
}

//----------------------------------------------------------------------------//

?>
