<?php
/*
 * PROJECT:           IM Statuses Engine
 * FILE DESCRIPTION:  VK IM Status Getter class
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

class tVKStatusGetter extends tBasicIMGetter
{
	protected function checkImIdentity($aIdentity)
	{
		return !empty($aIdentity) ? trim($aIdentity) : false;
	}

	protected function doUpdateImStatus($aIdentity)
	{
		$lContents = $this->fCDownloader->getURLContents('https://api.vkontakte.ru/method/getProfiles?uids=' . $aIdentity . '&fields=online');

		if(!empty($lContents))
		{
			$lGotStatus = false;
			$lJson = json_decode($lContents, true);
			if(!empty($lJson))
			{
				$lGotStatus = intval($lJson['response'][0]['online']) == 1 ? enmIMStatus::imsOnline : enmIMStatus::imsOffline;
				$this->fLastError = enmImError::imeNoError;
				$this->doUpdateCachedStatus($aIdentity, $lGotStatus);
			}
			else
				$this->fLastError = enmImError::imeConnectionErr;
		}
		else
			$this->fLastError = enmImError::imeConnectionErr;
	}
}

//----------------------------------------------------------------------------//
?>