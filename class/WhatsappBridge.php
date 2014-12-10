<?php
	require_once 'Utils.php';

	class WhatsappBridge
	{
		private $Whatsapp = null;

		public function __construct(WhatsProt $Whatsapp)
		{
			$this->Whatsapp = $Whatsapp;
		}

		public function SendMessage($To, $Message, $ID = null)
		{
			return $this->Whatsapp->sendMessage($To, $Message, $ID);
		}

		public function SendAudioMessage($To, $Filepath, $StoreURLMedia = false, $Filesize = 0, $Filehash = '')
		{
			return $this->Whatsapp->sendMessageAudio($To, $Filepath, $StoreURLMedia, $Filesize, $Filehash);
		}

		public function SendImageMessage($To, $Filepath, $Caption = '', $StoreURLMedia = false, $Filesize = 0, $Filehash = '')
		{
			return $this->Whatsapp->sendMessageImage($To, $Filepath, $StoreURLMedia, $Filesize, $Filehash, $Caption);
		}

		public function SetStatus($Status)
		{
			return $this->Whatsapp->sendStatusUpdate($Status);
		}

		/* Functions: 
			checkCredentials()
			codeRegister($code)
			codeRequest($method = 'sms', $countryCode = null, $langCode = null)
			connect()
			disconnect()
			eventManager()
			getMessages()
			login() // Don't use this one!
			loginWithPassword($password)
			pollMessage($autoReceipt = true)
			sendActiveStatus()
			sendBroadcastAudio($targets, $path, $storeURLmedia = false, $fsize = 0, $fhash = "")
			sendBroadcastImage($targets, $path, $storeURLmedia = false, $fsize = 0, $fhash = "", $caption = "")
			sendGetBroadcastLists()
			sendBroadcastLocation($targets, $long, $lat, $name = null, $url = null)
			sendBroadcastMessage($targets, $message)
			sendBroadcastVideo($targets, $path, $storeURLmedia = false, $fsize = 0, $fhash = "", $caption = "")
			sendClearDirty($categories)
			sendGetClientConfig()
			sendGetGroups()
			sendGetGroupsInfo($gjid)
			sendGetGroupsOwning()
			sendGetGroupsParticipants($gjid)
			sendGetNormalizedJid($countryCode, $number)
			sendGetPrivacyBlockedList()
			sendGetProfilePicture($number, $large = false)
			sendGetRequestLastSeen($to)
			sendGetServerProperties()
			sendGetServicePricing($lg, $lc)
			sendGetStatuses($jids)
			sendGroupsChatCreate($subject, $participants = array())
			sendSetGroupSubject($gjid, $subject)
			sendGroupsChatEnd($gjid)
			sendGroupsLeave($gjids)
			sendGroupsParticipantsAdd($groupId, $participants)
			sendGroupsParticipantsRemove($groupId, $participants)
			sendMessageComposing($to)
			sendMessagePaused($to)
			sendMessageVideo($to, $filepath, $storeURLmedia = false, $fsize = 0, $fhash = "", $caption = "")
			sendMessageLocation($to, $long, $lat, $name = null, $url = null)
			sendChatState($to, $state)
			sendNextMessage()
			sendOfflineStatus()
			sendPong($msgid)
			sendAvailableForChat($nickname = null)
			sendPing()
			sendPresence($type = "active")
			sendPresenceSubscription($to)
			sendSetGroupPicture($gjid, $path)
			sendSetPrivacyBlockedList($blockedJids = array())
			sendSetProfilePicture($path)
			sendSetRecoveryToken($token)
			sendVcard($to, $name, $vCard)
			sendBroadcastVcard($targets, $name, $vCard)
		*/
	}