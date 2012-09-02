<?php
/*
 * PROJECT:           IM Statuses Engine
 * FILE DESCRIPTION:  MRA IM Status Getter class
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

class tMRAStatusGetter extends tBasicIMGetter
{
	protected function checkImIdentity($aIdentity)
	{
		return !empty($aIdentity) && preg_match('#(.*?)@mail\.ru#si', $aIdentity) ? trim($aIdentity) : false;
	}

	protected function doUpdateImStatus($aIdentity)
	{
		$lContents = $this->fCDownloader->getURLContents('http://status.mail.ru/?' . $aIdentity);
		if(!empty($lContents))
		{
			$lGotStatus = false;
			$lContents = md5($lContents);

			switch($lContents)
			{
				case '0318014f28082ac7f2806171029266ef':
					$lGotStatus = enmIMStatus::imsOnline;
					break;
				case '89d1bfcdbf238e7faa6aeb278c27b676':
					$lGotStatus = enmIMStatus::imsAway;
					break;
				case 'a46f044e175e9b1b28c8d9a9f66f4495':
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