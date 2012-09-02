<?php
/*
 * PROJECT:           IM Statuses Engine
 * FILE DESCRIPTION:  Example project
 * VERSION:           1.0
 * CREATED:           29.08.2012
 *
 * AUTHOR:            Yuriy 'Urvin' Gorbachev
 * EMAIL:             urvindt@gmail.com
 *
 */

//----------------------------------------------------------------------------//

require_once 'classes/ticqstatusgetter.class.php';
require_once 'classes/tjabberstatusgetter.class.php';
require_once 'classes/tmrastatusgetter.class.php';
require_once 'classes/tskypestatusgetter.class.php';
require_once 'classes/tvkstatusgetter.class.php';

//----------------------------------------------------------------------------//

$lIMStatusesInText = array(
	enmIMStatus::imsOffline       => 'Offline',
	enmIMStatus::imsOnline        => 'Online',
	enmIMStatus::imsAway          => 'Away',
	enmIMStatus::imsDoNotDisturb  => 'Do not disturb',
	enmIMStatus::imsNotAvailable  => 'Available',
	enmIMStatus::imsFreeForChat   => 'Free for chat'
);

//----------------------------------------------------------------------------//

$lImStatusGetters = array();
$lImStatusGetters['icq']           = new tICQStatusGetter();
$lImStatusGetters['jabber']        = new tJabberStatusGetter();
$lImStatusGetters['mail.ru agent'] = new tMRAStatusGetter();
$lImStatusGetters['skype']         = new tSkypeStatusGetter();
$lImStatusGetters['vkontakte']     = new tVKStatusGetter();

$lImIdentificators = array(
	'icq'           => '224052196',
	'jabber'        => '42d533481a045cbb8463dc358c8f5076',
	'mail.ru agent' => 'urvin-dt@mail.ru',
	'skype'         => 'urvin-dt',
	'vkontakte'     => 'sampritopal',
);

//----------------------------------------------------------------------------//

foreach($lImStatusGetters as $lKey => &$lGetter)
	echo $lKey, ': ', $lIMStatusesInText[$lGetter->getImStatus($lImIdentificators[$lKey])], '<br>', PHP_EOL;

//----------------------------------------------------------------------------//

?>