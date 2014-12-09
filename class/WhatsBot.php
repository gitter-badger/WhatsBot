<?php
	require_once 'whatsapi/whatsprot.class.php';
	require_once 'WhatsBotListener.php';
	require_once 'DB/DB.php';
	require_once 'Utils.php';

	final class WhatsBot
	{
		private $Whatsapp = null;
		private $Listener = null;

		private $DB = null;

		private $Debug = false;

		public function __construct($Debug = false)
		{
			$this->Debug = $Debug;


			Utils::Write('Cleaning temp directory...');
			Utils::CleanTemp(); // If true
			Utils::Write('Temp directory cleaned...');

			Utils::WriteNewLine();

			$this->Init();
		}

		private function Init()
		{
			$Config = Utils::GetJson('config/WhatsBot.json');

			if($Config !== false && !empty($Config['database']['filename']) && !empty($Config['whatsapp']['username']) && !empty($Config['whatsapp']['identity']) && !empty($Config['whatsapp']['password']) && !empty($Config['whatsapp']['nickname'])) // and DB
			{
				$this->InitDB($Config['database']['filename']);

				$this->InitWhatsAPI
				(
					$Config['whatsapp']['username'],
					$Config['whatsapp']['identity'],
					$Config['whatsapp']['password'],
					$Config['whatsapp']['nickname']
				);
			}
			else
				exit('Can\'t load config...');
		}

		private function InitDB($Filename)
		{
			$this->DB = new WhatsBotDB($Filename);
		}

		private function InitWhatsAPI($Username, $Identity, $Password, $Nickname)
		{
			$this->Whatsapp = new WhatsProt
			(
				$Username,
				$Identity,
				$Nickname,
				$this->Debug
			);

			$this->Listener = new WhatsBotListener
			(
				$this->Whatsapp,
				$this->DB
			);


			$this->Whatsapp->eventManager()->SetDebug($this->Debug);
			$this->Whatsapp->eventManager()->BindClass($this->Listener);

			Utils::Write('Connecting...');
			$this->Whatsapp->connect();
			Utils::WriteNewLine();

			Utils::Write('Logging in...');
			$this->Whatsapp->loginWithPassword($Password);
			Utils::WriteNewLine();
		}

		public function Listen()
		{
			Utils::Write('Listening...');

			$StartTime = time();

			while(true)
			{
				$this->Whatsapp->pollMessage();

				if(time() >= $StartTime + 30)
				{
					$this->Whatsapp->sendPresence('active');
					$this->Whatsapp->sendPing();

					$StartTime = time();
				}
			}
		}
	}

	/* To do: 
	 * Make an parser for modules (With https://github.com/nikic/PHP-Parser ?)
	 * Flood detection / protection
	 * 
	 * https://github.com/mgp25/WhatsAPI-Official/issues/164#issuecomment-64790667
	 * Add syncing before send message (Array with numbers synceds? [IF DISCONNECT?])
	 * 
	 * Implement? https://github.com/mgp25/WhatsAPI-Official/issues/169
	 * 
	 * Only CLI use
	 */

	/* To do (new-structure): 
	 * Fix Utils::IsAdmin
	 * Fix !setstatus
	 * Test /soundcloud/
	 * Test !search (updated)
	 */

	/*
	 * Implement: https://github.com/mgp25/WhatsAPI-Official/wiki/WhatsAPI-Documentation#whatsapp-workflow
	 */

	// Agregar require_once para todo lo usado (Inclusive lo que no instanciamos nosotros).