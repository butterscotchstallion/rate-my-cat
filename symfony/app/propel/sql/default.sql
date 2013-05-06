
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- cats
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `cats`;

CREATE TABLE `cats`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    `created_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARACTER SET='utf8';

-- ---------------------------------------------------------------------
-- cat_pictures
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `cat_pictures`;

CREATE TABLE `cat_pictures`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `cat_id` INTEGER NOT NULL,
    `filename` VARCHAR(255),
    `width` INTEGER,
    `height` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `FI__pictures_cat_id` (`cat_id`),
    CONSTRAINT `cat_pictures_cat_id`
        FOREIGN KEY (`cat_id`)
        REFERENCES `cats` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB CHARACTER SET='utf8';

-- ---------------------------------------------------------------------
-- cat_ratings
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `cat_ratings`;

CREATE TABLE `cat_ratings`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `cat_id` INTEGER NOT NULL,
    `width` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `FI__ratings_cat_id` (`cat_id`),
    CONSTRAINT `cat_ratings_cat_id`
        FOREIGN KEY (`cat_id`)
        REFERENCES `cats` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB CHARACTER SET='utf8';

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
