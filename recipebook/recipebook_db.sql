# ************************************************************
# Sequel Pro SQL dump
# Version 4004
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.5.25)
# Database: rb_db
# Generation Time: 2013-05-31 15:41:29 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;

INSERT INTO `category` (`category_id`, `cat_name`)
VALUES
	(0,'No Category'),
	(1,'Breakfast'),
	(2,'Lunch'),
	(3,'Dinner'),
	(4,'Appetizer'),
	(5,'Snack'),
	(6,'Dessert');

/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table diets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `diets`;

CREATE TABLE `diets` (
  `diet_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `diet_name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`diet_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `diets` WRITE;
/*!40000 ALTER TABLE `diets` DISABLE KEYS */;

INSERT INTO `diets` (`diet_id`, `diet_name`)
VALUES
	(0,'None'),
	(1,'Vegetarian'),
	(2,'Vegan'),
	(3,'Pescetarian');

/*!40000 ALTER TABLE `diets` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table favorites
# ------------------------------------------------------------

DROP TABLE IF EXISTS `favorites`;

CREATE TABLE `favorites` (
  `favorite_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `recipe_id` int(11) unsigned NOT NULL,
  `num_favs` int(11) unsigned NOT NULL,
  PRIMARY KEY (`favorite_id`),
  KEY `user_fk` (`user_id`),
  KEY `recipe_fk` (`recipe_id`),
  CONSTRAINT `recipe_fk` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`recipe_id`),
  CONSTRAINT `user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `favorites` WRITE;
/*!40000 ALTER TABLE `favorites` DISABLE KEYS */;

INSERT INTO `favorites` (`favorite_id`, `user_id`, `recipe_id`, `num_favs`)
VALUES
	(1,1,4,1),
	(2,2,2,1),
	(3,2,4,1),
	(4,2,5,1),
	(5,2,3,1),
	(6,1,7,1),
	(7,1,6,1),
	(8,1,5,1),
	(9,1,8,1),
	(10,1,3,1);

/*!40000 ALTER TABLE `favorites` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table followers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `followers`;

CREATE TABLE `followers` (
  `fol_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `follower_id` int(11) unsigned NOT NULL,
  `date_added` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`fol_id`),
  KEY `fol_user_fk` (`user_id`),
  KEY `follower_fk` (`follower_id`),
  CONSTRAINT `follower_fk` FOREIGN KEY (`follower_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `fol_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `followers` WRITE;
/*!40000 ALTER TABLE `followers` DISABLE KEYS */;

INSERT INTO `followers` (`fol_id`, `user_id`, `follower_id`, `date_added`)
VALUES
	(1,1,2,1370013503),
	(2,2,1,1370013790);

/*!40000 ALTER TABLE `followers` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ingredients
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ingredients`;

CREATE TABLE `ingredients` (
  `ingredient_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ingredient` varchar(255) DEFAULT NULL,
  `recipe_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`ingredient_id`),
  KEY `ingredient_fk` (`recipe_id`),
  CONSTRAINT `ingredient_fk` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`recipe_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `ingredients` WRITE;
/*!40000 ALTER TABLE `ingredients` DISABLE KEYS */;

INSERT INTO `ingredients` (`ingredient_id`, `ingredient`, `recipe_id`)
VALUES
	(1,'1 lemon',1),
	(2,'1 lb. lima beans',1),
	(3,'4 - 5oz salmon fillets',1),
	(4,'1/2 c yogurt',1),
	(5,'romaine lettuce',2),
	(6,'1c feta cheese',2),
	(7,'1lb. chicken breast',2),
	(8,'1 small onion',3),
	(9,'1lb. fettuccine',3),
	(10,'1 16oz jar roasted red peppers',3),
	(11,'1 lb. mushrooms',4),
	(12,'4 eggs',4),
	(13,'4 oz. cheese',4),
	(14,'1 stick butter',5),
	(15,'1 c cocoa',5),
	(16,'1 c flour',5),
	(17,'6 corn torillas',6),
	(18,'3 avocados',6),
	(19,'2 ears corn',6),
	(20,'1 c diced tomato',7),
	(21,'1/2 lb. quinoa',7),
	(22,'3 carrots',8),
	(23,'2 zucchini',8),
	(24,'1 lb. pasta',8);

/*!40000 ALTER TABLE `ingredients` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table recipes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `recipes`;

CREATE TABLE `recipes` (
  `recipe_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date_added` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `category_id` int(11) unsigned NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `diet_id` int(11) unsigned NOT NULL,
  `prep_time` int(11) DEFAULT NULL,
  `description` text,
  `directions` text,
  `image_path` varchar(255) DEFAULT '',
  `favorites` int(11) unsigned NOT NULL,
  PRIMARY KEY (`recipe_id`),
  KEY `category_fk` (`category_id`),
  KEY `diets_fk` (`diet_id`),
  CONSTRAINT `category_fk` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`),
  CONSTRAINT `diets_fk` FOREIGN KEY (`diet_id`) REFERENCES `diets` (`diet_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `recipes` WRITE;
/*!40000 ALTER TABLE `recipes` DISABLE KEYS */;

INSERT INTO `recipes` (`recipe_id`, `date_added`, `name`, `category_id`, `rating`, `diet_id`, `prep_time`, `description`, `directions`, `image_path`, `favorites`)
VALUES
	(1,1370012197,'Lemon Salmon',3,4,3,NULL,'Lemon Salmon with Lima Beans. Quick and Easy!','Slice 1 lemon half into 4 thin rounds and set aside. Grate the zest of the other lemon half and set aside; squeeze some of the juice into a bowl and mix in the yogurt and 1/4 teaspoon paprika.\n\nPreheat the broiler. Heat 1 teaspoon olive oil in a medium saucepan over medium heat. Add the garlic, oregano and red pepper flakes and cook until the garlic is golden, about 2 minutes. Add the lima beans, 1 1/2 cups water and the lemon zest; partially cover the pan, bring to a simmer and cook until the beans are tender, about 20 minutes. Season with salt and pepper. Remove from the heat and stir in the parsley, 1 tablespoon of the yogurt mixture and the remaining 1 teaspoon olive oil.\n\nMeanwhile, mix the remaining 1/2 teaspoon paprika, 1/2 teaspoon salt, and pepper to taste in a small bowl. Sprinkle all over the salmon; arrange on a foil-lined baking sheet and top each fillet with a lemon slice. Broil until just cooked through, 6 to 8 minutes. Serve with the lima beans and top with the yogurt mixture.','51a8ba250cca6.jpg',0),
	(2,1370012535,'Mediterranean Salad',2,5,0,NULL,'Chicken salad. Perfect for lunch.','In a medium skillet, combine the wine, onion, lemon, bay leaf, peppercorns, salt, and enough water to cover the chicken by 1/2-inch and bring to a boil for 2 minutes. Add the chicken, cover, reduce the heat, and simmer until the chicken is no longer pink and just cooked through, about 10 to 15 minutes, depending upon the size of the chicken. Remove from the heat, uncover, and let the chicken cool in the liquid for 30 minutes. Transfer the chicken to a plate, cover, and refrigerate until well chilled, about 2 hours, or refrigerate overnight. Cut the chicken into 1-inch cubes and set aside.','51a8bb77e1757.jpg',1),
	(3,1370013002,'Fettuccine with Red Pepper Sauce',3,5,0,NULL,'Fettuccine with Creamy Red Pepper-Feta Sauce','Heat the oil in a heavy skillet over medium-high heat. Saute onion and garlic until soft, about 10 minutes. Add roasted peppers and saute until heated through. Remove from heat and let cool slightly. Place mixture in the bowl of a food processor with stock and all but 2 tablespoons of the feta. Process until combined and smooth, about 30 seconds. Cook pasta according to package directions. Drain, reserving 1/2 cup pasta water. Toss pasta with sauce, adding pasta water by the tablespoon, if needed. Sauce should cling nicely to pasta. Season with salt and pepper, to taste. Divide among pasta bowls. Sprinkle with parsley and remaining feta cheese.','51a8bd4a32096.jpg',2),
	(4,1370013359,'Quiche',1,4,0,NULL,'Cremini Mushroom, Bacon, and Shallot Crustless Quiche','Preheat oven to 350 degrees F. Cook the bacon in a medium skillet, over medium-high heat until just crisp. Transfer to paper towels to drain. Discard all but 2 tablespoons of the fat in the pan. Add 2 tablespoons butter, the mushrooms and 1/2 teaspoon salt. Cook, stirring over medium heat, until the mushroom juices evaporate, about 7 to 10 minutes. Add the shallots and cook until tender and mushrooms are golden, about 3 minutes more. Add the garlic and parsley. Remove from heat, cool slightly.','51a8beaf72bca.jpg',2),
	(5,1370013762,'German Chocolate Cake',5,4,0,NULL,'Delicious chocolate cake with coconut.','Make the cake: Position a rack in the center of the oven and preheat to 325 degrees F. Butter two 9-inch-round cake pans and line the bottoms with parchment paper. Whisk the flour, baking powder, baking soda and salt together in a medium bowl.','default_recipe_img.png',2),
	(6,1370014030,'Guacamole',6,4,1,NULL,'Roasted corn guacamole. Perfect for a snack.','For the tortilla chips: Preheat the oven to 350 degrees F. Brush the tortillas with the oil on both sides and season one side with salt, pepper and cumin. Place on a baking sheet and bake until lightly golden brown, about 10 minutes. When cool enough to handle, cut into eighths or break into chip-size pieces.','51a8c14e3ef96.jpg',1),
	(7,1370014319,'Quinoa Salad',2,5,0,NULL,'Quinoa salad with tomatoes','In a medium saucepan, add the chicken stock, lemon juice and quinoa. Bring to a boil over medium-high heat. Reduce the heat to a simmer, cover the pan and cook until all the liquid is absorbed, about 12 to 15 minutes.','51a8c26fe25aa.jpg',1),
	(8,1370014584,'Pasta Primavera',3,5,1,NULL,'Pasta with a light sauce and a lot of fresh vegetables.','On a large heavy baking sheet, toss all of the vegetables with the oil, salt, pepper, and dried herbs to coat. Transfer half of the vegetable mixture to another heavy large baking sheet and arrange evenly over the baking sheets. Bake until the carrots are tender and the vegetables begin to brown, stirring after the first 10 minutes, about 20 minutes total.','51a8c37865110.jpg',1);

/*!40000 ALTER TABLE `recipes` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_recipes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_recipes`;

CREATE TABLE `user_recipes` (
  `user_recipe_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `recipe_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`user_recipe_id`),
  KEY `users_fk` (`user_id`),
  KEY `recipes_fk` (`recipe_id`),
  CONSTRAINT `recipes_fk` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`recipe_id`),
  CONSTRAINT `users_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `user_recipes` WRITE;
/*!40000 ALTER TABLE `user_recipes` DISABLE KEYS */;

INSERT INTO `user_recipes` (`user_recipe_id`, `user_id`, `recipe_id`)
VALUES
	(1,1,1),
	(2,1,2),
	(3,1,3),
	(4,1,4),
	(5,2,5),
	(6,2,6),
	(7,2,7),
	(8,2,8);

/*!40000 ALTER TABLE `user_recipes` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `fb_id` int(11) DEFAULT NULL,
  `user_img_path` varchar(255) DEFAULT '',
  `last_login` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `username`, `email`, `password`, `fb_id`, `user_img_path`, `last_login`)
VALUES
	(1,'User','Test','user','user@email.com','1f32aa4c9a1d2ea010adcf2348166a04',NULL,'',1370014762),
	(2,'User','Test','user2','user2@email.com','1f32aa4c9a1d2ea010adcf2348166a04',NULL,'',1370013806);

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
