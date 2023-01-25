# SIDUSIS

System oparty jest na plikach. Do wygerowania danych potrzebny będzie zestaw plików. ***Pomimo dołożenia wszelkich starań dane powinny być sprawdzone przed wgraniem do systemu SIDUSIS***.

Dane wgrane do systemu są przetwarzane i weryfikowane ze słownikiem ze strony sidusis (aktualne pliki ze strony sidusis należy pobrać za każdym razem). Raporty i błedy są dostępne do wglądu. Pliki generowane sa całościowo jako jeden plik oraz jako plik przyrostowy.

## Nazwy plików do importu:

   * baza.csv - dane zasięgów adresówych 
   * recznie.csv - nie obowiązkowo, plik z danymi nie zawartymi w bazie klientów.
   * slownik.csv - słownik 
   * di.csv - dane instytucji
   * po.csv - przedstawiciel operatora
   * slownik.csv - słownik potrzebny do zamiany pakietów na technologie i prędkości
   * teryt.csv - plik z danymi adresowymi 

#####  SQL

Tabela tworzy się sama podczas każdego załadunku. W pliku lib/main.php nalezy wspiać dane do połączenia z bazą.


#####  Struktura pliku baza.csv, recznie.csv
 
 zasięg (rzeczywisty/teoretyczny/hurtowy) | pakiet | ulica | nr domu | miasto
 
 przykład: 
``` 
teoretyczny|Power 1000|Wolności|22|Katowice 
```

#####  Struktura pliku slownik.csv
 
pakiet | technologia | predkosc download | predkosc upload | (hurt/detal)
 
 przykład: 
``` 
Internet DOCSIS-400|(EURO)DOCSIS 3.x|1000|100|detal
Dostęp do Internetu 50Mb|GPON|1000|100|detal
Internet  GPON-500|GPON|1000|100|hurt
Dostęp do sieci  INTERNET|1 Gigabit Ethernet|1000|100|detal 
```



#####  teryt

Plik teryt to pliki ściągnięte ze strony pomocy sidusis. Ściągnięte pliki trzeba rozpakować i wgrać potrzebne województwa.

#####  Struktura pliku di.csv,po.csv

Plik di.csv,po.csv przygotowujemy zgodnie z przykładem umieszonym w pomocy na stronie sidusis. Na stronie dostępny też jest szczegółowy opis pól. 

## Linki

[SIDUSIS POMOC - wzory plików ](https://internet.gov.pl/help/)

[Demo SIDUSIS](https://sidusis.aniatel.pl/)

[Paczka przykładowych plików](https://sidusis.aniatel.pl/przyklady.tgz)
