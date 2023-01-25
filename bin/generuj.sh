#!/bin/bash

folder=`pwd`
echo $folder

data=`/bin/date +%d%m%Y_%H%M`

password=`cat $folder/lib/main.php | grep '\$password' | gawk '{ print $4 }' | sed -e  's/"//g' | sed -e  's/;//g'`
user=`cat  $folder/lib/main.php | grep '\$user' | gawk '{ print $4 }' | sed -e  's/"//g' | sed -e  's/;//g'`
dbname=`cat  $folder/lib/main.php | grep '\$baza' | gawk '{ print $4 }' | sed -e  's/"//g' | sed -e  's/;//g'`

echo "p: $password u: $user D: $dbname"

echo "start"> $folder/generuj.lock

mv $folder/24-full.zip $folder/backup/24-full-$data.zip
mv $folder/24-przyrostowo.zip $folder/backup/24-przyrostowo-$data.zip

rm -f $folder/full/24.csv
rm -f $folder/przyrostowo/24.csv

mysql -u $user -p --password=$password $dbname <$folder/bin/create.sql

cd $folder/upload/teryt
for f in *.csv
do
    mysql -e "LOAD DATA LOCAL INFILE '"$f"' INTO TABLE teryt FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 ROWS\n" -u $user --password=$password $dbname --local-infile

done
cd $folder

if [ ! -f $folder/upload/baza.csv ]
then
    echo "">$folder/upload/baza.csv
    echo "Brak baza.csv"
else
    mysql -e "LOAD DATA LOCAL INFILE '$folder/upload/baza.csv' INTO TABLE sort FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n'" -u $user --password=$password $dbname --local-infile
fi

if [ ! -f $folder/upload/recznie.csv ]
then
    echo "">$folder/upload/recznie.csv
    echo "Brak recznie.csv"
else
    mysql -e "LOAD DATA LOCAL INFILE '$folder/upload/recznie.csv' INTO TABLE sort FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n'" -u $user --password=$password $dbname --local-infile
fi

if [ ! -f $folder/upload/di.csv ]
then
    echo "">$folder/upload/di.csv
    echo "Brak di.csv"
else
    mysql -e "LOAD DATA LOCAL INFILE '$folder/upload/di.csv' INTO TABLE di FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n'" -u $user --password=$password $dbname --local-infile
fi

if [ ! -f $folder/upload/po.csv ]
then
    echo "">$folder/upload/po.csv
    echo "Brak po.csv"
else
    mysql -e "LOAD DATA LOCAL INFILE '$folder/upload/po.csv' INTO TABLE po FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n'" -u $user --password=$password $dbname --local-infile
fi



if [ ! -f $folder/upload/slownik.csv ]
then
    echo "">$folder/upload/slownik.csv
    echo "Brak slownik.csv"
else
    mysql -e "LOAD DATA LOCAL INFILE '$folder/upload/slownik.csv' INTO TABLE slownik FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n'" -u $user --password=$password $dbname --local-infile
fi

mysql -u $user -p --password=$password $dbname <$folder/bin/update.sql


php $folder/bin/sidusis.php >$folder/error.txt
php $folder/bin/teryt_error.php >$folder/brak.txt
cd $folder/full
zip $folder/24-full.zip 24.csv
cd $folder/przyrostowo
zip $folder/24-przyrostowo.zip 24.csv

cd ..

rm $folder/generuj.lock
