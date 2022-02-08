CREATE TABLE `GoodlowEFC`.`Users` (
    `UserName` TEXT NOT NULL ,
    `UserPassword` TEXT NOT NULL ,
    `Adate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
    `ID` INT NOT NULL AUTO_INCREMENT ,
    PRIMARY KEY (`ID`)
) ENGINE = InnoDB;

CREATE TABLE `GoodlowEFC`.`LoginAuth` (
    `Token` TEXT NOT NULL ,
    `Expire` TIMESTAMP NOT NULL ,
    `UsersID` TEXT NOT NULL ,
    `IP` TEXT NOT NULL ,
    `Adate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
    `ID` INT NOT NULL AUTO_INCREMENT ,
    PRIMARY KEY (`ID`)
) ENGINE = InnoDB;



CREATE TABLE `GoodlowEFC`.`AdultClass` (
    `Title` text NOT NULL,
    `Image` text NOT NULL,
    `Content` text NOT NULL,
    `Adate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ID` int NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`ID`)
) ENGINE=InnoDB;



CREATE TABLE `GoodlowEFC`.`Blogs` (
    `Title` text NOT NULL,
    `Date` date NOT NULL,
    `Content` longtext NOT NULL,
    `Attachment` text,
    `Name` text NOT NULL,
    `Adate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ID` text NOT NULL,
    `RID` int NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`RID`)
) ENGINE=InnoDB;


CREATE TABLE `GoodlowEFC`.`Bulletins` (
    `Url` text NOT NULL,
    `Date` date NOT NULL,
    `Adate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ID` int NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`ID`)
) ENGINE=InnoDB;



CREATE TABLE `GoodlowEFC`.`Events` (
    `Title` text NOT NULL,
    `Date` datetime NOT NULL,
    `Location` text,
    `Content` text,
    `Adate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ID` int NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`ID`)
) ENGINE=InnoDB;


CREATE TABLE `GoodlowEFC`.`Resources` (
    `Title` text NOT NULL,
    `Link` text NOT NULL,
    `Content` text NOT NULL,
    `List` text NOT NULL,
    `Adate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ID` int NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`ID`)
) ENGINE=InnoDB;



CREATE TABLE `GoodlowEFC`.`Sermons` (
    `Title` text NOT NULL,
    `Date` date NOT NULL,
    `File` text,
    `Audio` text,
    `Tags` text,
    `Adate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `ID` text NOT NULL,
    `RID` int NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`RID`)
) ENGINE=InnoDB;



CREATE TABLE `GoodlowEFC`.`Youtube` (
    `Title` text NOT NULL,
    `VideoID` text NOT NULL,
    `Date` date NOT NULL,
    `About` text NULL,
    `Adate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ID` text NOT NULL,
    `RID` int NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`RID`)
) ENGINE=InnoDB;

CREATE TABLE `GoodlowEFC`.`Podcast` (
    `VideoID` text NOT NULL,
    `Date` datetime NOT NULL,
    `Adate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ID` text NOT NULL,
    `RID` int NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`RID`)
) ENGINE=InnoDB;


CREATE TABLE `GoodlowEFC`.`Prayers` (
    `Name` text  NOT NULL,
    `Email` text NOT NULL,
    `Message` text NOT NULL,
    `Adate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ID` int NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`ID`)
) ENGINE=InnoDB;

INSERT INTO `Users` (`UserName`, `UserPassword`) VALUES ('Owen','$2y$10$CBD/4DQjdteLGIJQNLZ.0OXP.h2EQDEpUlV5kn4sgiU3RtlB4g4vW');
