-- Use database
USE `Vacation`;

-- Users
-- luke@paradoxis.nl:test
-- jordivanderhek@gmail.com:pakmelk
INSERT INTO `Users` VALUES
	(1,'Luke','Paris','','0','luke@paradoxis.nl','removed for security',0),
	(2,'Jordi','Hek','van der','0','jordivanderhek@gmail.com','removed for security',120);

-- Challenge types
INSERT INTO `ChallengeTypes` VALUES 
	(1, "Photo challenge"),
	(2, "Location challenge");

-- Trips
INSERT INTO `Trips` VALUES
	(1, "Weekendje zuid-grans chateau", "Verzorgd hotel met rustige ligging nabij Place d'Italie.", "Parijs, Frankrijk", "532", "2", "2015-04-17", "2015-05-25"),
	(2, "Berlin Mark", "Prima gerenoveerd hotel om de hoek van de bekende Kurfurstendamm.", "Berlijn, Duitsland", "112", "2", "2015-06-01", "2015-06-04"),
	(3, "Central park", "Comfortabel hotel met een goede ligging in London. Prima ligging en service.", "London, Groot Brittannië", "59.99", "2", "2015-04-17", "2015-04-20");

-- Challenges
INSERT INTO `Challenges` VALUES
	(1, 1, "Big Ben Phototobomb", "Laat een vriend een foto van je nemen waar je voor iemand's camera springt met de Big Ben in de achtergrond.", 15),
	(2, 1, "Gebouw des wonderen", "Neem een foto van het meest opmerkelijke gebouw in de stad waar je huidig verblijft.", 10),
	(3, 2, "Stad sprint", "Race met een vriend om te zien wie het snelste naar de andere kant van de stad kan komen.", 10),
	(4, 2, "Vreemdelingen selfie", "Vind binnen 2 minuten een complete vreemdeling die met je op de foto wilt.", 15);

-- TripChallenges
INSERT INTO `TripChallenges` (`tcTripId`, `tcChaId`) VALUES (3, 1);