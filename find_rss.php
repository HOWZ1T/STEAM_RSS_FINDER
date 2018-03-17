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
		echo "Most relevant:\n".$parsed_links[0]."\n\n";

		if($returnAll == true)
		{
			return $parsed_links;
		}

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
		echo "Most relevant:\n".$parsed_links[0]."\n\n";

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
		
		preg_match_all('/.*<a href=\"http:\/\/steamcommunity\.com\/games\/.*\/rss\/.*/', $res, $out);

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
		echo "Most relevant:\n".$parsed_links[0]."\n\n";

		return $parsed_links[0];
	}

	$term = "";
	for($i = 1; $i < count($argv); $i++)
	{
		$term .= $argv[$i]."%20";
	}
	$term = strtolower(substr($term, 0, strlen($term)-3));
	echo "search term = ".$term."\n";
	
	$result = getRSS(getUpdateLink(search($term)));


	if($result == null)
	{
		echo "\n\n\n*************************\n* NO RSS FEED FOUND! :( *\n*************************\n\n\n";
	}
	else
	{
		$ps = "* RSS FEED: ".$result." *";
		$stars = "";
		for($i = 0; $i < strlen($ps); $i++)
		{
			$stars .= "*";
		}
		echo "\n\n\n$stars\n$ps\n$stars\n\n\n";
	}
?>
