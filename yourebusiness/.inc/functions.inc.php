<?php

require_once "globals.inc.php";

/* Note: throughout the tests this is needed. */
function insertCommonData() {
	global $db;

	$mysqli = new mysqli($db["hostname"], $db["username"], $db["password"], $db["database"]);

	$sql = "insert into `province`(`id`,`provinceName`) values (1,'Abra\r'),(2,'Agusan del Norte\r'),(3,'Agusan del Sur\r'),(4,'Aklan\r'),(5,'Albay\r'),(6,'Antique\r'),(7,'Apayao\r'),(8,'Aurora\r'),(9,'Basilan\r'),(10,'Bataan\r'),(11,'Batanes\r'),(12,'Batangas\r'),(13,'Benguet\r'),(14,'Biliran\r'),(15,'Bohol\r'),(16,'Bukidnon\r'),(17,'Bulacan\r'),(18,'Cagayan\r'),(19,'Camarines Norte\r'),(20,'Camarines Sur\r'),(21,'Camiguin\r'),(22,'Capiz\r'),(23,'Catanduanes\r'),(24,'Cavite\r'),(25,'Cebu\r'),(26,'Compostela Valley\r'),(27,'Cotabato\r'),(28,'Davao del Norte\r'),(29,'Davao del Sur\r'),(30,'Davao Occidental\r'),(31,'Davao Oriental\r'),(32,'Dinagat Islands\r'),(33,'Eastern Samar\r'),(34,'Guimaras\r'),(35,'Ifugao\r'),(36,'Ilocos Norte\r'),(37,'Ilocos Sur\r'),(38,'Iloilo\r'),(39,'Isabela\r'),(40,'Kalinga\r'),(41,'La Union\r'),(42,'Laguna\r'),(43,'Lanao del Norte\r'),(44,'Lanao del Sur\r'),(45,'Leyte\r'),(46,'Maguindanao\r'),(47,'Marinduque\r'),(48,'Masbate\r'),(49,'Misamis Occidental\r'),(50,'Misamis Oriental\r'),(51,'Mountain Province\r'),(52,'Negros Occidental\r'),(53,'Negros Oriental\r'),(54,'Northern Samar\r'),(55,'Nueva Ecija\r'),(56,'Nueva Vizcaya\r'),(57,'Occidental Mindoro\r'),(58,'Oriental Mindoro\r'),(59,'Palawan'),(60,'Pampanga\r'),(61,'Pangasinan\r'),(62,'Quezon\r'),(63,'Quirino\r'),(64,'Rizal\r'),(65,'Romblon\r'),(66,'Samar\r'),(67,'Sarangani\r'),(68,'Siquijor\r'),(69,'Sorsogon\r'),(70,'South Cotabato\r'),(71,'Southern Leyte\r'),(72,'Sultan Kudarat\r'),(73,'Sulu\r'),(74,'Surigao del Norte\r'),(75,'Surigao del Sur\r'),(76,'Tarlac\r'),(77,'Tawi-Tawi\r'),(78,'Zambales\r'),(79,'Zamboanga del Norte\r'),(80,'Zamboanga del Sur\r'),(81,'Zamboanga Sibugay\r'),(82,'Metro Manila\r');
			insert into `city`(`id`,`cityName`,`provinceId`) values (1,'Caloocan\r',82),(2,'Las Pinas\r',82),(3,'Makati\r',82),(4,'Malabon\r',82),(5,'Mandaluyong\r',82),(6,'Manila\r',82),(7,'Marikina\r',82),(8,'Muntinlupa\r',82),(9,'Navotas\r',82),(10,'Paranaque\r',82),(11,'Pasay\r',82),(12,'Pasig\r',82),(13,'Quezon City\r',82),(14,'San Juan\r',82),(15,'Taguig\r',82),(16,'Valenzuela\r',82),(17,'Butuan\r',NULL),(18,'Cabadbaran\r',NULL),(19,'Bayugan\r',NULL),(20,'Legazpi\r',NULL),(21,'Ligao\r',NULL),(22,'Tabaco\r',NULL),(23,'Isabela\r',NULL),(24,'Lamitan\r',NULL),(25,'Balanga\r',NULL),(26,'Batangas City\r',NULL),(27,'Lipa\r',NULL),(28,'Tanauan\r',NULL),(29,'Baguio\r',NULL),(30,'Tagbilaran\r',15),(31,'Malaybalay\r',NULL),(32,'Valencia\r',NULL),(33,'Malolos\r',17),(34,'Meycauayan\r',17),(35,'San Jose del Monte\r',17),(36,'Tuguegarao\r',NULL),(37,'Iriga\r',NULL),(38,'Naga\r',NULL),(39,'Roxas\r',NULL),(40,'Bacoor\r',24),(41,'Cavite City\r',24),(42,'Dasmarinas\r',24),(43,'Imus\r',24),(44,'Tagaytay\r',24),(45,'Trece Martires\r',24),(46,'Bogo\r',25),(47,'Carcar\r',25),(48,'Cebu City\r',25),(49,'Danao\r',25),(50,'Lapu-Lapu\r',25),(51,'Mandaue\r',25),(52,'Naga\r',25),(53,'Talisay\r',25),(54,'Toledo\r',25),(55,'Kidapawan\r',NULL),(56,'Panabo\r',NULL),(57,'Samal\r',NULL),(58,'Tagum\r',NULL),(59,'Davao City\r',NULL),(60,'Digos\r',NULL),(61,'Mati\r',NULL),(62,'Borongan\r',33),(63,'Batac\r',NULL),(64,'Laoag\r',NULL),(65,'Candon\r',NULL),(66,'Vigan\r',37),(67,'Iloilo City\r',NULL),(68,'Passi\r',NULL),(69,'Cauayan\r',NULL),(70,'Ilagan\r',NULL),(71,'Santiago\r',NULL),(72,'Tabuk\r',NULL),(73,'San Fernando\r',NULL),(74,'Binan\r',42),(75,'Cabuyao\r',42),(76,'Calamba\r',42),(77,'San Pablo\r',42),(78,'Santa Rosa\r',42),(79,'San Pedro\r',42),(80,'Iligan\r',NULL),(81,'Marawi\r',NULL),(82,'Baybay\r',45),(83,'Ormoc\r',45),(84,'Tacloban\r',45),(85,'Cotabato City\r',NULL),(86,'Masbate City\r',NULL),(87,'Oroquieta\r',NULL),(88,'Ozamiz\r',NULL),(89,'Tangub\r',NULL),(90,'Cagayan de Oro\r',NULL),(91,'El Salvador\r',NULL),(92,'Gingoog\r',NULL),(93,'Bacolod\r',52),(94,'Bago\r',NULL),(95,'Cadiz\r',NULL),(96,'Escalante\r',NULL),(97,'Himamaylan\r',NULL),(98,'Kabankalan\r',NULL),(99,'La Carlota\r',NULL),(100,'Sagay\r',NULL),(101,'San Carlos\r',NULL),(102,'Silay\r',NULL),(103,'Sipalay\r',NULL),(104,'Talisay\r',NULL),(105,'Victorias\r',NULL),(106,'Bais\r',NULL),(107,'Bayawan\r',NULL),(108,'Canlaon\r',NULL),(109,'Dumaguete\r',53),(110,'Guihulngan\r',NULL),(111,'Tanjay\r',NULL),(112,'Cabanatuan\r',NULL),(113,'Gapan\r',NULL),(114,'Munoz\r',NULL),(115,'Palayan\r',NULL),(116,'San Jose\r',NULL),(117,'Calapan\r',NULL),(118,'Puerto Princesa\r',59),(119,'Angeles\r City',60),(120,'Mabalacat\r',60),(121,'San Fernando\r',60),(122,'Alaminos\r',61),(123,'Dagupan\r',NULL),(124,'San Carlos\r',NULL),(125,'Urdaneta\r',NULL),(126,'Lucena\r',NULL),(127,'Tayabas\r',NULL),(128,'Antipolo\r',NULL),(129,'Calbayog\r',66),(130,'Catbalogan\r',66),(131,'Sorsogon City\r',NULL),(132,'General Santos\r',NULL),(133,'Koronadal\r',NULL),(134,'Maasin\r',NULL),(135,'Tacurong\r',NULL),(136,'Surigao City\r',NULL),(137,'Bislig\r',NULL),(138,'Tandag\r',NULL),(139,'Tarlac City\r',NULL),(140,'Olongapo\r',NULL),(141,'Dapitan\r',NULL),(142,'Dipolog\r',NULL),(143,'Pagadian\r',NULL),(144,'Zamboanga City\r',NULL);";
	$result = $mysqli->multi_query($sql);
	if ($result == false) {
		$msg = $mysqli->error;
		$num = $mysqli->errno;
		echo "Database error in " . __FUNCTION__ . " ($num: $msg)";
		return false;
	}

	while($mysqli->more_results())
		if ($mysqli->next_result() == false)
			return false;

	$mysqli->close();	

	return true;
}

function generateRandomString($length = 20) {
	return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}