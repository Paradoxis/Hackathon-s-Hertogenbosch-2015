-- Database structure script
-- @project Hackathon 2015
-- @team Paradoxis (4)
-- @date 11-04-2015
-- @author Luke Paris

-- Drop database, full yolo mode during development
DROP DATABASE IF EXISTS `Vacation`;

-- Create database
CREATE DATABASE IF NOT EXISTS `Vacation`;

-- Use the newly created database
USE `Vacation`;

-- Layer 1:
-- Users table
CREATE TABLE IF NOT EXISTS `Users` (
	userId INT AUTO_INCREMENT,
	userFirstname NVARCHAR(32) NOT NULL,
	userLastname NVARCHAR(32) NOT NULL,
	userPrefix VARCHAR(16),
	userPhone VARCHAR(15), 
	userEmail VARCHAR(32) NOT NULL UNIQUE,
	userPassword CHAR(40) NOT NULL, -- Sha1 hash + salt
	userPoints INT DEFAULT 0 NOT NULL,
	PRIMARY KEY(userId)
);

-- Trips table
CREATE TABLE IF NOT EXISTS `Trips` (
	tripId INT AUTO_INCREMENT,
	tripName VARCHAR(64) NOT NULL,
	tripDescription TEXT,
	tripLocation VARCHAR(40),
	tripPrice DOUBLE NOT NULL,
	tripPeople INT NOT NULL DEFAULT 1,
	tripFromDate DATETIME NOT NULL,
	tripToDate DATETIME NOT NULL,
	PRIMARY KEY (tripId)
);

-- Challenge types table
CREATE TABLE IF NOT EXISTS `ChallengeTypes` (
	ctpId INT AUTO_INCREMENT,
	ctpName VARCHAR(32) NOT NULL,
	PRIMARY KEY(ctpId)
);


-- Layer 2:
-- Authentication tokens table
CREATE TABLE IF NOT EXISTS `AuthTokens` (
	authId INT AUTO_INCREMENT,
	authUserId INT NOT NULL UNIQUE,
	authToken CHAR(40) NOT NULL, -- Random sha1 hash
	PRIMARY KEY(authId)
);

-- Challenges table
CREATE TABLE IF NOT EXISTS `Challenges` (
	chaId INT AUTO_INCREMENT,
	chaTypeId INT NOT NULL,
	chaName VARCHAR(32) NOT NULL UNIQUE,
	chaDescription TEXT,
	chaPoints INT NOT NULL,
	PRIMARY KEY(chaId)
);


-- Layer 3:
-- Bookings table
CREATE TABLE IF NOT EXISTS `Bookings` (
	bkId INT AUTO_INCREMENT,
	bkTripId INT NOT NULL,
	bkUserId INT NOT NULL,
	bkDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY(bkId)
);

-- Trip challenges table (optional)
CREATE TABLE IF NOT EXISTS `TripChallenges` (
	tcId INT AUTO_INCREMENT,
	tcTripId INT NOT NULL,
	tcChaId INT NOT NULL,
	PRIMARY KEY(tcId)
);

-- User challenges
CREATE TABLE IF NOT EXISTS `UserChallenges` (
	ucId INT AUTO_INCREMENT,
	ucUserId INT NOT NULL,
	ucChaId INT NOT NULL,
	ucCompleted TINYINT(1) NOT NULL DEFAULT 0,
	ucDateStart TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	ucDateEnd DATETIME,
	PRIMARY KEY(ucId)
);


-- Relationships from layer 1
ALTER TABLE AuthTokens 		ADD CONSTRAINT FK_Users_AuthTokens 			FOREIGN KEY (authUserId) 	REFERENCES Users(userId);
ALTER TABLE Bookings 		ADD CONSTRAINT FK_Users_Bookings 			FOREIGN KEY (bkUserId) 		REFERENCES Users(userId);
ALTER TABLE UserChallenges 	ADD CONSTRAINT FK_Users_UserChallenges 		FOREIGN KEY (ucUserId) 		REFERENCES Users(userId);
ALTER TABLE Bookings 		ADD CONSTRAINT FK_Trips_Bookings 			FOREIGN KEY (bkTripId) 		REFERENCES Trips(tripId);
ALTER TABLE TripChallenges 	ADD CONSTRAINT FK_Trips_TripChallenges 		FOREIGN KEY (tcTripId) 		REFERENCES Trips(tripId);
ALTER TABLE Challenges 		ADD CONSTRAINT FK_ChallengeTypes_Challenges FOREIGN KEY (chaTypeId) 	REFERENCES ChallengeTypes(ctpId);

-- Relationships from layer 2
ALTER TABLE TripChallenges 	ADD CONSTRAINT FK_Challenges_TripChallenges FOREIGN KEY (tcChaId) 		REFERENCES Challenges(chaId);
ALTER TABLE UserChallenges 	ADD CONSTRAINT FK_Challenges_UserChallenges FOREIGN KEY (ucChaId) 		REFERENCES Challenges(chaId);