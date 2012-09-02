<?php
/*
 * PROJECT:           IM Statuses Engine
 * FILE DESCRIPTION:  ICQ IM Status Getter class
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

class tICQStatusGetter extends tBasicIMGetter
{
	protected function checkImIdentity($aIdentity)
	{
		return !empty($aIdentity) && is_numeric($aIdentity) && (intval($aIdentity) > 10000) ? intval($aIdentity) : false;
	}

	protected function doUpdateImStatus($aIdentity)
	{
		$lContents = $this->fCDownloader->getURLContents('http://status.icq.com/online.gif?icq=' . $aIdentity . '&img=27', true);
		if(!empty($lContents))
		{
			$lGotStatus = false;
			if(strstr($lContents, 'online1'))
				$lGotStatus = enmIMStatus::imsOnline;
			elseif(strstr($lContents, 'online0'))
				$lGotStatus = enmIMStatus::imsOffline;
			elseif(strstr($lContents, 'online2'))
				$lGotStatus = enmIMStatus::imsAway;

			if($lGotStatus !== false)
			{
				$this->fLastError = enmImError::imeNoError;
				$this->doUpdateCachedStatus($aIdentity, $lGotStatus);
			}
			else
				$this->fLastError = enmImError::imeUnknownStatus;
		}
		else
			$this->fLastError = enmImError::imeConnectionErr;
	}
}

//----------------------------------------------------------------------------//
?>