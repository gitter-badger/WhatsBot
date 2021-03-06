<?php
	require_once 'ThreadModel.php';

	define('WHATSBOT_WHATSAPP_TASK', 1);
	define('WHATSBOT_MODULEMANAGER_TASK', 2);
	//define('WHATSBOT_UPDATE_TASK', 3);

	class ThreadManager
	{
		private $Threads = array();

		private $Whatsapp = null;
		private $ModuleManager = null;

		public function __construct(WhatsappBridge &$Whatsapp, ModuleManager &$ModuleManager)
		{
			$this->Whatsapp = &$Whatsapp;
			$this->ModuleManager = &$ModuleManager;
		}

		public function LoadThreads()
		{
			if(extension_loaded('pthreads'))
			{
				$Threads = Utils::GetJson('config/Threads.json');

				if(is_array($Threads))
				{
					foreach($Threads as $Thread)
						$this->LoadThread($Thread);

					foreach($this->Threads as $Name => $Thread)
						$this->StartThread($Name);

					Utils::Write('Threads loaded...');
					Utils::WriteNewLine();
				}
			}
			else
			{
				Utils::Write('Can\'t load Threads. You have to install PThreads extension. ');
				Utils::Write('See http://php.net/manual/en/pthreads.installation.php');
				Utils::Write('Windows builds: http://windows.php.net/downloads/pecl/releases/pthreads/');
				Utils::WriteNewLine();
			}
		}

		protected function LoadThread($Name)
		{
			$Filename = "class/threads/{$Name}.php";

			if(is_file($Filename) && is_readable($Filename))
			{
				include_once $Filename;

				$ClassName = "Thread_{$Name}";

				if(class_exists($ClassName))
				{
					$this->Threads[$Name][0] = new $ClassName();
					$this->Threads[$Name][1] = false;

					if(!($this->Threads[$Name][0] instanceof Thread))
					{
						Utils::Write('Class "' . get_class($this->Threads[$Name][0]) . '" must be inherited from Thread to work...');

						unset($this->Threads[$Name]);
					}
					elseif(!in_array('WhatsBotThread', class_uses($this->Threads[$Name][0])))
						Utils::Write('Class "' . get_class($this->Threads[$Name][0]) . '" must use WhatsBotThread (trait). The thread will be loaded, but Whatsapp related functions will be not available...');
					else
						$this->Threads[$Name][1] = true;
				}
				else
					Utils::Write("Class {$ClassName} does not exists. The thread's class at file " . realpath($Filename) . ", must be named {$ClassName}...");
			}
		}

		protected function StartThread($Name)
		{
			$this->Threads[$Name][0]->start(PTHREADS_ALLOW_GLOBALS | PTHREADS_INHERIT_CONSTANTS); // Trait doesn't work with PTHREADS_INHERIT_ALL or PTHREADS_INHERIT_CLASSES => Static $Tasks context? ._.
		}

		public function ExecuteTasks()
		{
			foreach($this->Threads as $Thread)
			{
				if($Thread[1])
				{
					$Tasks = $Thread[0]->GetTasks();

					foreach($Tasks as $Task)
						switch($Task[0])
						{
							case WHATSBOT_WHATSAPP_TASK:
								Utils::CallFunction($this->Whatsapp, $Task[1], $Task[2]); // Protect __construct
								break;
							case WHATSBOT_MODULEMANAGER_TASK:
								Utils::CallFunction($this->ModuleManager, $Task[1], $Task[2]); // Protect __construct
								break;
							//case WHATSBOT_UPDATE_TASK:
							//	break;
							default:
								Utils::Write('Unknown task type. ');
								Utils::Write("Task type: {$Task[0]}");
								Utils::Write("Function: {$Task[1]}");
								Utils::Write('Params: ' . print_r($Task[2], true));
						}
				}
			}
		}
	}