<?php
	class TempFile
	{
		protected $Filename = null;

		public function __construct($Extension)
		{
			$this->Filename = $this->GenerateFilename($Extension);
			$this->Create();
		}

		protected function GenerateFilename($Extension)
		{
			$Filename = str_replace('.', '', str_replace(' ', '', microtime()));
			$Filename = "tmp/{$Filename}.{$Extension}";

			if(is_file($Filename))
				return $this->GenerateFilename($Extension);

			return $Filename;
		}

		protected function Create()
		{
			if(!is_file($this->Filename))
				if(file_put_contents($this->Filename, null) !== false)
					return true;

			return true;
		}

		public function Delete()
		{
			if(is_file($this->Filename))
				if(unlink($this->Filename))
					return true;

			return false;
		}

		public function Clean()
		{
			if(is_file($this->Filename) && is_writable($this->Filename))
				if(file_put_contents($this->Filename, null) !== false)
					return true;

			return false;
		}

		public function Read()
		{
			if(is_file($this->Filename) && is_readable($this->Filename))
				return file_get_contents($this->Filename);

			return false;
		}

		public function Write($Data)
		{
			$OriginalData = $this->Read();

			if($Data !== false)
				if(is_writable($this->Filename))
					if(file_put_contents($this->Filename, $OriginalData . $Data) == strlen($Data))
						return true;

			return false;
		}

		public function GetFilename()
		{
			return $this->Filename;
		}

		// Destruct => Delete()
	}