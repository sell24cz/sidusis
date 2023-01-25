<?php
include ("lib/main.php");
include("oplaty.php");

$plikexport = "full/24.csv";
$plikexport_przyrostowo = "przyrostowo/24.csv";

/* =============== Czyścimy tabele zs ======== */
//mGetSQL("delete from zs");

// Pobieramy identyfikator przedstawiciela z tabeliu PO pierwszy rekord.
$Identyfikator_Przedstawiciela = mysql_q("select identyfikator from po limit 1");

if( $Identyfikator_Przedstawiciela == "" )
{
    echo "Brak identyfikatora przedstawiciela P: $Identyfikator_Przedstawiciela\n";
    return;
}

// Pobieramy rekordy z przygotowanej wcześniej tabeli sort2. Tabela tworzona w skrypcie teryt.sql
$q= "select * from sort2 order by miasto,ulica3,dom2,oplata";
$res = GetSQL($q);
if( $res )
{
    while ( $w = $res->fetch_array() )
    {
	$miasto = $w['miasto'];
	$ulica = $w['ulica3'];
	$dom = $w['dom2'];
	$kategoria = $w['kategoria'];
	$identyfikator = preg_replace("/ /", "_", $miasto)."_" . preg_replace("/ /", "_", $ulica)."_".$dom;
	$oplata = $w['oplata'];

// Sprawdzamy technologie na podstawie opłaty
	$op = new oplata(trim($oplata));
	$op->type();
	$Technologia_dostepowa = $op->getTechnologia();
	$Maksymalna_przepustowosc_d = $op->getPredkosc_d();
	$Maksymalna_przepustowosc_u = $op->getPredkosc_u();
	$hurt = $op->getHurt();
	unset($op);

	$Medium_transmisyjne = "";

	if( $ulica != "" && $miasto != "" && $Technologia_dostepowa != "delete" )
	{
	    switch($Technologia_dostepowa)
	    {
		case "GPON" :
		    $Medium_transmisyjne = "światłowodowe";
		    $identyfikator = "G_" . $identyfikator;
		    break;
		case "(EURO)DOCSIS 3.x" :
		    $Medium_transmisyjne = "kablowe współosiowe miedziane";
		    $identyfikator = "D_". $identyfikator;
		    break;
		case "1 Gigabit Ethernet" :
		    $Medium_transmisyjne = "kablowe parowe miedziane";
		    $identyfikator = "GI_" . $identyfikator;
		    break;
		case "100 Mb/s Fast Ethernet" :
		    $Medium_transmisyjne = "kablowe parowe miedziane";
		    $identyfikator = "F_" . $identyfikator;
		    break;

	    }
	    
	    $TERC = $w['TERC'];
	    $SIMC = $w['SIMC'];
	    $SYM_UL  = $w['SYM_UL'];
	    $Szerokosc_geograficzna = $w['Szerokosc_geograficzna'];
	    $Dlugosc_geograficzna = $w['Dlugosc_geograficzna'];
	    $Rodzaj_zasiegu = "rzeczywisty";
	    if ($kategoria == "teoretyczny")
	    {
		$Rodzaj_zasiegu = "teoretyczny";
	    }
	    if( $hurt == "hurt" )
	    {
		$Usluga_hurtowa = "TAK";
		$Usluga_detaliczna = "NIE";
	    }
	    else
	    {
		$Usluga_hurtowa = "NIE";
		$Usluga_detaliczna = "TAK";
	    }

// Sprawdzamy ilość znaków identyfikatora. Ograniczenie do 100. 
	    if( strlen($identyfikator) > 100 )
	    {
		echo "================  ERROR Identyfikator > 100 znaków :  $identyfikator =======================\n";
	    }

// Przy złej technologii  nie będziemy dodawać rekordu do tabeli zs
	    if( $Technologia_dostepowa != "(EURO)DOCSIS 3.x" && $Technologia_dostepowa != "GPON" && $Technologia_dostepowa != "1 Gigabit Ethernet")
	    {
		echo "ERROR - $Technologia_dostepowa  - $identyfikator  OPŁATA: $oplata\n";
	    }
	    else
	    {
		$qinsert = "insert into zs ( Oznaczenie_typu_danych,identyfikator,TERC,Miejscowosc,SIMC,Ulica,SYM_UL,Numer_budynku,Szerokosc_geograficzna,Dlugosc_geograficzna,
		    Medium_transmisyjne,Technologia_dostepowa,Maksymalna_przepustowosc_d, Maksymalna_przepustowosc_u, Rodzaj_zasiegu, Usluga_hurtowa, Usluga_detaliczna,Identyfikator_Przedstawiciela)
		    values( 'ZS', '$identyfikator', '$TERC','$miasto','$SIMC','$ulica','$SYM_UL','$dom','$Szerokosc_geograficzna','$Dlugosc_geograficzna',
		    '$Medium_transmisyjne','$Technologia_dostepowa','$Maksymalna_przepustowosc_d', '$Maksymalna_przepustowosc_u', '$Rodzaj_zasiegu', '$Usluga_hurtowa', '$Usluga_detaliczna', '$Identyfikator_Przedstawiciela')";
    
//  Srawdzamy czy w tabeli zs jest już rekord o tym samym identyfikatorze	    

		$qzs = "select Technologia_dostepowa from zs where identyfikator = '$identyfikator'";
		$zstechnologia = mysql_q($qzs);


		if( $zstechnologia == "" )
		{

//  Brak rekordu. Dodajemy nowy.
		    GetSQL($qinsert);
		}
		elseif( $zstechnologia != $Technologia_dostepowa )
		{
		    if( $Technologia_dostepowa == "GPON" || $zstechnologia == "1 Gigabit Ethernet")
		    {

//  Nowa technologia jest lepsza. Nadpisujemy rekord nowymi danymi.
			GetSQL("delete from zs where identyfikator = '$identyfikator'");
			GetSQL($qinsert);
		    }
		    else
		    {
// Nowa technologia gorsza. Pomijamy.
//			echo "Inna technologia ZS: $zstechnologia  SORT: $Technologia_dostepowa \n";
		    }
		}
		else
		{
//			echo "Taka sama technologia ZS: $zstechnologia  SORT: $Technologia_dostepowa \n";
		}
	    }
	}
    }

//    echo "\n============   Export danych do pliku export.csv   =========\n";
    
    $plik = fopen($plikexport, "w+");
    if( $plik )
    {
	$resdi = GetSQL("select * from di");
	if( $resdi )
	{
	    while ( $wdi = $resdi->fetch_array() )
	    {
		$str = "";
		$str = $wdi['Oznaczenie_typu_danych'].','.$wdi['Nazwa_instytucji'].','.$wdi['Numer_RPT'].','.$wdi['Numer_RJST'].','.$wdi['NIP']."\n";
		fputs($plik, $str);
	    }

	}
	$respo = GetSQL("select * from po");
	if( $respo )
	{
	    while ( $wpo = $respo->fetch_array() )
	    {
		$str = "";
		$str = $wpo['Oznaczenie_typu_danych'].','.$wpo['identyfikator'].','.$wpo['Adres_e_mail'].','.$wpo['Telefon'].','.$wpo['Oferta']."\n";
		fputs($plik, $str);
	    }

	}
	$reszs = GetSQL("select * from zs order by Miejscowosc, ulica, numer_budynku, Medium_transmisyjne");
	if( $reszs )
	{
	    while ( $wzs = $reszs->fetch_array() )
	    {
		$str = "";
		$idzs = $wzs['id'];
		
		$str = $wzs['Oznaczenie_typu_danych'].','.$wzs['identyfikator'].','.$wzs['TERC'].','.$wzs['Miejscowosc'].','.$wzs['SIMC'].','.$wzs['Ulica'].','.$wzs['SYM_UL'].','.$wzs['Numer_budynku'].','.round($wzs['Szerokosc_geograficzna'],6).','.round($wzs['Dlugosc_geograficzna'],6).','.$wzs['Medium_transmisyjne'].','.$wzs['Technologia_dostepowa'].','.$wzs['Maksymalna_przepustowosc_d'].','.$wzs['Maksymalna_przepustowosc_u'].','.$wzs['Rodzaj_zasiegu'].','.$wzs['Usluga_hurtowa'].','.$wzs['Usluga_detaliczna'].','.$wzs['Identyfikator_Przedstawiciela']."\n";
		fputs($plik, $str);
		GetSQL("update zs set data_export = now() where id = '$idzs' and data_export is null");
	    }

	}
	fclose($plik);
    }

//    echo "\n============   Export danych przyrostowych do pliku export.csv   =========\n";
    
    $plik = fopen($plikexport_przyrostowo, "w+");
    if( $plik )
    {
	$resdip = GetSQL("select * from di");
	if( $resdip )
	{
	    while ( $wdip = $resdip->fetch_array() )
	    {
		$str = "";
		$str = $wdip['Oznaczenie_typu_danych'].','.$wdip['Nazwa_instytucji'].','.$wdip['Numer_RPT'].','.$wdip['Numer_RJST'].','.$wdip['NIP']."\n";
		fputs($plik, $str);
	    }

	}
	$respop = mGetSQL("select * from po");
	if( $respop )
	{
	    while ( $wpop = $respop->fetch_array() )
	    {
		$str = "";
		$str = $wpop['Oznaczenie_typu_danych'].','.$wpop['identyfikator'].','.$wpop['Adres_e_mail'].','.$wpop['Telefon'].','.$wpop['Oferta']."\n";
		fputs($plik, $str);
	    }

	}
	
	$reszsp = GetSQL("select * from zs where data_export is null or data_export > LAST_DAY(CURDATE() -INTERVAL 1 MONTH) order by Miejscowosc, ulica, numer_budynku, Medium_transmisyjne");
	if( $reszsp )
	{
	    while ( $wzsp = $reszsp->fetch_array() )
	    {
		$str = "";
		$idzsp = $wzsp['id'];
		
		$str = $wzsp['Oznaczenie_typu_danych'].','.$wzsp['identyfikator'].','.$wzsp['TERC'].','.$wzsp['Miejscowosc'].','.$wzsp['SIMC'].','.$wzsp['Ulica'].','.$wzsp['SYM_UL'].','.$wzsp['Numer_budynku'].','.round($wzsp['Szerokosc_geograficzna'],6).','.round($wzsp['Dlugosc_geograficzna'],6).','.$wzsp['Medium_transmisyjne'].','.$wzsp['Technologia_dostepowa'].','.$wzsp['Maksymalna_przepustowosc_d'].','.$wzsp['Maksymalna_przepustowosc_u'].','.$wzsp['Rodzaj_zasiegu'].','.$wzsp['Usluga_hurtowa'].','.$wzsp['Usluga_detaliczna'].','.$wzsp['Identyfikator_Przedstawiciela']."\n";
		fputs($plik, $str);
		GetSQL("update zs set data_export = now() where id = '$idzsp' and data_export is null");
	    }

	}
	fclose($plik);
    }
//    echo "============                KONIEC                 =========";
    
}

?>