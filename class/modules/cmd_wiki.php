<?php
	$From = Utils::GetFrom($From);
	$Text = Utils::GetText($ModuleName, $Text);

	if($Text !== false)
	{
		$Lang = Utils::GetJson('config/Wiki.json');

		if(!empty($Lang['lang']))
			Wikipedia::SetLang($Lang['lang']);

		$Result = Wikipedia::Search($Text);

		if($Result !== false)
		{
			$Title = $Result['title'];
			$Content = html_entity_decode(strip_tags($Result['content']));

			$Whatsapp->SendMessage($From, "{$Title}: {$Content}...");
		}
		else
			$Whatsapp->SendMessage($From, 'There is no results...');
	}
	else
		$Whatsapp->SendMessage($From, 'You must write something...');