<?php
//autoload class
function custom_autoloader($class)
{
    define(DIR, $_SERVER["DOCUMENT_ROOT"]);
    include DIR . "/lib/" . $class . ".php";
}

spl_autoload_register("custom_autoloader");
// Global value
$url = "https://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
// end global value
function formatGET($tekst)
{
    return addslashes(trim($_GET["" . $tekst . ""]));
}
function formatPOST($tekst)
{
    return addslashes(trim($_POST["" . $tekst . ""]));
}
class BAZA
{
    public $host = "localhost";
    public $baza = "sidusis";
    public $user = "sidusis";
    public $password = "";

    public function BAZA()
    {
        $this->sqlConnect = mysqli_connect(
            $this->host,
            $this->user,
            $this->password
        );
        mysqli_select_db($this->sqlConnect, $this->baza);
    }
}
function sql_fetch_array($sqlBack)
{
    $result = null;
    if ($sqlBack) {
        $result = $sqlBack->fetch_array(MYSQLI_ASSOC);
    }
    return $result;
}

function GetSQL($query)
{
    $sqlConnect = new BAZA();
    $zapytanie = null;
    if ($query != "") {
        ($zapytanie = $sqlConnect->sqlConnect->query($query)) or
            die(mysqli_error($sqlConnect->sqlConnect));
    }
    return $zapytanie;
}
function mysql_q($query, $default_value = "")
{
    $result = GetSQL($query);
    if (mysqli_num_rows($result) == 0) {
        return $default_value;
    } else {
        $row = $result->fetch_row();
        //     return mysqli_result($result,0);
        return $row[0];
    }
}
