<?php
include_once ("lib/main.php");

class oplata 
{
    public $nazwa;
    public $predkosc_d;
    public $predkosc_u;
    public $technologia;
    public $hurt;

    public function __construct(string $nazwa) 
    {
	$this->predkosc_d = 0;
	$this->predkosc_u = 0;
	$this->technologia = "";
	$this->hurt = "";
	$this->nazwa = $nazwa ;
    }

    public function type() 
    {
	$dane =  $this->nazwa;
	$res = GetSQL("select predkosc_d, predkosc_u, technologia, hurt from slownik where trim(pakiet) = trim('$this->nazwa') limit 1");
	if( $res && $res->num_rows > 0)
	{
	    $w = $res->fetch_assoc();
	    $this->predkosc_d = $w['predkosc_d'];
	    $this->predkosc_u = $w['predkosc_u'];
	    $this->technologia = $w['technologia'];
	    $this->hurt = $w['hurt'];
	}
	else
	{
	    $this->technologia = "Brak Kategorii";
	}
    }
    public function getTechnologia()
    {
	return $this->technologia;
    }
    public function getPredkosc_d()
    {
	return $this->predkosc_d;
    }
    public function getPredkosc_u()
    {
	return $this->predkosc_u;
    }
    public function getHurt()
    {
	return $this->hurt;
    }
}
?>