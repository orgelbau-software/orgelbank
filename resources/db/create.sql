-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: dd8434
-- Erstellungszeit: 21. Jun 2021 um 20:42
-- Server-Version: 5.7.34-nmm1-log
-- PHP-Version: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `d036ca68`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `adresse`
--

CREATE TABLE `adresse` (
  `ad_id` int(11) NOT NULL,
  `ad_type` int(1) NOT NULL,
  `ad_strasse` varchar(100) DEFAULT NULL,
  `ad_hsnr` varchar(5) DEFAULT NULL,
  `ad_plz` varchar(10) DEFAULT NULL,
  `ad_ort` varchar(100) DEFAULT NULL,
  `ad_land` varchar(100) NOT NULL DEFAULT 'Deutschland',
  `ad_lat` varchar(20) DEFAULT NULL,
  `ad_lng` varchar(20) DEFAULT NULL,
  `ad_geostatus` varchar(30) DEFAULT NULL,
  `ad_lastchange` datetime NOT NULL,
  `ad_createdate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ansprechpartner`
--

CREATE TABLE `ansprechpartner` (
  `a_id` int(11) NOT NULL,
  `a_funktion` varchar(50) NOT NULL,
  `a_stelle` varchar(50) NOT NULL,
  `a_anrede` varchar(50) NOT NULL,
  `a_titel` varchar(20) NOT NULL,
  `a_vorname` varchar(50) NOT NULL,
  `a_name` varchar(50) NOT NULL,
  `ad_id` int(11) DEFAULT NULL,
  `a_telefon` varchar(50) NOT NULL,
  `a_andere` varchar(100) NOT NULL,
  `a_fax` varchar(50) NOT NULL,
  `a_mobil` varchar(50) NOT NULL,
  `a_email` varchar(50) NOT NULL,
  `a_bemerkung` varchar(500) NOT NULL,
  `a_aktiv` varchar(10) NOT NULL,
  `a_lastchange` datetime NOT NULL,
  `a_createdate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitstag`
--

CREATE TABLE `arbeitstag` (
  `at_id` int(11) NOT NULL,
  `aw_id` int(11) DEFAULT NULL,
  `at_datum` date NOT NULL,
  `be_id` int(10) NOT NULL,
  `proj_id` int(11) NOT NULL,
  `au_id` int(10) NOT NULL,
  `at_stunden_ist` double(10,2) NOT NULL,
  `at_stunden_soll` double(10,2) NOT NULL,
  `at_stunden_dif` double(10,2) NOT NULL,
  `at_kommentar` varchar(500) NOT NULL,
  `at_komplett` int(2) NOT NULL DEFAULT '0',
  `at_gesperrt` int(2) NOT NULL DEFAULT '0',
  `at_lastchange` datetime NOT NULL,
  `at_createdate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitswoche`
--

CREATE TABLE `arbeitswoche` (
  `aw_id` int(11) NOT NULL,
  `be_id` int(10) NOT NULL,
  `aw_wochenstart` date NOT NULL,
  `aw_kw` int(2) NOT NULL,
  `aw_jahr` int(4) NOT NULL,
  `aw_stunden_ist` double(10,2) NOT NULL,
  `aw_stunden_soll` double(10,2) NOT NULL,
  `aw_stunden_dif` double(10,2) NOT NULL,
  `aw_stunden_urlaub` int(11) DEFAULT NULL,
  `aw_eingabe_komplett` int(1) NOT NULL,
  `aw_eingabe_moeglich` int(1) NOT NULL,
  `aw_eingabe_gebucht` int(1) NOT NULL,
  `aw_lastchange` datetime NOT NULL,
  `aw_createdate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `aufgabe`
--

CREATE TABLE `aufgabe` (
  `au_id` int(10) NOT NULL,
  `au_bezeichnung` varchar(100) NOT NULL,
  `au_beschreibung` varchar(500) NOT NULL,
  `au_geloescht` int(10) NOT NULL,
  `au_parentid` int(11) NOT NULL,
  `au_lastchange` datetime NOT NULL,
  `au_createdate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `aufgabe`
--

INSERT INTO `aufgabe` (`au_id`, `au_bezeichnung`, `au_beschreibung`, `au_geloescht`, `au_parentid`, `au_lastchange`, `au_createdate`) VALUES
(64, 'Spieltisch', 'Werkstattarbeiten, Neubau und Restaurierung', 0, 0, '2012-04-30 11:32:17', '2009-01-10 16:52:00'),
(66, 'Montage', 'Aufbau in der Kirche', 0, 0, '2009-01-20 09:35:50', '2009-01-10 16:52:45'),
(70, 'Pfeifenwerk (gelöscht)', 'Werkstattarbeit', 1, 0, '2013-04-21 17:04:39', '2009-01-18 12:54:22'),
(80, 'Spieltraktur', 'Werkstattarbeit, Neubau und Restaurierung', 0, 0, '2012-04-30 11:36:24', '2009-01-18 13:06:15'),
(86, 'Sanierung (gelöscht)', 'Werksattarbeiten, Neubau und Restaurierung', 1, 0, '2012-05-06 12:17:37', '2009-01-18 13:11:53'),
(89, 'Windanlage', 'Werkstattarbeiten, Neubau und Restaurierung', 0, 0, '2012-03-05 12:31:46', '2009-01-19 17:42:39'),
(98, 'Gerüstbau', 'Werkstattarbeit', 0, 0, '2009-01-20 09:50:07', '2009-01-19 17:52:56'),
(102, 'Holzpfeifenbau-Neu (gelöscht)', 'Werkstattarbeit', 1, 0, '2012-05-06 12:25:07', '2009-01-19 17:56:11'),
(106, 'Metallpfeifen (gelöscht)', 'Werkstattarbeit', 1, 0, '2013-05-08 11:05:38', '2009-01-19 17:57:41'),
(107, 'Windladen', 'Werkstattarbeit Neubau und Restaurieren', 0, 0, '2012-03-05 11:51:09', '2009-01-19 17:58:17'),
(227, 'Garantie', '', 0, 220, '2012-08-17 16:43:40', '2012-08-17 16:43:40'),
(226, 'Reparatur (gelöscht)', '', 1, 66, '2013-01-17 09:39:26', '2012-06-02 12:33:32'),
(116, 'Aufrastern', 'Werkstattarbeit', 0, 0, '2016-11-01 13:10:18', '2009-01-19 18:05:14'),
(225, 'Schule (gelöscht)', '', 1, 220, '2016-07-05 13:35:35', '2012-05-25 15:30:27'),
(224, 'Überstunden (gelöscht)', '', 1, 220, '2014-01-13 10:10:02', '2012-05-23 16:44:02'),
(223, 'Krank (gelöscht)', '', 1, 220, '2014-01-13 10:10:17', '2012-05-19 10:56:09'),
(120, 'Metallpfeifen Restaurierung (gelöscht)', 'Montage und Werrkstatt', 1, 0, '2012-05-06 12:25:46', '2009-01-19 18:07:31'),
(222, 'Krank (gelöscht)', '', 1, 0, '2012-05-19 10:55:56', '2012-05-19 10:55:36'),
(221, 'Urlaubzeit (gelöscht)', '', 1, 220, '2014-01-13 08:01:43', '2012-05-19 10:54:43'),
(124, 'Registertraktur', 'Montage und Werkstatt', 0, 0, '2012-04-30 11:34:24', '2009-01-19 18:08:49'),
(220, 'Betriebsinterna', '', 0, 0, '2016-07-05 13:35:09', '2012-05-19 10:54:29'),
(219, 'Gehäuseneubau', '', 0, 163, '2012-05-06 14:29:05', '2012-05-06 14:29:05'),
(127, 'Pfeifenwerk', 'Montage und Werkstatt', 0, 0, '2013-05-08 11:02:38', '2009-01-19 18:10:40'),
(212, 'Pfeifenneubau-Holz', '', 0, 127, '2013-05-08 11:03:54', '2012-05-06 12:24:52'),
(129, 'Restaurierung (gelöscht)', 'Montage und Werkstatt', 1, 0, '2012-05-06 12:17:10', '2009-01-19 18:11:30'),
(200, 'Klaviaturen (gel', '', 1, 64, '2017-01-19 08:09:55', '2012-05-06 12:18:01'),
(201, 'Pedalklaviatur (gelöscht)', '', 1, 64, '2013-05-08 11:15:44', '2012-05-06 12:18:17'),
(132, 'Reinigug', 'Montage und Werkstatt', 0, 0, '2009-01-20 09:36:23', '2009-01-19 18:13:12'),
(218, 'Orgelabbau', '', 0, 66, '2012-05-06 13:37:51', '2012-05-06 13:37:51'),
(217, 'Überarbeitung', '', 0, 214, '2014-01-13 09:38:38', '2012-05-06 13:37:25'),
(216, 'Abbau und Aufbau (gelöscht)', '', 1, 214, '2012-05-06 13:36:16', '2012-05-06 13:21:19'),
(213, 'Metallpfeifenrestaurierung (gelöscht)', '', 1, 106, '2013-05-08 11:05:38', '2012-05-06 12:26:35'),
(214, 'Werkstatt', '', 0, 0, '2012-05-06 13:37:26', '2012-05-06 13:20:53'),
(210, 'Stöcke', '', 0, 107, '2012-05-06 12:20:49', '2012-05-06 12:20:49'),
(208, 'Kanäle', '', 0, 89, '2012-05-06 12:20:17', '2012-05-06 12:20:17'),
(206, 'Intonation Werkstatt', '', 0, 178, '2012-05-06 12:19:48', '2012-05-06 12:19:48'),
(203, 'Mechanisch', '', 0, 80, '2012-05-06 12:18:49', '2012-05-06 12:18:49'),
(202, 'Chassi (gel', '', 1, 64, '2017-01-19 08:09:46', '2012-05-06 12:18:24'),
(199, 'Intonationsarbeiten', '', 0, 66, '2012-05-06 12:16:42', '2012-05-06 12:16:42'),
(198, 'Schimmelbekämpfung', '', 0, 132, '2012-05-06 12:13:53', '2012-05-06 12:13:53'),
(197, 'Orgelreinigung', '', 0, 132, '2012-05-06 12:13:32', '2012-05-06 12:13:32'),
(196, 'Reg.-Holz', '', 0, 124, '2012-05-06 12:13:04', '2012-05-06 12:13:04'),
(195, 'Stimmung', '', 0, 180, '2012-05-06 12:12:28', '2012-05-06 12:12:28'),
(194, 'Pfeifenreinigung (gelöscht)', '', 1, 70, '2013-04-21 17:04:39', '2012-05-06 12:11:56'),
(193, 'Tremulant', '', 0, 169, '2012-05-06 12:11:29', '2012-05-06 12:10:18'),
(192, 'Trakturteile', '', 0, 169, '2012-05-06 12:10:07', '2012-05-06 12:10:07'),
(191, 'Aufbau', '', 0, 66, '2012-05-06 12:09:33', '2012-05-06 12:09:33'),
(190, 'Metallpfeifenneubau (gelöscht)', '', 1, 106, '2013-05-08 11:05:38', '2012-05-06 12:09:11'),
(189, 'Pfeifenrestaurierun-Holz', '', 0, 127, '2013-05-08 11:04:23', '2012-05-06 12:08:26'),
(163, 'Gehäuse', 'Werkstattarbeiten', 0, 0, '2009-01-20 11:17:19', '2009-01-20 11:17:19'),
(188, 'Sanierung-Metall (gelöscht)', '', 1, 120, '2012-05-06 12:25:46', '2012-05-06 12:06:13'),
(187, 'Holzpfeifen (gelöscht)', '', 1, 102, '2012-05-06 12:25:07', '2012-05-06 12:05:48'),
(186, 'Intonation (gelöscht)', '', 1, 179, '2013-05-08 11:06:43', '2012-05-06 12:04:59'),
(184, 'Spieltischbau', '', 0, 163, '2012-05-06 12:03:55', '2012-05-06 12:03:55'),
(169, 'Orgelteile', 'Werkstattarbeit', 0, 0, '2012-04-30 11:39:00', '2009-01-20 12:22:56'),
(183, 'Gehäuserestaurierung', '', 0, 163, '2012-05-06 14:28:53', '2012-05-06 12:03:32'),
(182, 'Pfeifenaufrastern', '', 0, 116, '2012-05-06 12:03:07', '2012-05-06 12:03:07'),
(181, 'Orgelbänke', '', 0, 169, '2012-04-30 11:55:54', '2012-04-30 11:55:54'),
(180, 'Pflege-Stimmungen', 'Montage', 0, 0, '2012-04-30 11:41:01', '2012-04-30 11:41:01'),
(179, 'Haupt-Intonation (gelöscht)', 'Montage', 1, 0, '2013-05-08 11:06:43', '2012-04-30 11:40:31'),
(215, 'Abbau', '', 0, 214, '2012-05-06 13:35:56', '2012-05-06 13:21:03'),
(178, 'Vor-Intonation', 'Werkstatt', 0, 0, '2012-04-30 11:40:07', '2012-04-30 11:40:07'),
(185, 'Holzgerüst', '', 0, 98, '2012-05-06 12:04:13', '2012-05-06 12:04:13'),
(209, 'Korpus', '', 0, 107, '2012-05-06 12:20:36', '2012-05-06 12:20:36'),
(211, 'Windkasten', '', 0, 107, '2012-05-06 12:20:59', '2012-05-06 12:20:59'),
(207, 'Bälge', '', 0, 89, '2012-05-06 12:20:10', '2012-05-06 12:20:10'),
(205, 'Reg-Elektrisch', '', 0, 124, '2012-05-06 12:19:28', '2012-05-06 12:19:28'),
(204, 'Elektrisch', '', 0, 80, '2012-05-06 12:18:56', '2012-05-06 12:18:56'),
(228, 'Kurzarbeit', '', 0, 220, '2012-12-15 11:05:53', '2012-12-15 11:05:53'),
(229, 'Werkstattarbeit (gelöscht)', '', 1, 220, '2014-01-13 08:01:35', '2013-01-22 16:11:57'),
(230, 'Verschiedenes', '', 0, 127, '2013-04-21 17:04:01', '2013-04-21 17:02:04'),
(231, 'Sonstige', '', 0, 132, '2013-04-21 17:03:22', '2013-04-21 17:03:22'),
(232, 'Verschiedene (gelöscht)', '', 1, 106, '2013-05-08 11:05:38', '2013-04-21 17:05:20'),
(233, 'Pfeifenreinigung', '', 0, 132, '2013-04-21 17:05:52', '2013-04-21 17:05:52'),
(234, 'Sicherung', '', 0, 66, '2013-05-08 10:07:35', '2013-05-08 10:07:35'),
(235, 'Pfeifenneubau-Metall', '', 0, 127, '2013-05-08 11:04:07', '2013-05-08 11:04:07'),
(236, 'Pfeifenrestaurierung-Metall', '', 0, 127, '2013-05-08 11:04:41', '2013-05-08 11:04:41'),
(237, 'Zusammenbau', '', 0, 214, '2013-05-08 11:06:26', '2013-05-08 11:06:26'),
(238, 'Pedalklavier (gel', '', 1, 64, '2017-01-19 08:08:11', '2013-05-08 11:16:12'),
(239, 'Pedalkaviatur', '', 0, 169, '2013-05-08 11:16:34', '2013-05-08 11:16:34'),
(240, 'Stoßbälge', '', 0, 169, '2013-05-08 11:16:47', '2013-05-08 11:16:47'),
(241, 'test2', '', 0, 0, '2016-11-21 14:26:13', '2013-05-08 11:18:56'),
(242, 'Hohlflöte', '', 0, 241, '2013-05-08 11:19:10', '2013-05-08 11:19:10'),
(243, 'Gedact', '', 0, 241, '2013-05-08 11:19:22', '2013-05-08 11:19:22'),
(244, 'Subbass', '', 0, 241, '2013-05-08 11:19:42', '2013-05-08 11:19:33'),
(245, 'Violon', '', 0, 241, '2013-05-08 11:20:08', '2013-05-08 11:20:08'),
(246, 'Posaune', '', 0, 241, '2013-05-08 11:20:13', '2013-05-08 11:20:13'),
(247, 'Trompete', '', 0, 241, '2013-05-08 11:20:18', '2013-05-08 11:20:18'),
(248, 'Büro,Doku (gelöscht)', '', 1, 220, '2014-01-13 08:01:56', '2014-01-10 15:21:01'),
(249, 'Garantieleistung', '', 0, 180, '2014-01-13 08:00:17', '2014-01-13 08:00:17'),
(250, 'Werkstattarbeit', '', 0, 0, '2014-01-13 08:03:37', '2014-01-13 08:03:37'),
(251, 'Instandhaltung', '', 0, 250, '2014-01-13 08:03:57', '2014-01-13 08:03:57'),
(252, 'Praktikanten', '', 0, 250, '2014-01-13 08:04:04', '2014-01-13 08:04:04'),
(253, 'Autopflege', '', 0, 250, '2014-01-13 08:06:08', '2014-01-13 08:06:08'),
(254, 'Auszeit', '', 1, 0, '2014-01-13 10:04:25', '2014-01-13 09:59:53'),
(255, 'Urlaub (gelöscht)', '', 1, 254, '2014-01-13 10:07:17', '2014-01-13 10:04:31'),
(256, 'Überstundenvergütet (gelöscht)', '', 1, 254, '2014-01-13 10:07:11', '2014-01-13 10:04:46'),
(257, 'Urlaubszeit (gelöscht)', '', 1, 0, '2014-01-13 10:12:56', '2014-01-13 10:08:29'),
(258, 'Urlaub (gelöscht)', '', 1, 257, '2014-01-13 10:12:56', '2014-01-13 10:08:43'),
(259, 'Aus-Urlaubszeit', '', 0, 0, '2014-01-13 10:14:26', '2014-01-13 10:14:26'),
(260, 'Urlaub', '', 0, 259, '2014-01-13 10:14:38', '2014-01-13 10:14:38'),
(261, ' (gel', '', 1, 259, '2018-12-10 21:55:22', '2014-01-13 10:14:44'),
(262, 'Krank', '', 0, 259, '2014-01-13 10:14:50', '2014-01-13 10:14:50'),
(263, 'Dokumentationen', '', 0, 220, '2016-11-01 13:04:53', '2014-01-13 10:22:31'),
(264, 'Konstruktion', '', 0, 220, '2014-01-13 10:22:45', '2014-01-13 10:22:45'),
(265, 'Angebote', '', 0, 220, '2014-01-13 10:22:51', '2014-01-13 10:22:51'),
(266, 'AAA-Schule (gelöscht)', '', 1, 0, '2016-11-01 13:09:53', '2016-07-05 13:33:19'),
(267, 'Ludwigsburg (gelöscht)', '', 1, 266, '2016-11-01 13:09:53', '2016-07-05 13:34:57'),
(268, 'Ventile', '', 0, 107, '2017-01-18 13:54:44', '2017-01-18 13:54:44'),
(269, 'Unproduktiv', '', 0, 220, '2017-01-19 08:05:16', '2017-01-19 08:05:16'),
(270, '01 Gehaeuse', '', 0, 64, '2017-01-19 08:09:22', '2017-01-19 08:07:16'),
(271, '02 Koppelaufbau', '', 0, 64, '2017-01-19 08:08:37', '2017-01-19 08:08:37'),
(272, '03 Registertraktur', '', 0, 64, '2017-01-19 08:10:09', '2017-01-19 08:10:09'),
(273, 'Test1', '', 0, 116, '2017-01-27 18:15:03', '2017-01-27 18:15:03'),
(274, 'Saubermachen', '', 0, 214, '2018-02-25 17:08:22', '2018-02-25 17:08:22'),
(275, '', '', 0, 259, '2018-12-10 21:55:07', '2018-12-10 21:55:07'),
(276, 'Ueberstunden', '', 0, 259, '2018-12-10 21:55:15', '2018-12-10 21:55:15');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `aufgabe_mitarbeiter`
--

CREATE TABLE `aufgabe_mitarbeiter` (
  `au_id` int(10) NOT NULL,
  `be_id` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `aufgabe_mitarbeiter`
--

INSERT INTO `aufgabe_mitarbeiter` (`au_id`, `be_id`) VALUES
(64, 2),
(66, 2),
(70, 2),
(80, 2),
(86, 2),
(89, 2),
(98, 2),
(102, 2),
(106, 2),
(107, 2),
(116, 2),
(120, 2),
(124, 2),
(127, 2),
(129, 2),
(132, 2),
(163, 2),
(169, 2),
(178, 2),
(179, 2),
(180, 2),
(181, 2),
(182, 2),
(183, 2),
(184, 2),
(185, 2),
(186, 2),
(187, 2),
(188, 2),
(189, 2),
(190, 2),
(191, 2),
(192, 2),
(193, 2),
(194, 2),
(195, 2),
(196, 2),
(197, 2),
(198, 2),
(199, 2),
(200, 2),
(201, 2),
(202, 2),
(203, 2),
(204, 2),
(205, 2),
(206, 2),
(207, 2),
(208, 2),
(209, 2),
(210, 2),
(211, 2),
(212, 2),
(213, 2),
(214, 2),
(215, 2),
(216, 2),
(217, 2),
(218, 2),
(219, 2),
(220, 2),
(221, 2),
(222, 2),
(223, 2),
(224, 2),
(225, 2),
(226, 2),
(227, 2),
(228, 2),
(229, 2),
(230, 2),
(231, 2),
(232, 2),
(233, 2),
(234, 2),
(235, 2),
(236, 2),
(237, 2),
(238, 2),
(239, 2),
(240, 2),
(241, 2),
(242, 2),
(243, 2),
(244, 2),
(245, 2),
(246, 2),
(247, 2),
(248, 2),
(249, 2),
(250, 2),
(251, 2),
(252, 2),
(253, 2),
(254, 2),
(255, 2),
(256, 2),
(257, 2),
(258, 2),
(259, 2),
(260, 2),
(261, 2),
(262, 2),
(263, 2),
(264, 2),
(265, 2),
(266, 2),
(267, 2),
(268, 2),
(269, 2),
(270, 2),
(271, 2),
(272, 2),
(273, 2),
(274, 2),
(275, 2),
(276, 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `benutzer`
--

CREATE TABLE `benutzer` (
  `be_id` int(10) NOT NULL,
  `be_vorname` varchar(100) NOT NULL,
  `be_nachname` varchar(100) NOT NULL,
  `be_benutzername` varchar(100) NOT NULL,
  `be_passwort` varchar(100) NOT NULL,
  `be_benutzerlevel` int(10) NOT NULL,
  `be_aktiviert` int(10) NOT NULL,
  `be_demo` int(1) NOT NULL DEFAULT '0',
  `be_failedlogin_count` int(11) NOT NULL DEFAULT '0',
  `be_failedlogin_last` datetime NOT NULL,
  `be_geloescht` int(1) NOT NULL,
  `be_std_montag` double(4,2) NOT NULL,
  `be_std_dienstag` double(4,2) NOT NULL,
  `be_std_mittwoch` double(4,2) NOT NULL,
  `be_std_donnerstag` double(4,2) NOT NULL,
  `be_std_freitag` double(4,2) NOT NULL,
  `be_std_samstag` double(4,2) NOT NULL,
  `be_std_sonntag` double(4,2) NOT NULL,
  `be_std_gesamt` double(4,2) NOT NULL,
  `be_eintrittsdatum` date NOT NULL,
  `be_urlaubstage` double(5,2) NOT NULL,
  `be_resturlaub` double(10,2) NOT NULL DEFAULT '0.00',
  `be_urlaub_aktuell` double(10,2) NOT NULL DEFAULT '0.00',
  `be_std_lohn` double(10,2) NOT NULL,
  `be_verrechnungssatz` double(10,2) NOT NULL,
  `be_zeiterfassung` varchar(10) NOT NULL,
  `be_sortierung` int(11) NOT NULL,
  `be_lastchange` datetime NOT NULL,
  `be_createdate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `benutzer`
--

INSERT INTO `benutzer` (`be_id`, `be_vorname`, `be_nachname`, `be_benutzername`, `be_passwort`, `be_benutzerlevel`, `be_aktiviert`, `be_demo`, `be_failedlogin_count`, `be_failedlogin_last`, `be_geloescht`, `be_std_montag`, `be_std_dienstag`, `be_std_mittwoch`, `be_std_donnerstag`, `be_std_freitag`, `be_std_samstag`, `be_std_sonntag`, `be_std_gesamt`, `be_eintrittsdatum`, `be_urlaubstage`, `be_resturlaub`, `be_urlaub_aktuell`, `be_std_lohn`, `be_verrechnungssatz`, `be_zeiterfassung`, `be_sortierung`, `be_lastchange`, `be_createdate`) VALUES
(1, '', '', 'swatermeyer', '37ada55d13dffddd5c432ad8f06c9fe3', 10, 1, 1, 0, '0000-00-00 00:00:00', 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '0000-00-00', 0.00, 0.00, 0.00, 0.00, 0.00, '', 0, '2021-06-21 20:26:52', '2021-06-21 20:26:52');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `benutzerverlauf`
--

CREATE TABLE `benutzerverlauf` (
  `bv_id` int(11) NOT NULL,
  `bv_benutzerID` int(11) NOT NULL,
  `bv_benutzerName` varchar(50) CHARACTER SET latin1 NOT NULL,
  `bv_requestURI` varchar(250) CHARACTER SET latin1 NOT NULL,
  `bv_referer` varchar(250) CHARACTER SET latin1 NOT NULL,
  `bv_post` varchar(1000) CHARACTER SET latin1 NOT NULL,
  `bv_get` varchar(1000) CHARACTER SET latin1 NOT NULL,
  `bv_duration` int(20) NOT NULL,
  `bv_lastchange` datetime NOT NULL,
  `bv_createdate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `benutzerverlauf`
--
--
-- Tabellenstruktur für Tabelle `disposition`
--

CREATE TABLE `disposition` (
  `d_id` int(100) NOT NULL,
  `o_id` int(100) NOT NULL,
  `m_id` int(100) NOT NULL,
  `d_name` varchar(100) NOT NULL,
  `d_fuss` varchar(100) NOT NULL,
  `d_reihenfolge` int(10) NOT NULL,
  `d_lastchange` datetime NOT NULL,
  `d_createdate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gemeinde`
--

CREATE TABLE `gemeinde` (
  `g_id` int(10) NOT NULL,
  `k_id` int(3) NOT NULL,
  `g_kirche` varchar(100) NOT NULL,
  `g_kirche_aid` int(11) NOT NULL,
  `g_ranschrift` varchar(100) NOT NULL,
  `g_rechnung_aid` int(11) NOT NULL,
  `g_rgemeinde` varchar(100) NOT NULL,
  `g_kundennr` varchar(32) DEFAULT NULL,
  `b_id` int(10) NOT NULL,
  `b_distanz` int(10) NOT NULL,
  `b_fahrzeit` varchar(10) NOT NULL,
  `g_aktiv` varchar(1) NOT NULL DEFAULT '1',
  `a_hauptid` varchar(5) NOT NULL,
  `g_lastchange` datetime NOT NULL,
  `g_createdate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gemeindeansprechpartner`
--

CREATE TABLE `gemeindeansprechpartner` (
  `g_id` int(10) NOT NULL,
  `a_id` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `http_session`
--

CREATE TABLE `http_session` (
  `id2` int(11) NOT NULL,
  `id` text CHARACTER SET latin1 NOT NULL,
  `data` text CHARACTER SET latin1,
  `expire` int(11) NOT NULL,
  `session_start` varchar(15) CHARACTER SET latin1 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `http_session`
--

INSERT INTO `http_session` (`id2`, `id`, `data`, `expire`, `session_start`) VALUES
(5623, '95e54a3153031aa1ab9a5cf544214f97', 'cmVxdWVzdHxhOjM6e3M6MTA6Imxhc3RhY3Rpb24iO2k6MTYyNDMwMDkyMTtzOjExOiJsYXN0dHJhY2tpZCI7czoyOiIzMCI7czo1OiJ3b2NoZSI7aToxNjI0MzAwOTE0O311c2VyfGE6Mzp7czoxMjoiYmVudXR6ZXJuYW1lIjtzOjExOiJzd2F0ZXJtZXllciI7czo4OiJwYXNzd29ydCI7czozMjoiMzdhZGE1NWQxM2RmZmRkZDVjNDMyYWQ4ZjA2YzlmZTMiO3M6MjoiaWQiO3M6MToiMSI7fXN1Y2hiZWdyaWZmfGE6NTp7czo4OiJvc3RfaWQtMSI7czowOiIiO3M6ODoib3N0X2lkLTIiO3M6MDoiIjtzOjg6Im9zdF9pZC0zIjtzOjA6IiI7czoxNToibmljaHR6dWdlb3JkbmV0IjtzOjA6IiI7czo2OiJzdWJtaXQiO3M6MDoiIjt9bGV0enRlX3Byb2pla3RfaWR8aTowOw==', 1624302721, '1624299946');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `konfession`
--

CREATE TABLE `konfession` (
  `k_id` int(11) NOT NULL,
  `k_name` varchar(100) NOT NULL,
  `k_anschrift` varchar(100) NOT NULL,
  `k_genitiv` varchar(100) NOT NULL,
  `k_kurzform` varchar(100) NOT NULL,
  `k_lastchange` datetime NOT NULL,
  `k_createdate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `konfession`
--

INSERT INTO `konfession` (`k_id`, `k_name`, `k_anschrift`, `k_genitiv`, `k_kurzform`, `k_lastchange`, `k_createdate`) VALUES
(1, 'Evangelisch', 'Evangelische', 'evangelischen', 'Ev.', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Katholisch', 'Katholische', 'katholischen', 'Kath.', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Sonstiges', '', '', 'Sonst.', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'Evangelisch-Methodistisch', 'Evangelisch-Methodistischen', 'evangelisch-Methodistischen', 'Ev.-metho.', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'Evangelisch-Luthrisch', 'Evangelisch-Luthrischen', 'evangelisch-Luthrischen', 'Ev.-luth.', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `option_meta`
--

CREATE TABLE `option_meta` (
  `option_id` int(100) NOT NULL,
  `option_modul` varchar(100) NOT NULL,
  `option_name` varchar(100) NOT NULL,
  `option_value` varchar(500) NOT NULL,
  `option_autoload` tinyint(1) NOT NULL,
  `option_comment` varchar(200) NOT NULL,
  `option_editable` tinyint(1) NOT NULL,
  `option_lastchange` datetime NOT NULL,
  `option_createdate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `option_meta`
--

INSERT INTO `option_meta` (`option_id`, `option_modul`, `option_name`, `option_value`, `option_autoload`, `option_comment`, `option_editable`, `option_lastchange`, `option_createdate`) VALUES
(1, 'Rechnung', 'rechnung_pflege_text', 'Standardtext Pflegevertrag kann in den Einstellungen geändert werden', 1, 'Standard Text für Pflegerechnungen nach Pflegevertrag', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Rechnung', 'rechnung_auftrag_text', 'Standardtext Pflegevertrag kann in den Einstellungen geändert werden', 1, 'Standard Text für Pflegerechnungen nach Auftrag', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Rechnung', 'rechnung_angebot_text', 'Standardtext Pflegevertrag kann in den Einstellungen geändert werden', 1, 'Standard Text für Pflegerechnungen nach Angebot', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'Rechnung', 'standardzahlungsziel', '14', 1, 'Standard Zahlungsziel für alle Rechnungen', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'Rechnung', 'pflegerechnung_pos_1', 'Motor geölt', 1, '1. Standard Position für Pflegerechnungen', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'Rechnung', 'pflegerechnung_pos_2', 'Winddruck geprüft', 1, '2. Standard Position für Pflegerechnungen', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'Rechnung', 'pflegerechnung_pos_3', '', 1, '3. Standard Position für Pflegerechnungen', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 'Rechnung', 'rechnung_zahlungsziele', '7,10,14,21,28,30,31', 1, 'Standardzahlungsziele', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'Einstellung', 'default_redirect_seconds_true', '1', 1, 'Bei erfolgreicher Meldung nach X Sekunden weiterleiten', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 'Einstellung', 'default_redirect_seconds_false', '4', 1, 'Bei negativer Meldung nach X Sekunden weiterleiten', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 'Einstellung', 'max_idle_time', '30', 1, 'Maximale Zeit der Inaktivität (... für Logout)', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 'Einstellung', 'min_user_password_length', '5', 1, 'Minimum Password Länge der Benutzer', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 'Rechnung', 'rechnung_abschlag1_text', 'Standardtext Abschlagsrechnung I', 1, 'Abschlagstext für 1. Abschlagsrechnung', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 'Rechnung', 'rechnung_abschlag2_text', 'Standardtext Abschlagsrechnung II', 1, 'Abschlagtext für 2. Abschlagsrechnung', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 'Rechnung', 'rechnung_abschlag3_text', 'Standardtext Abschlagsrechnung III', 1, 'Abschlagstext für 3. Abschlagsrechnung', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 'Rechnung', 'rechnung_abschlag1_prozent', '30', 1, 'Prozentangabe für die 1. Abschlagsrechnung', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 'Rechnung', 'rechnung_abschlag2_prozent', '30', 1, 'Prozentangabe für die 2. Abschlagsrechnung', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 'Rechnung', 'rechnung_abschlag3_prozent', '20', 1, 'Prozentangabe für die 3. Abschlagsrechnung', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 'Projekt', 'standard_stunden_montag', '8.25', 1, 'Standard Arbeitsstunden Montag', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 'Projekt', 'standard_stunden_dienstag', '8.25', 1, 'Standard Arbeitsstunden Dienstag', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 'Projekt', 'standard_stunden_mittwoch', '8.25', 1, 'Standard Arbeitsstunden Mittwoch', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 'Projekt', 'standard_stunden_donnerstag', '8.25', 1, 'Standard Arbeitsstunden Donnerstag', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 'Projekt', 'standard_stunden_freitag', '7', 1, 'Standard Arbeitsstunden Freitag', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 'Projekt', 'standard_stunden_samstag', '0', 1, 'Standard Arbeitsstunden Samstag', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 'Projekt', 'standard_stunden_sonntag', '0', 1, 'Standard Arbeitsstunden Sonntag', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 'Projekt', 'standard_urlaubstage', '28', 1, 'Standard Urlaubstage', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, 'Einstellung', 'site_title', 'TEST', 1, 'Seiten Title', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, 'Einstellung', 'admin_kurzinfo_hover_txt', 'Ihre Nachricht an den Systemadministrator...', 1, 'Hover Text in der Fusszeile', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, 'Wartung', 'pdf_untertext1', 'Der Text für den Wartungsbogen', 1, 'Text für die Kopfzeile auf dem Wartungsbogen', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, 'Wartung', 'pdf_untertext2', 'ist beliebig änderbar', 1, 'Text für die Kopfzeile auf dem Wartungsbogen', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, 'Einstellung', 'max_user_username_length', '16', 1, 'Maximale Benutzernamen Länge', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(35, 'Allgemein', 'kunde_ansprechpartner_id', '1', 1, 'ID des Kunden in der Ansprechpartner Tabelle', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(38, 'Einstellung', 'max_failed_logins', '5', 1, 'Maximale Anzahl Fehlversuche', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(39, 'Allgemein', 'default_top_register', '25', 1, 'Im Dispositionseditor die Registervorschläge', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(40, 'Projekt', 'kilometerpauschale', '0.3', 1, 'Kilometerpauschale für Reisekosten', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(41, 'Wartung', 'wartung_bogen_bildanzeige', 'true', 1, 'Bildanzeige auf dem Wartungsbogen an oder abschalten', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(56, 'Rechnung', 'pflegerechnung_pos_8', '', 1, '8. Standard Position für Pflegerechnungen', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(46, 'Allgemein', 'laenderauswahl', 'Deutschland, Österreich, Schweiz, Norwegen', 1, 'Auswahlliste für die Länder', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(44, 'Gemeinde', 'gemeinde_anzahl_listengruppierung', '20', 1, 'Anzahl der Gemeinden ab der die Gruppierung der Gemeindeliste aktiviert wird', 1, '2011-03-20 14:26:27', '2011-03-20 14:26:29'),
(45, 'Orgel', 'orgel_anzahl_listengruppierung', '20', 1, 'Anzahl der Gemeinden ab der die Gruppierung der Orgelliste aktiviert wird', 1, '2011-03-20 14:26:32', '2011-03-20 14:26:34'),
(55, 'Rechnung', 'pflegerechnung_pos_7', '', 1, '7. Standard Position für Pflegerechnungen', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(54, 'Rechnung', 'pflegerechnung_pos_6', '', 1, '6. Standard Position für Pflegerechnungen', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(52, 'Rechnung', 'pflegerechnung_pos_4', '', 1, '4. Standard Position für Pflegerechnungen', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(53, 'Rechnung', 'pflegerechnung_pos_5', '', 1, '5. Standard Position für Pflegerechnungen', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(57, 'Rechnung', 'pflegerechnung_pos_9', '', 1, '9. Standard Position für Pflegerechnungen', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(58, 'Rechnung', 'pflegerechnung_pos_10', '', 1, '10. Standard Position für Pflegerechnungen', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(59, 'Rechnung', 'rechnung_pflege_naechste_nummer', '21', 1, 'Die naechste Pflege Rechnungsnummer', 1, '2015-05-16 13:25:01', '2015-05-16 13:25:04'),
(60, 'Rechnung', 'rechnung_abschlag_naechste_nummer', '10', 1, 'Die naechste Abschlag Rechnungsnummer', 1, '2015-05-16 13:25:01', '2015-05-16 13:25:04'),
(63, 'Einstellung', 'orgelbank_api_key', '90481bd85266be39fb4110a4ee6ece9b', 1, 'Der Orgelbank API Key ', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(64, 'Rechnung', 'stundenrechnung_pos_1', '1. Standard Position für Stundenrechnungen', 1, '1. Standard Position für Stundenrechnungen', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(65, 'Rechnung', 'stundenrechnung_pos_2', '', 1, '2. Standard Position für Stundenrechnungen', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(66, 'Rechnung', 'stundenrechnung_pos_3', '', 1, '3. Standard Position für Stundenrechnungen', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(67, 'Rechnung', 'stundenrechnung_pos_4', '', 1, '4. Standard Position für Stundenrechnungen', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(68, 'Rechnung', 'stundenrechnung_pos_5', '', 1, '5. Standard Position für Stundenrechnungen', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(69, 'Rechnung', 'stundenrechnung_pos_6', '', 1, '6. Standard Position für Stundenrechnungen', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(70, 'Rechnung', 'stundenrechnung_pos_7', '', 1, '7. Standard Position für Stundenrechnungen', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(71, 'Rechnung', 'stundenrechnung_pos_8', '', 1, '8. Standard Position für Stundenrechnungen', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(72, 'Rechnung', 'stundenrechnung_pos_9', '', 1, '9. Standard Position für Stundenrechnungen', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(73, 'Rechnung', 'stundenrechnung_pos_10', '', 1, '10. Standard Position für Stundenrechnungen', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(74, 'Wartung', 'wartung_bogen_alle_ansprechpartner', 'true', 1, 'Alle Ansprechpartner auf dem Wartungsbogen anzeigen', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(75, 'Wartung', 'wartung_bogen_checkliste', 'true', 1, 'Checkliste auf dem Wartungsbogen anzeigen', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(76, 'Rechnung', 'rechnung_pflege_schlusstext', 'Vielen Dank für ihr Vertrauen', 1, 'Schlusstext für Pflege Rechnungen', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(77, 'Rechnung', 'rechnung_stunden_schlusstext', 'Vielen Dank für ihren Auftrag', 1, 'Schlusstext für Stunden Rechnungen', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(78, 'Gemeinde', 'gemeinde_liste_standard_sortierung', 'g_kirche', 1, 'Entweder \"kirche\" oder \"ort\"', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(79, 'Einstellung', 'cronjob_geostatus_limit', '30', 1, 'Wieviele Adressen der GeoStatus Cronjob pro Lauf verarbeiten soll', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `orgel`
--

CREATE TABLE `orgel` (
  `o_id` int(10) NOT NULL,
  `g_id` int(10) DEFAULT NULL,
  `ost_id` smallint(5) NOT NULL COMMENT 'Status',
  `o_baujahr` varchar(10) NOT NULL,
  `o_erbauer` varchar(100) NOT NULL,
  `o_renoviert` varchar(10) NOT NULL,
  `o_renovierer` varchar(100) NOT NULL,
  `ow_id` smallint(5) NOT NULL COMMENT 'Windlade',
  `os_id` smallint(5) NOT NULL COMMENT 'Spieltraktur',
  `or_id` smallint(5) NOT NULL COMMENT 'Registertratkru',
  `ok_id` smallint(5) NOT NULL COMMENT 'Koppeln',
  `o_anzahlregister` varchar(10) NOT NULL,
  `o_anmerkung` varchar(500) NOT NULL,
  `o_pflegevertrag` varchar(1) NOT NULL,
  `o_letztepflege` varchar(10) NOT NULL,
  `o_hauptstimmung` varchar(10) NOT NULL,
  `o_zyklus` varchar(10) NOT NULL DEFAULT '0',
  `o_massnahmen` varchar(200) NOT NULL,
  `o_manual1` varchar(100) NOT NULL,
  `o_manual2` varchar(100) NOT NULL,
  `o_manual3` varchar(100) NOT NULL,
  `o_manual4` varchar(100) NOT NULL,
  `o_manual5` varchar(100) NOT NULL,
  `o_m1wd` varchar(20) NOT NULL,
  `o_m2wd` varchar(20) NOT NULL,
  `o_m3wd` varchar(20) NOT NULL,
  `o_m4wd` varchar(20) NOT NULL,
  `o_m5wd` varchar(20) NOT NULL,
  `o_m6wd` varchar(20) NOT NULL,
  `o_m1groesse` varchar(20) NOT NULL,
  `o_m2groesse` varchar(20) NOT NULL,
  `o_m3groesse` varchar(20) NOT NULL,
  `o_m4groesse` varchar(20) NOT NULL,
  `o_m5groesse` varchar(20) NOT NULL,
  `o_m6groesse` varchar(20) NOT NULL,
  `o_pedal` varchar(10) NOT NULL,
  `o_stimmung` varchar(100) NOT NULL,
  `o_aktiv` varchar(10) NOT NULL,
  `o_kostenhauptstimmung` varchar(128) NOT NULL DEFAULT '',
  `o_kostenteilstimmung` varchar(128) NOT NULL DEFAULT '',
  `o_lastchange` datetime NOT NULL,
  `o_createdate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `orgel_koppel`
--

CREATE TABLE `orgel_koppel` (
  `ok_id` int(10) NOT NULL,
  `ok_name` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `orgel_koppel`
--

INSERT INTO `orgel_koppel` (`ok_id`, `ok_name`) VALUES
(1, 'Mechanisch'),
(2, 'Elektrisch'),
(3, 'Kombination'),
(4, 'Pneumatisch');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `orgel_registertraktur`
--

CREATE TABLE `orgel_registertraktur` (
  `or_id` int(10) NOT NULL,
  `or_name` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `orgel_registertraktur`
--

INSERT INTO `orgel_registertraktur` (`or_id`, `or_name`) VALUES
(1, 'Mechanisch'),
(2, 'Elektrisch'),
(3, 'Pneumatisch');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `orgel_spieltraktur`
--

CREATE TABLE `orgel_spieltraktur` (
  `os_id` int(10) NOT NULL,
  `os_name` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `orgel_spieltraktur`
--

INSERT INTO `orgel_spieltraktur` (`os_id`, `os_name`) VALUES
(1, 'Mechanisch'),
(2, 'Elektrisch'),
(3, 'Pneumatisch');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `orgel_status`
--

CREATE TABLE `orgel_status` (
  `ost_id` int(10) NOT NULL,
  `ost_name` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `orgel_status`
--

INSERT INTO `orgel_status` (`ost_id`, `ost_name`) VALUES
(1, 'Neubau'),
(2, 'Renoviert'),
(3, 'Restauriert'),
(0, 'Reinigung');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `orgel_windlade`
--

CREATE TABLE `orgel_windlade` (
  `ow_id` int(10) NOT NULL,
  `ow_name` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `orgel_windlade`
--

INSERT INTO `orgel_windlade` (`ow_id`, `ow_name`) VALUES
(1, 'Schleiflade'),
(2, 'Kegellade'),
(3, 'Taschenlade'),
(4, 'Springlade');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `projekt`
--

CREATE TABLE `projekt` (
  `proj_id` int(10) NOT NULL,
  `proj_bezeichnung` varchar(200) NOT NULL,
  `proj_beschreibung` varchar(500) NOT NULL,
  `proj_start` date NOT NULL,
  `proj_ende` date NOT NULL,
  `g_id` int(10) NOT NULL,
  `proj_geloescht` int(11) NOT NULL,
  `proj_archivdatum` datetime NOT NULL,
  `proj_archiviert` int(1) NOT NULL,
  `proj_angebotspreis` double(10,2) NOT NULL,
  `proj_keinezeitenfuer` varchar(255) NOT NULL,
  `proj_sortierung` int(11) NOT NULL,
  `proj_lastchange` datetime NOT NULL,
  `proj_createdate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `projekt_aufgabe`
--

CREATE TABLE `projekt_aufgabe` (
  `proj_id` int(10) NOT NULL,
  `au_id` int(10) NOT NULL,
  `pa_reihenfolge` int(11) NOT NULL,
  `pa_plankosten` double(10,2) NOT NULL,
  `pa_lastchange` datetime NOT NULL,
  `pa_createdate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `projekt_rechnung`
--

CREATE TABLE `projekt_rechnung` (
  `pr_id` int(10) NOT NULL,
  `proj_id` int(10) NOT NULL,
  `pa_id` int(10) NOT NULL,
  `pr_nummer` varchar(100) NOT NULL,
  `pr_datum` date NOT NULL,
  `pr_kommentar` text NOT NULL,
  `pr_betrag` double(10,2) NOT NULL,
  `pr_lieferant` varchar(100) NOT NULL,
  `pr_lastchange` datetime NOT NULL,
  `pr_createdate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rechnung_abschlag`
--

CREATE TABLE `rechnung_abschlag` (
  `ra_id` int(11) NOT NULL,
  `g_id` int(10) NOT NULL,
  `ra_nummer` varchar(11) NOT NULL,
  `ra_abschlagsatz` double(10,2) NOT NULL,
  `ra_anr` int(11) NOT NULL,
  `re_id` int(10) NOT NULL DEFAULT '0',
  `ra_datum` date NOT NULL,
  `ra_zieldatum` date NOT NULL,
  `ra_titel` varchar(200) NOT NULL,
  `ra_einleitung` varchar(500) NOT NULL,
  `ra_gesamtnetto` double(10,2) NOT NULL,
  `ra_gesamtmwst` double(10,2) NOT NULL,
  `ra_gesamtbrutto` double(10,2) NOT NULL,
  `ra_nettobetrag` double(10,2) NOT NULL,
  `ra_bruttobetrag` double(10,2) NOT NULL,
  `ra_mwst` double(10,2) NOT NULL,
  `ra_mwstsatz` double(3,2) NOT NULL,
  `ra_eingangsdatum` date NOT NULL,
  `ra_eingangsbetrag` double(10,2) NOT NULL,
  `ra_eingangsanmerkung` varchar(255) NOT NULL,
  `ra_lastchange` datetime NOT NULL,
  `ra_createdate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rechnung_end`
--

CREATE TABLE `rechnung_end` (
  `re_id` int(11) NOT NULL,
  `g_id` int(10) NOT NULL,
  `re_nummer` varchar(50) NOT NULL,
  `re_datum` date NOT NULL,
  `re_zieldatum` date NOT NULL,
  `re_ra1` int(10) NOT NULL,
  `re_ra2` int(10) NOT NULL,
  `re_ra3` int(10) NOT NULL,
  `re_titel` varchar(500) NOT NULL,
  `re_text` varchar(500) NOT NULL,
  `re_gesamtnetto` double(10,2) NOT NULL,
  `re_gesamtbrutto` double(10,2) NOT NULL,
  `re_gesamtmwst` double(10,2) NOT NULL,
  `re_bruttobetrag` double(10,2) NOT NULL,
  `re_mwst` double(10,2) NOT NULL,
  `re_mwstsatz` double(3,2) NOT NULL,
  `re_nettobetrag` double(10,2) NOT NULL,
  `re_eingangsdatum` date NOT NULL,
  `re_eingangsbetrag` double(10,2) NOT NULL,
  `re_eingangsanmerkung` varchar(255) NOT NULL,
  `re_restbetrag` double(10,2) NOT NULL,
  `re_lastchange` datetime NOT NULL,
  `re_createdate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rechnung_pflege`
--

CREATE TABLE `rechnung_pflege` (
  `rp_id` int(11) NOT NULL,
  `rp_nummer` varchar(11) NOT NULL,
  `rp_text1` varchar(500) NOT NULL,
  `rp_pos1` varchar(200) NOT NULL,
  `rp_pos2` varchar(200) NOT NULL,
  `rp_pos3` varchar(200) NOT NULL,
  `rp_pos4` varchar(200) NOT NULL,
  `rp_pos5` varchar(200) NOT NULL,
  `rp_pos6` varchar(200) NOT NULL,
  `rp_pos7` varchar(200) NOT NULL,
  `rp_pos8` varchar(200) NOT NULL,
  `rp_pos9` varchar(200) NOT NULL,
  `rp_pos10` varchar(200) NOT NULL,
  `rp_text2` varchar(500) NOT NULL,
  `rp_nettobetrag` double(10,2) NOT NULL,
  `rp_bruttobetrag` double(10,2) NOT NULL,
  `rp_mwst` double(10,2) NOT NULL,
  `rp_mwstsatz` double(3,2) NOT NULL,
  `rp_betrag` varchar(10) NOT NULL,
  `rp_pflegekosten` double(10,2) NOT NULL,
  `rp_fahrtkosten` double(10,2) NOT NULL,
  `rp_eingangsbetrag` double(10,2) NOT NULL,
  `rp_eingangsdatum` date NOT NULL,
  `rp_eingangsanmerkung` varchar(255) NOT NULL,
  `g_id` int(10) NOT NULL,
  `rp_datum` date NOT NULL,
  `rp_zieldatum` date NOT NULL,
  `rp_lastchange` datetime NOT NULL,
  `rp_createdate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rechnung_position`
--

CREATE TABLE `rechnung_position` (
  `rpos_id` int(11) NOT NULL,
  `rpos_type` int(11) NOT NULL,
  `r_id` int(11) NOT NULL,
  `rpos_position` int(11) NOT NULL,
  `rpos_text` varchar(255) NOT NULL,
  `rpos_createdate` datetime NOT NULL,
  `rpos_lastchange` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rechnung_stunde`
--

CREATE TABLE `rechnung_stunde` (
  `rs_id` int(11) NOT NULL,
  `g_id` int(10) NOT NULL,
  `rs_nummer` varchar(11) NOT NULL,
  `rs_datum` date NOT NULL,
  `rs_zieldatum` date NOT NULL,
  `rs_text1` varchar(500) NOT NULL,
  `rs_pos1` varchar(200) NOT NULL,
  `rs_pos2` varchar(200) NOT NULL,
  `rs_pos3` varchar(200) NOT NULL,
  `rs_pos4` varchar(200) NOT NULL,
  `rs_pos5` varchar(200) NOT NULL,
  `rs_pos6` varchar(200) NOT NULL,
  `rs_pos7` varchar(200) NOT NULL,
  `rs_pos8` varchar(200) NOT NULL,
  `rs_pos9` varchar(200) NOT NULL,
  `rs_pos10` varchar(200) NOT NULL,
  `rs_text2` varchar(500) NOT NULL,
  `rs_azubi_lohn` double(10,2) NOT NULL,
  `rs_azubi_std` double(10,2) NOT NULL,
  `rs_geselle_lohn` double(10,2) NOT NULL,
  `rs_geselle_std` double(10,2) NOT NULL,
  `rs_material` double NOT NULL,
  `rs_fahrtkosten` double(10,2) NOT NULL,
  `rs_nettobetrag` double(10,2) NOT NULL,
  `rs_bruttobetrag` double(10,2) NOT NULL,
  `rs_mwst` double(10,2) NOT NULL,
  `rs_mwstsatz` double(3,2) NOT NULL,
  `rs_eingangsdatum` date NOT NULL,
  `rs_eingangsbetrag` double(10,2) NOT NULL,
  `rs_eingangsanmerkung` varchar(255) NOT NULL,
  `rs_lastchange` datetime NOT NULL,
  `rs_createdate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `register_groessen`
--

CREATE TABLE `register_groessen` (
  `rg_id` int(5) NOT NULL,
  `rg_reihenfolge` int(3) NOT NULL,
  `rg_fuss` varchar(10) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `register_groessen`
--

INSERT INTO `register_groessen` (`rg_id`, `rg_reihenfolge`, `rg_fuss`) VALUES
(1, 1, '32'),
(2, 2, '16'),
(3, 3, '10 2/3'),
(4, 4, '8'),
(5, 5, '6'),
(6, 6, '4'),
(7, 7, '3'),
(8, 9, '2'),
(9, 10, '1 3/5'),
(10, 11, '1 1/3'),
(11, 13, '1'),
(12, 14, '2/3'),
(14, 16, '1/3'),
(15, 17, '/'),
(13, 15, '1/2'),
(18, 12, '1 1/7'),
(19, 8, '2 2/3');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `reisekosten`
--

CREATE TABLE `reisekosten` (
  `rk_id` int(10) NOT NULL,
  `proj_id` int(10) NOT NULL,
  `be_id` int(10) NOT NULL,
  `rk_datum` date NOT NULL,
  `rk_timestamp` int(11) NOT NULL,
  `rk_jahr` int(4) NOT NULL,
  `rk_kw` int(2) NOT NULL,
  `rk_spesen` double(10,2) NOT NULL,
  `rk_hotel` double(10,2) NOT NULL,
  `rk_km` double(10,2) NOT NULL,
  `rk_kmkosten` double(10,2) NOT NULL,
  `rk_gesamt` double(10,2) NOT NULL,
  `rk_lastchange` datetime NOT NULL,
  `rk_createdate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `seitenstatistik`
--

CREATE TABLE `seitenstatistik` (
  `ss_url` varchar(200) NOT NULL,
  `ss_description` varchar(200) NOT NULL,
  `ss_count` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `seitenstatistik`
--

INSERT INTO `seitenstatistik` (`ss_url`, `ss_description`, `ss_count`) VALUES
('index.php?page=1&do=1', 'GemeindeController::zeigeGemeindeListe', 2),
('index.php?page=5&do=89', 'RechnungController::zeigeRechnungsListe', 3),
('index.php?page=3&do=40', 'AnsprechpartnerController::zeigeAnsprechpartnerVerwaltung', 3),
('index.php?page=6&do=100', 'ProjektController::zeigeProjekte', 5),
('index.php?page=7&do=123', 'EinstellungController::zeigeOptions', 5),
('index.php?page=8&do=142', 'BenutzerController::zeigeZeiterfassung', 5),
('index.php?page=2&do=20', 'OrgelController::zeigeOrgelListe', 2),
('index.php?page=6&do=101', 'ProjektController::zeigeZeiterfassungWrapper', 1),
('index.php?page=6&do=108', 'ProjektController::zeigeArbeitszeitVerwaltung', 1),
('index.php?page=6&do=112', 'ProjektController::zeigeMaterialRechnungen', 1),
('index.php?page=6&do=102', 'ProjektController::zeigeAufgabenVerwaltung', 7),
('index.php?page=7&do=125', 'EinstellungController::showOptionMeta', 1),
('index.php?page=7&do=121', 'EinstellungController::zeigeFirmenDaten', 1),
('index.php?page=7&do=120', 'EinstellungController::zeigeRechnungsEinstellungen', 1),
('index.php?page=6&do=103', 'ProjektController::mitarbeiterVerwalten', 3);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `wartung`
--

CREATE TABLE `wartung` (
  `w_id` int(10) NOT NULL,
  `o_id` int(10) NOT NULL,
  `w_datum` date NOT NULL,
  `w_bemerkung` varchar(500) NOT NULL,
  `w_temperatur` varchar(10) NOT NULL,
  `w_luftfeuchtigkeit` varchar(10) NOT NULL,
  `w_stimmton` varchar(10) NOT NULL,
  `w_stimmung` int(1) NOT NULL,
  `m_id_1` int(4) NOT NULL,
  `m_id_2` int(4) NOT NULL,
  `m_id_3` int(4) NOT NULL,
  `w_ma1_iststd` double(5,2) NOT NULL,
  `w_ma2_iststd` double(5,2) NOT NULL,
  `w_ma3_iststd` double(5,2) NOT NULL,
  `w_ma1_faktstd` double(5,2) NOT NULL,
  `w_ma2_faktstd` double(5,2) NOT NULL,
  `w_ma3_faktstd` double(5,2) NOT NULL,
  `w_tastenhalter` tinyint(1) NOT NULL,
  `w_material` varchar(500) NOT NULL,
  `w_abrechnungsart` int(2) NOT NULL,
  `w_lastchange` datetime NOT NULL,
  `w_changeby` varchar(100) NOT NULL,
  `w_createdate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `zzz_views`
-- (Siehe unten für die tatsächliche Ansicht)
--
CREATE TABLE `zzz_views` (
`bv_benutzername` int(1)
,`bv_count` int(1)
,`bv_min` int(1)
,`bv_max` int(1)
);

-- --------------------------------------------------------


-- --------------------------------------------------------

--
-- Struktur des Views `zzz_views`
--
DROP TABLE IF EXISTS `zzz_views`;

CREATE ALGORITHM=UNDEFINED DEFINER=`d036ca68`@`85.13.132.184` SQL SECURITY DEFINER VIEW `zzz_views`  AS SELECT 1 AS `bv_benutzername`, 1 AS `bv_count`, 1 AS `bv_min`, 1 AS `bv_max` ;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `adresse`
--
ALTER TABLE `adresse`
  ADD PRIMARY KEY (`ad_id`);

--
-- Indizes für die Tabelle `ansprechpartner`
--
ALTER TABLE `ansprechpartner`
  ADD PRIMARY KEY (`a_id`),
  ADD KEY `ad_id` (`ad_id`);

--
-- Indizes für die Tabelle `arbeitstag`
--
ALTER TABLE `arbeitstag`
  ADD PRIMARY KEY (`at_id`),
  ADD UNIQUE KEY `at_datum` (`at_datum`,`be_id`,`proj_id`,`au_id`);

--
-- Indizes für die Tabelle `arbeitswoche`
--
ALTER TABLE `arbeitswoche`
  ADD PRIMARY KEY (`aw_id`),
  ADD UNIQUE KEY `be_id` (`be_id`,`aw_kw`,`aw_jahr`);

--
-- Indizes für die Tabelle `aufgabe`
--
ALTER TABLE `aufgabe`
  ADD PRIMARY KEY (`au_id`);

--
-- Indizes für die Tabelle `aufgabe_mitarbeiter`
--
ALTER TABLE `aufgabe_mitarbeiter`
  ADD PRIMARY KEY (`au_id`,`be_id`);

--
-- Indizes für die Tabelle `benutzer`
--
ALTER TABLE `benutzer`
  ADD PRIMARY KEY (`be_id`),
  ADD UNIQUE KEY `be_benutzername` (`be_benutzername`);

--
-- Indizes für die Tabelle `benutzerverlauf`
--
ALTER TABLE `benutzerverlauf`
  ADD PRIMARY KEY (`bv_id`);

--
-- Indizes für die Tabelle `disposition`
--
ALTER TABLE `disposition`
  ADD PRIMARY KEY (`d_id`);

--
-- Indizes für die Tabelle `gemeinde`
--
ALTER TABLE `gemeinde`
  ADD PRIMARY KEY (`g_id`),
  ADD KEY `g_kirche_aid` (`g_kirche_aid`),
  ADD KEY `g_rechnung_aid` (`g_rechnung_aid`);

--
-- Indizes für die Tabelle `gemeindeansprechpartner`
--
ALTER TABLE `gemeindeansprechpartner`
  ADD PRIMARY KEY (`g_id`,`a_id`);

--
-- Indizes für die Tabelle `http_session`
--
ALTER TABLE `http_session`
  ADD PRIMARY KEY (`id2`),
  ADD KEY `expire` (`expire`);

--
-- Indizes für die Tabelle `konfession`
--
ALTER TABLE `konfession`
  ADD PRIMARY KEY (`k_id`);

--
-- Indizes für die Tabelle `option_meta`
--
ALTER TABLE `option_meta`
  ADD PRIMARY KEY (`option_id`);

--
-- Indizes für die Tabelle `orgel`
--
ALTER TABLE `orgel`
  ADD PRIMARY KEY (`o_id`);

--
-- Indizes für die Tabelle `orgel_koppel`
--
ALTER TABLE `orgel_koppel`
  ADD PRIMARY KEY (`ok_id`);

--
-- Indizes für die Tabelle `orgel_registertraktur`
--
ALTER TABLE `orgel_registertraktur`
  ADD PRIMARY KEY (`or_id`);

--
-- Indizes für die Tabelle `orgel_spieltraktur`
--
ALTER TABLE `orgel_spieltraktur`
  ADD PRIMARY KEY (`os_id`);

--
-- Indizes für die Tabelle `orgel_status`
--
ALTER TABLE `orgel_status`
  ADD PRIMARY KEY (`ost_id`);

--
-- Indizes für die Tabelle `orgel_windlade`
--
ALTER TABLE `orgel_windlade`
  ADD PRIMARY KEY (`ow_id`);

--
-- Indizes für die Tabelle `projekt`
--
ALTER TABLE `projekt`
  ADD PRIMARY KEY (`proj_id`);

--
-- Indizes für die Tabelle `projekt_aufgabe`
--
ALTER TABLE `projekt_aufgabe`
  ADD PRIMARY KEY (`proj_id`,`au_id`);

--
-- Indizes für die Tabelle `projekt_rechnung`
--
ALTER TABLE `projekt_rechnung`
  ADD PRIMARY KEY (`pr_id`);

--
-- Indizes für die Tabelle `rechnung_abschlag`
--
ALTER TABLE `rechnung_abschlag`
  ADD PRIMARY KEY (`ra_id`);

--
-- Indizes für die Tabelle `rechnung_end`
--
ALTER TABLE `rechnung_end`
  ADD PRIMARY KEY (`re_id`);

--
-- Indizes für die Tabelle `rechnung_pflege`
--
ALTER TABLE `rechnung_pflege`
  ADD PRIMARY KEY (`rp_id`);

--
-- Indizes für die Tabelle `rechnung_position`
--
ALTER TABLE `rechnung_position`
  ADD PRIMARY KEY (`rpos_id`);

--
-- Indizes für die Tabelle `rechnung_stunde`
--
ALTER TABLE `rechnung_stunde`
  ADD PRIMARY KEY (`rs_id`);

--
-- Indizes für die Tabelle `register_groessen`
--
ALTER TABLE `register_groessen`
  ADD PRIMARY KEY (`rg_id`);

--
-- Indizes für die Tabelle `reisekosten`
--
ALTER TABLE `reisekosten`
  ADD PRIMARY KEY (`rk_id`),
  ADD UNIQUE KEY `proj_id` (`proj_id`,`be_id`,`rk_jahr`,`rk_kw`),
  ADD UNIQUE KEY `proj_id_2` (`proj_id`,`be_id`,`rk_jahr`,`rk_kw`);

--
-- Indizes für die Tabelle `seitenstatistik`
--
ALTER TABLE `seitenstatistik`
  ADD PRIMARY KEY (`ss_url`);

--
-- Indizes für die Tabelle `wartung`
--
ALTER TABLE `wartung`
  ADD PRIMARY KEY (`w_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `adresse`
--
ALTER TABLE `adresse`
  MODIFY `ad_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `ansprechpartner`
--
ALTER TABLE `ansprechpartner`
  MODIFY `a_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `arbeitstag`
--
ALTER TABLE `arbeitstag`
  MODIFY `at_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `arbeitswoche`
--
ALTER TABLE `arbeitswoche`
  MODIFY `aw_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `aufgabe`
--
ALTER TABLE `aufgabe`
  MODIFY `au_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=277;

--
-- AUTO_INCREMENT für Tabelle `benutzer`
--
ALTER TABLE `benutzer`
  MODIFY `be_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `benutzerverlauf`
--
ALTER TABLE `benutzerverlauf`
  MODIFY `bv_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT für Tabelle `disposition`
--
ALTER TABLE `disposition`
  MODIFY `d_id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `gemeinde`
--
ALTER TABLE `gemeinde`
  MODIFY `g_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `http_session`
--
ALTER TABLE `http_session`
  MODIFY `id2` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5624;

--
-- AUTO_INCREMENT für Tabelle `konfession`
--
ALTER TABLE `konfession`
  MODIFY `k_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT für Tabelle `option_meta`
--
ALTER TABLE `option_meta`
  MODIFY `option_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT für Tabelle `orgel`
--
ALTER TABLE `orgel`
  MODIFY `o_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `orgel_koppel`
--
ALTER TABLE `orgel_koppel`
  MODIFY `ok_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `orgel_registertraktur`
--
ALTER TABLE `orgel_registertraktur`
  MODIFY `or_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `orgel_spieltraktur`
--
ALTER TABLE `orgel_spieltraktur`
  MODIFY `os_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `orgel_windlade`
--
ALTER TABLE `orgel_windlade`
  MODIFY `ow_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `projekt`
--
ALTER TABLE `projekt`
  MODIFY `proj_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `projekt_rechnung`
--
ALTER TABLE `projekt_rechnung`
  MODIFY `pr_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `rechnung_abschlag`
--
ALTER TABLE `rechnung_abschlag`
  MODIFY `ra_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `rechnung_end`
--
ALTER TABLE `rechnung_end`
  MODIFY `re_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `rechnung_pflege`
--
ALTER TABLE `rechnung_pflege`
  MODIFY `rp_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `rechnung_position`
--
ALTER TABLE `rechnung_position`
  MODIFY `rpos_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `rechnung_stunde`
--
ALTER TABLE `rechnung_stunde`
  MODIFY `rs_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `register_groessen`
--
ALTER TABLE `register_groessen`
  MODIFY `rg_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT für Tabelle `reisekosten`
--
ALTER TABLE `reisekosten`
  MODIFY `rk_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `wartung`
--
ALTER TABLE `wartung`
  MODIFY `w_id` int(10) NOT NULL AUTO_INCREMENT;
COMMIT;

CREATE VIEW `rechnung_view`  AS  select `r`.`rp_id` AS `r_id`,`r`.`rp_nummer` AS `r_nummer`,1 AS `r_typid`,_utf8'Pflegerechnung' AS `r_typ`,`r`.`rp_nettobetrag` AS `r_nettobetrag`,`r`.`rp_bruttobetrag` AS `r_bruttobetrag`,`r`.`g_id` AS `g_id`,`g`.`g_kirche` AS `g_kirche`,`r`.`rp_datum` AS `r_datum`,`r`.`rp_eingangsdatum` AS `r_eingangsdatum`,`r`.`rp_eingangsbetrag` AS `r_eingangsbetrag`,`r`.`rp_eingangsanmerkung` AS `r_eingangsanmerkung` from (`rechnung_pflege` `r` join `gemeinde` `g`) where (`r`.`g_id` = `g`.`g_id`) union select `r`.`rs_id` AS `rs_id`,`r`.`rs_nummer` AS `rs_nummer`,2 AS `2`,_utf8'Stundenrechnung' AS `Stundenrechnung`,`r`.`rs_nettobetrag` AS `r_nettobetrag`,`r`.`rs_bruttobetrag` AS `r_bruttobetrag`,`r`.`g_id` AS `g_id`,`g`.`g_kirche` AS `g_kirche`,`r`.`rs_datum` AS `rs_datum`,`r`.`rs_eingangsdatum` AS `r_eingangsdatum`,`r`.`rs_eingangsbetrag` AS `r_eingangsbetrag`,`r`.`rs_eingangsanmerkung` AS `r_eingangsanmerkung` from (`rechnung_stunde` `r` join `gemeinde` `g`) where (`r`.`g_id` = `g`.`g_id`) union select `r`.`ra_id` AS `ra_id`,`r`.`ra_nummer` AS `ra_nummer`,3 AS `3`,_utf8'Abschlagsrechnung' AS `Abschlagsrechnung`,`r`.`ra_nettobetrag` AS `r_nettobetrag`,`r`.`ra_bruttobetrag` AS `r_bruttobetrag`,`r`.`g_id` AS `g_id`,`g`.`g_kirche` AS `g_kirche`,`r`.`ra_datum` AS `ra_datum`,`r`.`ra_eingangsdatum` AS `r_eingangsdatum`,`r`.`ra_eingangsbetrag` AS `r_eingangsbetrag`,`r`.`ra_eingangsanmerkung` AS `r_eingangsanmerkung` from (`rechnung_abschlag` `r` join `gemeinde` `g`) where (`r`.`g_id` = `g`.`g_id`) union select `r`.`re_id` AS `re_id`,`r`.`re_nummer` AS `re_nummer`,4 AS `4`,_utf8'Endrechnung' AS `Endrechnung`,`r`.`re_gesamtnetto` AS `r_gesamtnetto`,`r`.`re_bruttobetrag` AS `r_bruttobetrag`,`r`.`g_id` AS `g_id`,`g`.`g_kirche` AS `g_kirche`,`r`.`re_datum` AS `re_datum`,`r`.`re_eingangsdatum` AS `r_eingangsdatum`,`r`.`re_eingangsbetrag` AS `r_eingangsbetrag`,`r`.`re_eingangsanmerkung` AS `r_eingangsanmerkung` from (`rechnung_end` `r` join `gemeinde` `g`) where (`r`.`g_id` = `g`.`g_id`) ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


