<?php
	class WhatsApiEventsManager
	{
		private $Debug = false;

		private $Classes = array();

		public function SetDebug($Debug)
		{
			$this->Debug = $Debug;
		}

		public function BindClass(&$Class)
		{
			$this->Classes[] = &$Class;

			if($this->Debug)
				Utils::Write('Class ' . get_class($Class) . ' binded...');
		}

		public function fire($Event, Array $Params)
		{
			if($this->Debug)
				Utils::Write("Event fired: {$Event}");

			for($i = 0; $i < count($this->Classes); $i++)
				if(method_exists($this->Classes[$i], $Event) && is_callable(array($this->Classes[$i], $Event), true)) // To do: If method is private it returns true, fix!
					call_user_func_array(array($this->Classes[$i], $Event), $Params);
		}
	}