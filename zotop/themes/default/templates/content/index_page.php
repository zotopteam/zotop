{content cid="$category.id" size="1" return="data"/}

{if $row = reset($data)}
	{php header("HTTP/1.1 301 Moved Permanently")}
	{php header("location:".$row['url'])}
	{php exit}
{/if}