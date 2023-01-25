
create table sort2 as select * from sort;

alter table sort2 add column TERC varchar(7) NULL DEFAULT '';
alter table sort2 add column SIMC  varchar(7) NULL DEFAULT '';
alter table sort2 add column SYM_UL varchar(5) NULL DEFAULT '';
alter table sort2 add column Szerokosc_geograficzna varchar(20) NULL DEFAULT '';
alter table sort2 add column Dlugosc_geograficzna varchar(20) NULL DEFAULT '';
alter table sort2 add column dom2 varchar(32) null DEFAULT '';
alter table sort2 add column ulica2 varchar(200) null DEFAULT '';
alter table teryt add column ulica2 varchar(200) null DEFAULT '';
alter table sort2 add column ulica3 varchar(200) null DEFAULT '';
alter table sort2 add column id integer auto_increment unique;
alter table teryt add column id integer auto_increment unique;
create index sort2_ulica on sort2( miasto,ulica,dom);
create index sort2_ulica2 on sort2( miasto,ulica2,dom);

update sort2 set ulica2 = replace(ulica, "ul.", '') where ulica like 'ul.%';
update sort2 set ulica2 = replace(ulica, "pl.", '') where ulica like 'pl.%';
update sort2 set ulica2 = replace(ulica, "al.", '') where ulica like 'al.%';
update sort2 set ulica2 = replace(ulica, "os.", '') where ulica like 'os.%';

update sort2 set ulica2 = ulica where ulica2 = '';
update sort2 set ulica2 = ltrim(ulica2);

update teryt set ulica2 = replace(ulica, "ul. ", '') where ulica like 'ul. %';
update teryt set ulica2 = replace(ulica, "pl. ", '') where ulica like 'pl. %';
update teryt set ulica2 = replace(ulica, "al. ", '') where ulica like 'al. %';
update teryt set ulica2 = replace(ulica, "os. ", '') where ulica like 'os. %';
update teryt set ulica2 = ulica where ulica2 = '';

create index teryt_ulica2 on teryt( Miejscowosc,ulica2,nr_porzadkowy);

update sort2 left join teryt on teryt.ulica2 =  sort2.ulica2 and teryt.Miejscowosc = sort2.miasto and teryt.nr_porzadkowy = sort2.dom set sort2.ulica3 = teryt.ulica, sort2.dom2=teryt.nr_porzadkowy, sort2.TERC=teryt.TERC, sort2.SIMC=teryt.SIMC, sort2.SYM_UL = teryt.SYM_UL, sort2.Szerokosc_geograficzna=teryt.Szerokosc_geograficzna, sort2.Dlugosc_geograficzna=teryt.Dlugosc_geograficzna;
