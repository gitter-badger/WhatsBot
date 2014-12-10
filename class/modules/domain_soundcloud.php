<?php
	/* To do. 
	 * If you have already the video size and hash, you can send it without re-uploading it to whatsapp servers
	 * $w->sendMessageVideo($target, $filepath, false, $fsize, $fhash, $caption);
	 */

	require_once 'class/Utils/TempFile.php';

	$From = Utils::GetFrom($From);

	$Error = true;

	$Config = Utils::GetJson('config/soundcloud.json');

	if($Config !== false && !empty($Config['endpoint']) && !empty($Config['clientid']))
	{
		$RequestURL = "{$Config['endpoint']}resolve.json?client_id={$Config['clientid']}&url={$URL}";

		$Track = Utils::GetRemoteJson($RequestURL, 302);

		if($Track !== false && isset($Track['kind']) && $Track['kind'] == 'track' && isset($Track['id']) && is_int($Track['id']))
		{
			$RequestURL = "{$Config['endpoint']}i1/tracks/{$Track['id']}/streams?client_id={$Config['clientid']}";

			$Streams = Utils::GetRemoteJson($RequestURL, 200);

			if($Streams !== false)
			{
				if(isset($Streams['http_mp3_128_url']))
				{
					$Data = file_get_contents($Streams['http_mp3_128_url']);

					if($Data !== false && strlen($Data) > 0)
					{
						$File = new TempFile('mp3');

						if($File->Write($Data))
							if($Whatsapp->SendAudioMessage($From, $File->GetFilename()))
								$Error = false;

						//$File->Delete();
					}
				}
				elseif(isset($Streams['hls_mp3_128_url']))
				{
					$Playlist = Utils::GetRemoteFile($Streams['hls_mp3_128_url'], 200);
					$Playlist = Utils::GetURLs($Playlist);

					if($Playlist !== false)
					{
						$Continue = true;
						$Data = '';

						foreach($Playlist as $URL)
						{
							$D = Utils::GetRemoteFile($URL);

							if($D !== false)
								$Data .= $D;
							else // Try again?
							{
								$Continue = false;
								break;
							}
						}

						if($Continue && strlen($Data) > 0)
						{
							$File = new TempFile('mp3');

							if($File->Write($Data))
								if($Whatsapp->SendAudioMessage($From, $File->GetFilename()))
									$Error = false;

							//$File->Delete();
						}
					}
				}
			}
		}
	}

	if($Error)
		$Whatsapp->SendMessage($From, 'Can\'t download track... Try with another song :)');