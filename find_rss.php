<?php
	/*This script attempts to find and grab an rss feed for the given search phrase*/
	
	function search($term, $returnAll=false)
	{
		if($term == null || strlen($term) <= 0)
		{
			echo "missing the search parameter!";
			return null;
		}

		//create steam search url
		$URL = "http://store.steampowered.com/search/?term=".$term;
		echo "requesting response from: ".$URL."\n";
		$res = file_get_contents($URL);
		//use regex to grab links from html response
		preg_match_all('/.*<a href=\"http:\/\/store\.steampowered\.com\/app\/.*/', $res, $out);

		$parsed_links = array();
		foreach($out[0] as $key => $raw_link)
		{
			$link = explode('"', $raw_link)[1];
			array_push($parsed_links, $link);
		}

		if(count($parsed_links) <= 0)
		{
			echo "\nno links found\n\n";
			return null;
		}

		echo "gathered links:\n";
		foreach($parsed_links as $key => $link)
		{
			echo $link."\n";
		}
		echo "most relevant:\n".$parsed_links[0]."\n\n";

		if($returnAll == true)
		{
			return $parsed_links;
		}

		$GLOBALS['name'] = str_replace("_", " ", explode("/", $parsed_links[0])[5]);
		return $parsed_links[0];
	}

	function getUpdateLink($item_link)
	{
		if($item_link == null || strlen($item_link) <= 0)
		{
			echo "missing the item_link parameter!";
			return null;
		}
		
		echo "requesting response from: ".$item_link."\n";
		$res = file_get_contents($item_link);
		
		preg_match_all('/.*<a href=\"http:\/\/steamcommunity\.com\/gid\/.*/', $res, $out);

		$parsed_links = array();
		foreach($out[0] as $key => $raw_link)
		{
			$link = explode('"', $raw_link)[1];
			array_push($parsed_links, $link);
		}

		if(count($parsed_links) <= 0)
		{
			echo "no links found\n\n";
			return null;
		}

		echo "gathered links:\n";
		foreach($parsed_links as $key => $link)
		{
			echo $link."\n";
		}
		echo "most relevant:\n".$parsed_links[0]."\n\n";

		return $parsed_links[0];
	}

	function getRSS($item_link)
	{
		if($item_link == null || strlen($item_link) <= 0)
		{
			echo "missing the item_link parameter!";
			return null;
		}
		
		echo "requesting response from: ".$item_link."\n";
		$res = file_get_contents($item_link);
		
		preg_match_all('/.*<a.*href=\"http:\/\/steamcommunity\.com\/games\/.*\/rss\/.*/', $res, $out);
		$parsed_links = array();
		foreach($out[0] as $key => $raw_link)
		{
			$link = explode('"', $raw_link)[1];
			array_push($parsed_links, $link);
		}

		if(count($parsed_links) <= 0)
		{
			echo "no links found\n\n";
			return null;
		}

		echo "gathered links:\n";
		foreach($parsed_links as $key => $link)
		{
			echo $link."\n";
		}
		echo "most relevant:\n".$parsed_links[0]."\n\n";

		return $parsed_links[0];
	}

	function bypassAgeGate($item_link)
	{
		if($item_link == null || strlen($item_link) <= 0)
		{
			echo "missing the item_link parameter!";
			return null;
		}
		
		echo "checking for agegate...\n";
		echo "requesting response from: ".$item_link."\n";
		$res = file_get_contents($item_link);
		preg_match_all('/.*agegate.*/', $res, $out);
		if(!($out != null && count($out) > 0))
		{
			echo "\n\nagegate not found!\n\n";
			return null;
		}
		echo "agegate found\n";
		echo "attempting to bypass...\n";
		echo "setting cookie...\n";
		$curl = curl_init($item_link);
		curl_setopt($curl, CURLOPT_VERBOSE, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_COOKIE, 'mature_content=1; birthtime=631112401; lastagecheckage=1-January-1990; timezoneOffset=39600,0');
		echo "requesting response via curl from: ".$item_link."\n";
		$page = curl_exec($curl);
		curl_close($curl);
		
		if($page == false)
		{
			echo "\n\ncurl request failed!\n\n";
			return null;
		}
		
		echo "\n"; //clear curl verbose output
		//get link
		$out = null;
		preg_match_all('/.*<a href=\"http:\/\/steamcommunity\.com\/gid\/.*/', $page, $out);

		$parsed_links = array();
		foreach($out[0] as $key => $raw_link)
		{
			$link = explode('"', $raw_link)[1];
			array_push($parsed_links, $link);
		}

		if(count($parsed_links) <= 0)
		{
			echo "no links found\n\n";
			return null;
		}

		echo "gathered links:\n";
		foreach($parsed_links as $key => $link)
		{
			echo $link."\n";
		}
		echo "most relevant:\n".$parsed_links[0]."\n\n";

		return $parsed_links[0];
	}

	function getRSSviaCommunity($item_link)
	{
		echo "attempting to get rss feed via community hub...\n";
		if($item_link == null || strlen($item_link) <= 0)
		{
			echo "missing the item_link parameter!";
			return null;
		}
		
		echo "checking for agegate...\n";
		echo "requesting response from: ".$item_link."\n";
		$res = file_get_contents($item_link);
		preg_match_all('/.*agegate.*/', $res, $out);
		if(!($out != null && count($out) > 0))
		{
			echo "\n\nagegate not found!\n\n";
			return null;
		}
		echo "agegate found\n";
		echo "attempting to bypass...\n";
		echo "setting cookie...\n";
		$curl = curl_init($item_link);
		curl_setopt($curl, CURLOPT_VERBOSE, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_COOKIE, 'mature_content=1; birthtime=631112401; lastagecheckage=1-January-1990; timezoneOffset=39600,0');
		echo "requesting response via curl from: ".$item_link."\n";
		$page = curl_exec($curl);
		curl_close($curl);
		if($page == false)
		{
			echo "\n\ncurl request failed!\n\n";
			return null;
		}
		
		echo "\nattempting to retrieve community link...\n"; //clear curl verbose output
		//get community link
		$out = null;
		preg_match_all('/.*<a.*href=\"http:\/\/steamcommunity\.com\/app\/.*\">/', $page, $out);
		$parsed_links = array();
		foreach($out[0] as $key => $raw_link)
		{
			$link = explode('"', $raw_link)[3];
			array_push($parsed_links, $link);
		}

		if(count($parsed_links) <= 0)
		{
			echo "no links found\n\n";
			return null;
		}

		$community_link = $parsed_links[0];
		
		if($community_link == false || $community_link == null)
		{
			echo "\n\ncould not find community link!\n\n";
			return null;
		}
		
		$out = null;
		echo "found community link: ".$community_link."\n";
		echo "requesting response from: ".$community_link."\n";
		$res = file_get_contents($community_link);
		echo "\nattempting to retrieve news iframe page link...\n"; //clear curl verbose output
		//get news page link
		preg_match_all('/.*<a.*href=\"http:\/\/steamcommunity\.com\/app\/.*\/all.*/', $res, $out);
		$parsed_links = array();
		foreach($out[0] as $key => $raw_link)
		{
			$link = explode('"', $raw_link)[1];
			array_push($parsed_links, $link);
		}

		if(count($parsed_links) <= 0)
		{
			echo "no links found\n\n";
			return null;
		}
		
		$out = null;
		$news_link = $parsed_links[0];
		echo "found news iframe page link: ".$news_link."\n";
		echo "attempting to retrieve rss page link...\n";
		
		$res = file_get_contents($news_link);
		//check for age gate
		$hasAgeGate = false;
		echo "checking for agegate...\n";
		echo "requesting response from: ".$news_link."\n";
		$res = file_get_contents($news_link);
		preg_match_all('/.*agegate.*/', $res, $out);		
		$page = $res;
		if(!($out != null && count($out) > 0))
		{
			echo "agegate not found!\n";
		}
		else
		{
			echo "agegate found\n";
			echo "attempting to bypass...\n";
			echo "setting cookie...\n";
			$curl = curl_init($news_link);
			curl_setopt($curl, CURLOPT_VERBOSE, 1);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_COOKIE, 'mature_content=1; birthtime=631112401; lastagecheckage=1-January-1990; timezoneOffset=39600,0');
			echo "requesting response via curl from: ".$news_link."\n";
			$page = curl_exec($curl);
			curl_close($curl);

			if($page == false)
			{
				echo "\n\ncurl request failed!\n\n";
				return null;
			}	
		}
		
		preg_match_all('/.*data-modal-content-url=\"http:\/\/steamcommunity.com\/gid\/.*\"/', $page, $out);
		$parsed_links = array();
		foreach($out[0] as $key => $raw_link)
		{
			$link = explode('"', $raw_link)[5];
			array_push($parsed_links, $link);
		}

		if(count($parsed_links) <= 0)
		{
			echo "no links found\n\n";
			return null;
		}
		
		$out = null;
		$rss_link = $parsed_links[0];
		echo "found rss link: ".$rss_link."\n";
		return $rss_link;
	}

	$term = "";
	for($i = 1; $i < count($argv); $i++)
	{
		$term .= $argv[$i]."%20";
	}
	$term = strtolower(substr($term, 0, strlen($term)-3));
	echo "search term = ".$term."\n";
	
	$page_link = search($term);
	if($page_link == null)
	{
		$ps = "*          NO LINKS FOUND :(          *";
		$stars = "";
		for($i = 0; $i < strlen($ps); $i++)
		{
			$stars .= "*";
		}
		die("\n\n\n$stars\n$ps\n$stars\n\n\n");
	}

	$result = getUpdateLink($page_link);
	if($result == null) {$result = bypassAgeGate($page_link);}
	if($result == null) {$result = getRSSviaCommunity($page_link);}
	$result = getRSS($result);

	if($result == null)
	{
		$ps = "* NO RSS FEED FOUND :(                   *";
		$ns = "* {$GLOBALS['name']} ";
		$pl = strlen($ps);
		$nl = strlen($ns);
		$diff = 0;
		if($nl >= $pl)
		{
			$diff = $nl - $pl;
		}
		else
		{
			$diff = $pl - $nl;
		}
		for($i = 0; $i < $diff-1; $i++)
		{
			$ns .= ' ';
		}
		$ns .= '*';
		$stars = "";
		for($i = 0; $i < strlen($ps); $i++)
		{
			$stars .= "*";
		}
		die("\n\n\n$stars\n$ns\n$stars\n$ps\n$stars\n\n\n");
	}
	else
	{
		$ps = "*     RSS FEED: ".$result."     *";
		$ns = "* {$GLOBALS['name']} ";
		$pl = strlen($ps);
		$nl = strlen($ns);
		$diff = 0;
		if($nl >= $pl)
		{
			$diff = $nl - $pl;
		}
		else
		{
			$diff = $pl - $nl;
		}
		for($i = 0; $i < $diff-1; $i++)
		{
			$ns .= ' ';
		}
		$ns .= '*';
		$stars = "";
		for($i = 0; $i < strlen($ps); $i++)
		{
			$stars .= "*";
		}
		echo "\n\n\n$stars\n$ns\n$stars\n$ps\n$stars\n\n\n";
	}

	//TODO Add multiple query support
?>
