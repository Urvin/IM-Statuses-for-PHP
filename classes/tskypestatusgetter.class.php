<?php
/*
 * PROJECT:           IM Statuses Engine
 * FILE DESCRIPTION:  Skype IM Status Getter class
 * VERSION:           1.0
 * CREATED:           29.08.2012
 *
 * AUTHOR:            Yuriy 'Urvin' Gorbachev
 * EMAIL:             urvindt@gmail.com
 *
 */

//----------------------------------------------------------------------------//

require_once 'tbasicimgetter.class.php';

//----------------------------------------------------------------------------//

class tSkypeStatusGetter extends tBasicIMGetter
{
	protected function checkImIdentity($aIdentity)
	{
		return !empty($aIdentity) ? trim($aIdentity) : false;
	}

	protected function doUpdateImStatus($aIdentity)
	{
		$lContents = $this->fCDownloader->getURLContents('http://mystatus.skype.com/' . $aIdentity . '.txt');
		if(!empty($lContents))
		{
			$lGotStatus = false;
			if(strstr($lContents, 'Online'))
				$lGotStatus = enmIMStatus::imsOnline;
			elseif(strstr($lContents, 'Offline'))
				$lGotStatus = enmIMStatus::imsOffline;
			elseif(strstr($lContents, 'Away'))
				$lGotStatus = enmIMStatus::imsAway;
			elseif(strstr($lContents, 'Do Not Disturb'))
				$lGotStatus = enmIMStatus::imsDoNotDisturb;

			if($lGotStatus !== false)
			{
				$this->fLastError = enmImError::imeNoError;
				$this->doUpdateCachedStatus($aIdentity, $lGotStatus);
			}
			else
			{
				$this->fLastError = enmImError::imeUnknownStatus;
			}
		}
		else
			$this->fLastError = enmImError::imeConnectionErr;
	}
}

//----------------------------------------------------------------------------//
?>