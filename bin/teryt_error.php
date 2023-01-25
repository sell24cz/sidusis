<?php
    include("lib/main.php");
    include("oplaty.php");

    $resteryt = GetSQL("select  distinct miasto, ulica, dom, ulica2  from sort2 where TERC is null order by miasto, ulica, dom;");
    if( $resteryt )
    {
        while ( $wt = $resteryt->fetch_array() )
        {
    	    $miasto = $wt['miasto'];
	    $ulica = $wt['ulica'];
	    $dom = $wt['dom'];
	    echo "$miasto $ulica $dom\n";
	}
    }
?>