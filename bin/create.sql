drop table IF EXISTS di;
drop table IF EXISTS po;
drop table IF EXISTS sort;
drop table IF EXISTS sort2;
drop table IF EXISTS teryt;
drop table IF EXISTS slownik;

create table teryt (
    TERC			varchar(7) NOT NULL DEFAULT '',
    Gmina			varchar(60) NOT NULL DEFAULT '',
    SIMC			varchar(7) NOT NULL DEFAULT '',
    Miejscowosc 		varchar(60) NOT NULL DEFAULT '',
    SYM_UL			varchar(5) NOT NULL DEFAULT '',
    Ulica			varchar(60) NOT NULL DEFAULT '',
    Nr_porzadkowy		varchar(32) NOT NULL DEFAULT '',
    Szerokosc_geograficzna	decimal(8,6) NOT NULL DEFAULT 0,
    Dlugosc_geograficzna	decimal(8,6) NOT NULL DEFAULT 0,
    gml_id			varchar(80) NOT NULL DEFAULT '');


create index teryt_ulicz on teryt( Miejscowosc,ulica,nr_porzadkowy);

create table di (
    Oznaczenie_typu_danych	varchar(2)  NOT NULL DEFAULT 'DI',
    Nazwa_instytucji 		varchar(150) NOT NULL DEFAULT '',
    Numer_RPT 			varchar(5) NOT NULL DEFAULT '',
    Numer_RJST 			varchar(3) NOT NULL DEFAULT '',
    NIP 			varchar(10) NOT NULL DEFAULT '');


create table po (
    Oznaczenie_typu_danych	varchar(2)  NOT NULL DEFAULT 'PO',
    identyfikator 		varchar(100) NOT NULL DEFAULT '',
    Adres_e_mail		varchar(254) NOT NULL DEFAULT '',
    Telefon 			varchar(14) NOT NULL DEFAULT '',
    Oferta			varchar(200) NOT NULL DEFAULT '');



create table IF NOT EXISTS zs (
    Oznaczenie_typu_danych	varchar(2)  NOT NULL DEFAULT 'ZS',
    identyfikator 		varchar(100) NOT NULL DEFAULT '',
    TERC 			varchar(7) NOT NULL DEFAULT '',
    Miejscowosc 		varchar(100) NOT NULL DEFAULT '',
    SIMC 			varchar(7) NOT NULL DEFAULT '',
    Ulica 			varchar(200) NOT NULL DEFAULT '',
    SYM_UL 			varchar(5) NOT NULL DEFAULT '',
    Numer_budynku 		varchar(32) NOT NULL DEFAULT '',
    Szerokosc_geograficzna	decimal(8,6) NOT NULL DEFAULT 0,
    Dlugosc_geograficzna	decimal(8,6) NOT NULL DEFAULT 0,
    Medium_transmisyjne		varchar(30) NOT NULL DEFAULT '',
    Technologia_dostepowa	varchar(20) NOT NULL DEFAULT '',
    Maksymalna_przepustowosc_d	varchar(5) NOT NULL DEFAULT '',
    Maksymalna_przepustowosc_u  varchar(5) NOT NULL DEFAULT '',
    Rodzaj_zasiegu 		varchar(11) NOT NULL DEFAULT '',
    Usluga_hurtowa 		varchar(3) NOT NULL DEFAULT '',
    Usluga_detaliczna		varchar(3) NOT NULL DEFAULT '',
    Identyfikator_Przedstawiciela varchar(100) NOT NULL DEFAULT '',
    data_export 		date null default null,
    id 				integer not null auto_increment UNIQUE);


create table sort (
    kategoria			varchar(50) NOT NULL DEFAULT '',
    oplata			varchar(250) NOT NULL DEFAULT '',
    ulica			varchar(200) NOT NULL DEFAULT '',
    dom				varchar(32) NOT NULL DEFAULT '',
    miasto			varchar(100) NOT NULL DEFAULT '');


create table slownik (
    pakiet			varchar(500) NOT NULL DEFAULT '',
    technologia			varchar(20) NOT NULL DEFAULT '',
    predkosc_d			varchar(5) NOT NULL DEFAULT '',
    predkosc_u			varchar(5) NOT NULL DEFAULT '',
    hurt			varchar(6) NOT NULL DEFAULT '');
create index slownik_pakiet on slownik( pakiet );

