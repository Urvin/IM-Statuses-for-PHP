<?php
/*
 * PROJECT:           IM Statuses Engine
 * FILE DESCRIPTION:  Jabber IM Status Getter class
 * VERSION:           1.0
 * CREATED:           29.08.2012
 *
 * AUTHOR:            Yuriy 'Urvin' Gorbachev
 * EMAIL:             urvindt@gmail.com
 *
 */
//----------------------------------------------------------------------------//

// For jabber identificator use http://presence.jabberfr.org/ hash generator

//----------------------------------------------------------------------------//

require_once 'tbasicimgetter.class.php';

//----------------------------------------------------------------------------//

class tJabberStatusGetter extends tBasicIMGetter
{
	protected function checkImIdentity($aIdentity)
	{
		return !empty($aIdentity) ? trim($aIdentity) : false;
	}

	protected function doUpdateImStatus($aIdentity)
	{
		$lContents = $this->fCDownloader->getURLContents('http://presence.jabberfr.org/' . $aIdentity . '/text-en.txt');
		
		if(!empty($lContents))
		{
			$lGotStatus = false;

			switch($lContents)
			{
				case 'Available';
					$lGotStatus = enmIMStatus::imsOnline;
					break;
				case 'Free for chat';
					$lGotStatus = enmIMStatus::imsFreeForChat;
					break;
				case 'Away';
					$lGotStatus = enmIMStatus::imsAway;
					break;
				case 'Not available';
					$lGotStatus = enmIMStatus::imsNotAvailable;
					break;
				case 'Do not disturb';
					$lGotStatus = enmIMStatus::imsDoNotDisturb;
					break;
				case 'Offline';
					$lGotStatus = enmIMStatus::imsOffline;
					break;
			}

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