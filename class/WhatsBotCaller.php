<?php
	require_once 'WhatsappBridge.php';
	require_once 'ModuleManager.php';
	require_once 'Utils.php';

	class WhatsBotCaller
	{
		private $Whatsapp = null;

		private $ModuleManager = null;

		public function __construct(WhatsappBridge $Whatsapp, ModuleManager $ModuleManager)
		{
			$this->Whatsapp = $Whatsapp;
			$this->ModuleManager = $ModuleManager;
		}

		public function CallModule($ModuleName, $Filename, $Params, $Me, $ID, $Time, $From, $Name, $Text) // Cambiar orden
		{
			$Whatsapp = $this->Whatsapp;
			$ModuleManager = $this->ModuleManager;

			return include $Filename;
		}
		
		public function CallDomainModule($ModuleName, $Filename, $ParsedURL, $URL, $Me, $ID, $Time, $From, $Name, $Text) // Cambiar orden
		{
			$Whatsapp = $this->Whatsapp;

			return include $Filename;
		}

		public function CallExtensionModule($ModuleName, $Filename, $Me, $From, $ID, $Type, $Time, $Name, $Text, $URL, $ParsedURL)
		{
			$Whatsapp = $this->Whatsapp;

			return include $Filename;
		}

		public function CallMediaModule($ModuleName, $Filename, $Me, $From, $ID, $Type, $Time, $Name, Array $Data)
		{
			$Whatsapp = $this->Whatsapp;

			extract($Data);

			return include $Filename;
		}
	}