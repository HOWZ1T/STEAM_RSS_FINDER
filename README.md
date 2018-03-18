# STEAM_RSS_FINDER
This PHP CLI script lets you easily query Steam via a search phrase and attempts to retrieve the RSS feed for the most relevant results.

## FLAGS:
- -r [optional flag]the raw flag for output, outputting just the links without * formatting
### Example:
php -f find_rss.php arma 3 -r

## MULTI-QUERY:
Multi-Query support via / as the delimeter.
### Example:
php -f find_rss.php arma 3 / dota / call of duty black ops 3

## General Example:
php -f find_rss.php arma 3
