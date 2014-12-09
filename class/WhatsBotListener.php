<?php
	require_once 'WhatsBotParser.php';
	require_once 'Utils.php';

	class WhatsBotListener
	{
		private $Whatsapp = null;
		private $Parser = null;

		private $DB = null;

		private $StartTime = null;

		public function __construct(WhatsProt $Whatsapp, WhatsBotDB $DB)
		{
			$this->Whatsapp = $Whatsapp;
			$this->Parser = new WhatsBotParser($Whatsapp, $DB);
			$this->DB = $DB;

			$this->StartTime = time();
		}

		// To do: Log every event to database

		public function onConnect($Me, $Socket)
		{
			Utils::Write('Connected...');
		}

		public function onGetMessage($Me, $From, $ID, $Type, $Time, $Name, $Text)
		{
			$this->DB->InsertMessage($Me, $From, $ID, $Type, $Time, $Name, $Text);

			if($Time > $this->StartTime)
				$this->Parser->ParseTextMessage($Me, null, $From, $ID, $Type, $Time, $Name, $Text);
		}

		public function onGetGroupMessage($Me, $GroupID, $From, $ID, $Type, $Time, $Name, $Text)
		{
			$this->DB->InsertGroupMessage($Me, $GroupID, $From, $ID, $Type, $Time, $Name, $Text);

			if($Time > $this->StartTime)
				$this->Parser->ParseTextMessage($Me, $GroupID, $From, $ID, $Type, $Time, $Name, $Text);
		}

		public function onGetAudio($Me, $From, $ID, $Type, $Time, $Name, $Size, $URL, $File, $MIME, $Hash, $Duration, $Codec)
		{
			// Log to DB

			if($Time > $this->StartTime)
				$this->Parser->ParseMediaMessage
				(
					$Me,
					$From,
					$ID,
					$Type,
					'audio',
					$Time,
					$Name,
					array
					(
						'Size' => $Size,
						'URL' => $URL,
						'File' => $File,
						'MIME' => $MIME,
						'Hash' => $Hash,
						'Duration' => $Duration,
						'Codec' => $Codec
					)
				);
		}

		public function onGetImage($Me, $From, $ID, $Type, $Time, $Name, $Size, $URL, $File, $MIME, $Hash, $Width, $Height, $Preview, $Caption)
		{
			// Log to DB

			if($Time > $this->StartTime)
				$this->Parser->ParseMediaMessage
				(
					$Me,
					$From,
					$ID, 
					$Type,
					'image',
					$Time,
					$Name,
					array
					(
						'Size' => $Size,
						'URL' => $URL,
						'File' => $File,
						'MIME' => $MIME,
						'Hash' => $Hash,
						'Width' => $Width,
						'Height' => $Height,
						'Preview' => $Preview,
						'Caption' => $Caption
					)
				);
		}

		public function onGetReceipt($From, $ID, $Offline, $Retry)
		{
			$this->Whatsapp->sendPong($ID);
		}

		public function onGetVideo($Me, $From, $ID, $Type, $Time, $Name, $URL, $File, $Size, $MIME, $Hash, $Duration, $VCodec, $ACodec, $Preview, $Caption)
		{
			// Log to DB

			if($Time > $this->StartTime)
				$this->Parser->ParseMediaMessage
				(
					$Me,
					$From,
					$ID,
					$Type,
					'video',
					$Time,
					$Name,
					array
					(
						'URL' => $URL,
						'File' => $File,
						'Size' => $Size,
						'MIME' => $MIME,
						'Hash' => $Hash,
						'Duration' => $Duration,
						'VCodec' => $VCodec,
						'ACodec' => $ACodec,
						'Preview' => $Preview,
						'Caption' => $CaptionS
					)
				);
		}

		public function onLogin($Me)
		{
			Utils::Write('Logged in...');
		}

		/* Events: 
			onClose($mynumber, $error)
			onCodeRegister( $mynumber, $login, $password, $type, $expiration, $kind, $price, $cost, $currency, $price_expiration )
			onCodeRegisterFailed( $mynumber, $status, $reason, $retry_after )
			onCodeRequest( $mynumber, $method, $length )
			onCodeRequestFailed( $mynumber, $method, $reason, $param )
			onCodeRequestFailedTooRecent( $mynumber, $method, $reason, $retry_after )
			onConnectError( $mynumber, $socket )
			onCredentialsBad( $mynumber, $status, $reason )
			onCredentialsGood( $mynumber, $login, $password, $type, $expiration, $kind, $price, $cost, $currency, $price_expiration )
			onDisconnect( $mynumber, $socket ) 
			onDissectPhone( $mynumber, $phonecountry, $phonecc, $phone, $phonemcc, $phoneISO3166, $phoneISO639, $phonemnc ) 
			onDissectPhoneFailed( $mynumber )
			onGetBroadcastLists( $mynumber, $broadcastLists )
			onGetError( $mynumber, $id, $data )
			onGetExtendAccount( $mynumber, $kind, $status, $creation, $expiration )
			onGetGroupParticipants( $mynumber, $groupId, $groupList )
			onGetGroups( $mynumber, $groupList )
			onGetGroupsInfo( $mynumber, $groupList )
			onGetGroupsSubject( $mynumber, $group_jid, $time, $author, $name, $subject )
			onGetLocation( $mynumber, $from, $id, $type, $time, $name, $name, $longitude, $latitude, $url, $preview )
			onGetNormalizedJid( $mynumber, $data )
			onGetPrivacyBlockedList( $mynumber, $data )
			onGetProfilePicture( $mynumber, $from, $type, $data )
			onGetReceipt( $from, $id, $offline, $retry )
			onGetRequestLastSeen( $mynumber, $from, $id, $seconds )
			onGetServerProperties( $mynumber, $version, $props )
			onGetServicePricing( $mynumber, $price, $cost, $currency, $expiration )
			onGetStatus( $mynumber, $from, $requested, $id, $time, $data )
			onGetSyncResult( $result )
			onGetvCard( $mynumber, $from, $id, $type, $time, $name, $vcardname, $vcard )
			onGroupCreate( $mynumber, $groupId )
			onGroupisCreated( $mynumber, $creator, $gid, $subject )
			onGroupsChatCreate( $mynumber, $gid )
			onGroupsChatEnd( $mynumber, $gid )
			onGroupsParticipantsAdd( $mynumber, $groupId, $jid )
			onGroupsParticipantsRemove( $mynumber, $groupId, $jid)
			onLoginFailed( $mynumber, $data )
			onMediaMessageSent( $mynumber, $to, $id, $filetype, $url, $filename, $filesize, $filehash, $caption, $icon )
			onMediaUploadFailed( $mynumber, $id, $node, $messageNode, $statusMessage )
			onMessageComposing( $mynumber, $from, $id, $type, $time )
			onMessagePaused( $mynumber, $from, $id, $type, $time )
			onMessageReceivedClient( $mynumber, $from, $id, $type, $time )
			onMessageReceivedServer( $mynumber, $from, $id, $type, $time )
			onPaidAccount( $mynumber, $author, $kind, $status, $creation, $expiration )
			onPing( $mynumber, $id )
			onPresence( $mynumber, $from, $status )
			onProfilePictureChanged( $mynumber, $from, $id, $time )
			onProfilePictureDeleted( $mynumber, $from, $id, $time )
			onSendMessage( $mynumber, $target, $messageId, $node )
			onSendMessageReceived( $mynumber, $id, $from, $type )
			onSendPong( $mynumber, $msgid )
			onSendPresence( $mynumber, $type, $name )
			onSendStatusUpdate( $mynumber, $txt )
			onStreamError( $data )
			onUploadFile( $mynumber, $filename, $url )
			onUploadFileFailed( $mynumber, $filename )
		*/
	}