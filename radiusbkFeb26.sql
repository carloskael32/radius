/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.15-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: radius
-- ------------------------------------------------------
-- Server version	10.11.15-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES
('admin@gmail.com|127.0.0.1','i:1;',1749300149),
('admin@gmail.com|127.0.0.1:timer','i:1749300149;',1749300149),
('carloskael32@gmail.com|127.0.0.1','i:2;',1748918063),
('carloskael32@gmail.com|127.0.0.1:timer','i:1748918063;',1748918063),
('test@gmail.com|127.0.0.1','i:1;',1749300155),
('test@gmail.com|127.0.0.1:timer','i:1749300155;',1749300155);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `plan` varchar(255) DEFAULT NULL,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clients_username_unique` (`username`),
  KEY `clients_username_index` (`username`),
  KEY `clients_estado_index` (`estado`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES
(1,'gonzalo77','Ing. Oliver Carballo','zvaldivia@example.com','957-091556','Ronda Pablo, 757, 24º D, 67341, Quesada del Penedès',' ','activo','Quod repellendus molestias voluptate illum magni.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(2,'vbanuelos','Srta. Alejandra Hernando','zordonez@example.net','+34 932 85 6612','Rúa Ríos, 525, 0º B, 43103, A Maestas',' ','inactivo','Aut aperiam omnis quaerat qui et et numquam.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(3,'biel10','Ander Mercado','angel.osorio@example.org','+34 901-53-5637','Passeig Amparo, 48, 5º, 85983, As Abad Baja',' ','activo','Ut dolorem itaque excepturi odio et et dolorem ducimus.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(4,'manuel.macias','Dr. Yaiza Lorenzo','raul.zarate@example.com','+34 930 073117','Rúa Peláez, 881, Ático 3º, 15440, Villa Tamez',' ','activo','Consequatur iusto atque suscipit in.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(5,'teresa.pons','Josefa Vázquez','rbarraza@example.org','985-318655','Carrer Contreras, 4, 81º F, 46843, Pabón del Vallès',' ','inactivo','Esse eos dolorem fugit nulla aperiam nulla ratione.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(6,'mascarenas.ander','Dr. Francisca Galindo Segundo','nora49@example.com','+34 910-13-3977','Camiño Zamudio, 76, 59º D, 59340, Esquibel del Barco',' ','inactivo','Iure vitae fuga occaecati ab dignissimos sunt rem reiciendis.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(7,'mar69','Srta. Marina Andreu','aitor.oliva@example.net','+34 943154841','Avenida Alejandra, 2, Bajos, 23887, La Gil',' ','activo','Omnis dolores sapiente impedit qui mollitia et.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(8,'jaleman','Dr. Marta Leiva Segundo','sbarrientos@example.org','+34 930 77 0612','Travesía Carla, 85, Ático 9º, 51281, Quintanilla del Vallès',' ','activo','Et voluptatem sed autem est voluptatibus.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(9,'carmen.regalado','Ing. Ana María Vargas Tercero','ifont@example.net','967-13-0932','Plaza Miriam, 984, Bajos, 57288, Los Velasco',' ','activo','Ducimus illum sint quo fuga aliquam.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(10,'unai15','Paola Concepción','jan88@example.net','+34 998 444929','Camino Zarate, 998, 3º C, 71498, Os González',' ','activo','Et doloremque maxime aut est nihil fugit quibusdam.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(11,'andreu.olga','Jordi Arellano','santiago.espino@example.net','945 175098','Camiño Eva, 4, 37º E, 27502, Medina de Arriba',' ','activo','Iusto mollitia voluptatem nam blanditiis adipisci.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(12,'alba.esquivel','Srta. Malak Tello','encarnacion35@example.net','923-528748','Rúa Encarnación, 914, 12º D, 49609, Lucas Baja',' ','activo','Ut qui vel non nesciunt ratione assumenda ut vitae.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(13,'alejandro50','Úrsula Toro','madera.victoria@example.com','935-683268','Paseo Bernal, 3, 48º C, 39631, As Galván',' ','inactivo','Ut tempore dicta nihil voluptates.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(14,'alex47','Srta. Amparo Ocasio','mmartos@example.org','968 243290','Plaza Lovato, 7, 20º F, 10036, Bueno del Pozo',' ','activo','Iste est delectus eius possimus id quia dolore atque.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(15,'resendez.ana','Verónica Rivas','falejandro@example.org','943 56 4604','Paseo Jimena, 81, Bajo 0º, 11789, As Costa',' ','activo','Nam voluptates voluptatum cupiditate praesentium iusto eum.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(16,'raquel.holguin','Guillem Martí','ubermudez@example.org','+34 957147037','Ruela Noa, 61, 0º D, 48679, Villa Gutiérrez',' ','inactivo','Dolores qui praesentium dolores voluptas quam.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(17,'vsalinas','Julia Santana','reyna.victor@example.com','+34 929355689','Praza Suárez, 154, 9º F, 14064, San Cisneros',' ','activo','Ipsa sapiente sunt non.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(18,'gzapata','Helena Betancourt','batista.elsa@example.com','986-882875','Carrer Samuel, 22, 89º B, 10171, Las Rascón del Barco',' ','inactivo','Soluta veniam provident fuga vel aut ipsa ut sint.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(19,'rosario04','Dario Andrés','eduardo.pina@example.org','934-24-7253','Plaza Fonseca, 901, 6º, 59049, Las Véliz Medio',' ','activo','Nesciunt iure aspernatur natus enim.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(20,'ipaez','Pablo Huerta','mariacarmen.marquez@example.org','+34 925-087402','Passeig Andrés, 439, 54º D, 45412, Vall Feliciano',' ','activo','Assumenda quae pariatur illum ut ratione tenetur.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(21,'lola74','Paola Esteban Hijo','nayara11@example.net','+34 926 524069','Camiño Manuel, 5, 1º, 77835, L\' Rojas',' ','activo','Iure in et nam magni sit aperiam.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(22,'banda.pedro','Rosario Rey','eduardo.martinez@example.com','930 86 2923','Avenida Cervántez, 5, Entre suelo 3º, 95019, A Meléndez del Barco',' ','activo','Incidunt corrupti tempora est voluptate numquam aspernatur facilis aliquid.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(23,'segovia.ainhoa','Natalia Vanegas','antonia.archuleta@example.org','922 34 6791','Plaza Margarita, 3, 1º D, 55925, Velázquez Alta',' ','inactivo','Nihil voluptatem odio illum pariatur.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(24,'jorge18','Paula Requena','calvo.adrian@example.com','952-967366','Camiño Leire, 179, Bajos, 76724, Vall Longoria del Mirador',' ','inactivo','Aliquid ut optio ducimus atque similique.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(25,'valenzuela.guillem','Paola Castañeda','mara00@example.net','+34 921 076204','Camiño Marc, 8, 04º E, 16121, Los Casárez',' ','inactivo','Vitae dolorem nihil itaque.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(26,'haguayo','Nahia Rosado Segundo','lucas.caro@example.net','940308751','Avenida Jaime, 3, 58º E, 23643, La Nieto de San Pedro',' ','inactivo','Vel error ut ullam est veniam facere hic eligendi.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(27,'xuribe','Yaiza Montaño','yaiza.tellez@example.net','+34 942-576555','Praza Biel, 20, 1º F, 56111, As Nava',' ','inactivo','Et omnis qui tempore doloribus et.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(28,'noa43','Manuel Villarreal Segundo','zcosta@example.org','994-51-6058','Travessera Ignacio, 9, 08º B, 64588, Las Arellano',' ','activo','Quia tempora laudantium velit sunt.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(29,'teresa.covarrubias','Dr. Patricia Cerda Tercero','asier99@example.net','912-691334','Plaza Enrique, 891, 14º A, 63137, Os Arribas de Ulla',' ','activo','Repellat labore excepturi molestias natus nemo inventore accusamus.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(30,'franciscojavier.jaramillo','Mario Roig','ignacio.clemente@example.com','909016893','Ruela Quiroz, 16, 78º C, 59837, L\' Valle',' ','inactivo','Cum quibusdam et voluptas asperiores.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(31,'ycabrera','Sra. Zoe Carrasquillo','asier82@example.com','957-49-6775','Ronda Laboy, 90, 4º F, 22252, Vall Santiago de las Torres',' ','inactivo','Ut ut et et repellat nostrum et.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(32,'pgiron','Andrea Niño','juanjose05@example.org','973 24 2699','Camiño Griego, 4, 0º C, 51179, A Sosa de la Sierra',' ','activo','Distinctio aut enim quia voluptas aut adipisci ipsum.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(33,'angel.patino','Natalia Castellanos','silvia.hernandes@example.com','932 97 0620','Plaça Gael, 386, 39º D, 93119, Arevalo del Puerto',' ','inactivo','Aut vitae quia et ut laboriosam.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(34,'miguelangel19','Biel Segovia Tercero','montano.andres@example.com','906-564787','Travesía Sanabria, 711, 63º 3º, 62431, El Becerra',' ','inactivo','Ab quia fugit accusantium est sed consectetur architecto eos.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(35,'bruno23','Srta. Adriana Gámez','patricia16@example.com','+34 948-320018','Travesía Ainara, 2, Ático 4º, 32043, Olivo de San Pedro',' ','inactivo','Repellat et vel cumque.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(36,'fgil','Cristian Contreras','patricia.solorio@example.com','+34 926-42-2018','Camino Andrés, 14, Bajo 4º, 78428, Las Espino Alta',' ','activo','Rerum voluptatum quae doloremque quaerat.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(37,'ramirez.ismael','Manuel Rivas','mara15@example.org','+34 981-681957','Praza Teresa, 22, 8º A, 43272, As Roybal de Arriba',' ','activo','Exercitationem nam accusantium eos temporibus doloribus cumque ex at.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(38,'abril01','Nil Baeza','cbriseno@example.org','+34 997719648','Passeig Negrón, 30, 5º F, 71751, Os Lucas',' ','inactivo','Aut sed facilis adipisci nihil totam.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(39,'nil98','Hugo Segovia','correa.yolanda@example.org','+34 983-602769','Praza Paula, 4, 04º C, 40920, El Olivo',' ','activo','Velit ut quasi rem maiores accusantium ad quia.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(40,'cesar.quintana','Ángela Cano','molina.ainhoa@example.com','+34 936 53 4989','Camino Tamayo, 48, 48º B, 19546, Santamaría de Arriba',' ','activo','Voluptatem non necessitatibus ut doloremque est.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(41,'zpagan','Marc Casárez','uiglesias@example.com','933 466732','Camino José Antonio, 84, 6º D, 05839, Paz Alta',' ','inactivo','Voluptatum officiis itaque aliquam sunt blanditiis quo consequatur.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(42,'ismael52','Jordi Cervantes','ivan.cordova@example.com','+34 904-259538','Avinguda Oliver, 2, 39º E, 79181, Valle Alta',' ','activo','Magni non velit laudantium sint dolore quaerat.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(43,'mireia.sancho','Valentina Mayorga','ignacio04@example.net','921224248','Camiño Miguel, 2, Entre suelo 3º, 92398, Las Meléndez',' ','inactivo','Consequatur odio ab accusamus maiores doloremque et.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(44,'yolanda39','Rosa María Ocasio Tercero','pablo71@example.org','+34 981-995739','Carrer De la Cruz, 9, 27º 9º, 72205, O Plaza del Vallès',' ','activo','Ut consequatur nulla alias et.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(45,'daniel37','Pau Reynoso','aarmijo@example.net','+34 969 302540','Travesía Sara, 13, Bajo 8º, 13511, Huerta Alta',' ','inactivo','Nemo omnis consequatur vitae non provident cupiditate veniam.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(46,'ainara.ibarra','Ing. Mario Delgadillo','esther.calvo@example.net','919 52 2398','Travesía Zavala, 95, Bajo 3º, 29552, El Castro del Bages',' ','activo','Fugiat animi voluptatem magnam sed.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(47,'adrian.valverde','Yeray Lorente','figueroa.jesus@example.com','+34 945518196','Ruela José Antonio, 537, 5º E, 46839, L\' Zamora',' ','activo','Repellendus vel soluta iste beatae et aliquam.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(48,'patino.josemanuel','Sandra Garrido','sotelo.sergio@example.net','+34 959162015','Plaza Viera, 1, Entre suelo 3º, 15385, Alonzo del Barco',' ','inactivo','Ex exercitationem tempore fugiat distinctio.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(49,'dario.valenzuela','Srta. Salma Sola Hijo','zoe.reynoso@example.net','+34 918231292','Carrer Mena, 6, 53º B, 51543, El Calvillo del Puerto',' ','inactivo','Non dignissimos fuga aspernatur recusandae.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(50,'eduardo.miramontes','Lucía Moreno','sisneros.alejandra@example.net','+34 969-63-5269','Plaza Oquendo, 7, 0º A, 57717, Nazario del Bages',' ','inactivo','Cupiditate voluptates et est placeat.','2026-02-21 17:15:33','2026-02-21 17:15:33',NULL),
(51,'acarrero','Lorena Ozuna','cmunguia@example.com','990-110135','Ruela Nicolás, 807, Entre suelo 1º, 31189, L\' Chavarría','premiun 3','activo','Aut veniam debitis similique placeat.','2026-02-21 17:15:45','2026-02-21 17:17:16',NULL),
(52,'sisneros.ona','Ing. Vega Tejeda','iquezada@example.com','900-35-2072','Carrer Luna, 39, Ático 3º, 85688, Os Curiel del Penedès','premiun 3','inactivo','Et excepturi laudantium pariatur itaque.','2026-02-21 17:15:45','2026-02-21 17:17:16',NULL),
(53,'lpuente','Aurora Perea','cfajardo@example.net','+34 903 177873','Avenida Gil, 3, Ático 8º, 81768, Suárez del Mirador','premiun 3','activo','Sapiente sequi et consequuntur consequuntur laudantium distinctio sint.','2026-02-21 17:15:45','2026-02-21 17:17:16',NULL),
(54,'juana.almonte','Miguel Esquibel','yarellano@example.org','992-92-4262','Camiño Montalvo, 69, 2º E, 79693, Sáenz del Vallès','premiun 3','inactivo','In et vel blanditiis libero eius.','2026-02-21 17:15:45','2026-02-21 17:17:16',NULL),
(55,'sandra.delatorre','Izan Sanabria','alvaro97@example.com','+34 981-59-3956','Plaza Yago, 6, 48º B, 26512, Grijalva de Arriba',' ','activo','Aperiam aut quas alias fuga.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(56,'saul.olmos','Josefa Carrero','alicia.cazares@example.com','967 89 7175','Ruela Ángel, 211, 79º D, 75210, El Benito',' ','inactivo','Alias sunt eum delectus quisquam.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(57,'roberto.manzanares','Lic. Marco Menchaca Tercero','uvelez@example.net','947469891','Ronda Acevedo, 7, Bajo 1º, 20858, O Rosas de la Sierra','premiun2','inactivo','Et omnis expedita non fugiat voluptatum et.','2026-02-21 17:15:45','2026-02-21 17:17:57',NULL),
(58,'gsaldana','Adriana Laureano','xdelrio@example.net','975-26-3065','Praza León, 92, 1º F, 45935, Romo Alta','premiun2','inactivo','Rerum consectetur debitis iure ut eius dolor temporibus.','2026-02-21 17:15:45','2026-02-21 17:17:57',NULL),
(59,'pilar45','Luisa Nevárez','dballesteros@example.org','970718731','Avinguda Rodrigo, 675, Bajo 3º, 38693, Juárez del Vallès','premiun2','activo','Placeat et explicabo nesciunt.','2026-02-21 17:15:45','2026-02-21 17:17:57',NULL),
(60,'nadia.manzanares','Cristian Noriega','acuna.nayara@example.net','994-11-7628','Passeig Ocampo, 85, 3º C, 62967, Ocasio del Puerto',' ','activo','Quisquam rem numquam quis vitae fuga deleniti.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(61,'biel.montanez','Adrián Puga','rios.alejandra@example.com','+34 984-166232','Travesía Lorena, 14, 6º C, 56294, Madrid Alta',' ','activo','Odit dolores corporis dolor modi labore expedita amet.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(62,'jorge.carreon','Martín Tirado','olga.salcedo@example.org','+34 937-840082','Avenida Pedro, 72, 87º F, 97522, O Irizarry de Lemos',' ','activo','Illum esse dolorem vel ea.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(63,'hprado','Cristian Montalvo Tercero','font.lara@example.net','+34 904739171','Ruela Biel, 73, 6º D, 82849, El Guzmán',' ','activo','Est odio deleniti hic assumenda officia veniam quo.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(64,'mmondragon','Ing. Roberto Almonte','ismael95@example.org','+34 943 92 3867','Avenida Aponte, 2, 18º A, 86787, Vall Calderón',' ','inactivo','Repellendus ducimus ea asperiores qui ut odit.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(65,'avalos.alba','Carla Ríos','nora.deleon@example.net','+34 916-457139','Ronda Asier, 9, 7º 9º, 63625, O Solorzano',' ','activo','Nihil minus veritatis exercitationem.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(66,'ruben79','Ismael Zúñiga','alejandro.rosa@example.org','987 40 6652','Ronda Verónica, 4, 62º F, 60571, El Rocha',' ','inactivo','Ipsum esse sunt vel eius odit tenetur rerum et.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(67,'palba','Nahia Reyna','chacon.ruben@example.org','+34 913-121971','Ruela Malak, 478, 58º A, 78349, Os Ortega',' ','inactivo','Assumenda dolorem mollitia omnis occaecati aut laboriosam.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(68,'cristian.solorio','D. Eric Zavala','encarnacion.guajardo@example.org','977 66 3084','Travessera Ainhoa, 483, 24º A, 46213, L\' Romero',' ','activo','Dignissimos placeat repellat culpa totam est labore.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(69,'pconde','Malak Benavídez','jose.altamirano@example.net','+34 986698379','Camiño Lucas, 838, Ático 2º, 73735, Villalobos de la Sierra',' ','activo','Vel quia iure quo maiores error ut.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(70,'alexandra.zuniga','Alexandra Moreno','marta00@example.com','969553733','Camino Oliver, 5, Ático 3º, 64061, La Cárdenas',' ','activo','Sequi dolore accusantium excepturi et quod.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(71,'sofia.gil','Ander Sauceda','natalia02@example.org','954 367882','Plaça Ibarra, 1, Entre suelo 4º, 40549, Vall Escalante de las Torres',' ','inactivo','Rerum alias aut dolorem ut rerum quia dolores.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(72,'olivera.luis','José Antonio Pineda','mgurule@example.com','+34 902 87 8015','Travesía Perea, 8, Ático 9º, 10406, Barroso del Penedès',' ','activo','Sapiente aut et eos facilis repellendus assumenda.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(73,'gerard96','Marco Clemente','maria01@example.com','+34 928 110371','Camino Víctor, 347, 4º D, 93264, O Olvera',' ','inactivo','Et recusandae eaque dolores similique dignissimos sunt.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(74,'isabel.nevarez','Pilar Reyes','conde.mireia@example.com','+34 920988716','Avenida Isaac, 96, 8º, 21596, Ponce de Arriba',' ','inactivo','Numquam et neque delectus quasi fugit.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(75,'rafael.aguirre','María Ángeles Marrero Hijo','encarnacion41@example.net','925-01-6512','Plaça Ainhoa, 7, 9º, 66122, San Cordero',' ','activo','Et voluptas maiores maxime inventore.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(76,'soto.adriana','Paola Roybal Hijo','baez.alberto@example.com','946-08-7116','Travesía Valdez, 5, 44º B, 48504, Las Segovia del Bages',' ','activo','Cumque id minima numquam reiciendis.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(77,'mblazquez','Mar Benítez','blanca.fernandez@example.org','906-88-6531','Camino Ana Isabel, 72, 0º F, 92205, L\' Porras',' ','inactivo','Non quos earum necessitatibus qui pariatur est ratione.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(78,'wpina','Álvaro Pichardo','wterrazas@example.org','994 17 1028','Praza Sáenz, 578, Ático 1º, 29868, Villa Jáquez del Mirador',' ','activo','Soluta labore veritatis quibusdam esse et.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(79,'ugarrido','Rodrigo Zamora','fernando.delvalle@example.net','905 195009','Plaça Carbonell, 87, 61º A, 56939, El Godoy',' ','activo','Et sit vero omnis minus repellendus in.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(80,'africa.leyva','Paula Calderón Tercero','sgallardo@example.net','+34 960 61 7452','Avinguda Quezada, 32, 5º D, 43457, El Crespo',' ','inactivo','Quo amet delectus sed quisquam dolor eaque consequatur.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(81,'rosamaria.ponce','Luis Mercado','valeria.venegas@example.com','+34 970-329552','Ronda Alberto, 921, Bajo 0º, 74648, Vall Cabello de Arriba',' ','inactivo','Et veritatis dignissimos error qui sed.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(82,'sergio80','Sr. Bruno Paz Segundo','pnevarez@example.com','+34 930-67-2813','Rúa Lorenzo, 81, Bajos, 73845, La Roig del Penedès',' ','inactivo','Et possimus sunt illo reprehenderit accusamus.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(83,'vega.irene','Pedro Rico Tercero','casillas.eric@example.com','995 08 5793','Plaza Zaragoza, 568, 3º 0º, 33059, Vall Partida',' ','inactivo','Tempora id exercitationem ratione vitae similique.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(84,'briseno.andres','Iván Zarate','carlos12@example.net','984 01 9141','Ruela Biel, 1, 35º D, 84817, Ramos de Ulla',' ','activo','Commodi repudiandae qui ad non.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(85,'mesa.alex','Rayan Contreras Tercero','cesar.arribas@example.net','981-435736','Avenida Esther, 3, 0º D, 76022, L\' Hernández',' ','activo','Delectus consequatur accusantium nihil quas et est.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(86,'emilia.banuelos','Srta. Eva Salcido','jnazario@example.com','+34 952-01-5410','Rúa Andrés, 69, 88º D, 66731, Villa Aponte del Mirador',' ','inactivo','Rerum repudiandae quod eos eum asperiores voluptatem impedit.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(87,'otello','Sr. Héctor Domínguez Tercero','efont@example.net','+34 906 101543','Passeig Ocasio, 3, 4º E, 81646, Villa Hurtado',' ','activo','Consequuntur reprehenderit culpa dolorem architecto aut.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(88,'ivan.rey','Luna Barela','aina89@example.org','+34 975831446','Avinguda María, 63, 1º C, 50596, Olivárez del Barco',' ','inactivo','Eaque dicta saepe in.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(89,'martin.solorzano','Dña Inmaculada Mata','tellez.lara@example.com','+34 987-27-2683','Rúa Arenas, 768, Ático 3º, 50433, Navarro del Puerto',' ','inactivo','Veritatis et omnis facilis et.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(90,'saul.mateo','Sr. Alex Caro','cervantez.bruno@example.org','922-36-9118','Camiño Oliver, 3, 6º, 68128, O Salvador del Barco',' ','inactivo','Odit quisquam qui asperiores id.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(91,'zavala.alonso','Antonia Rodarte','cuesta.aleix@example.net','+34 993-838290','Plaza Sara, 164, 7º 3º, 51323, Villa Farías del Pozo',' ','activo','Aut velit porro debitis deleniti.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(92,'ignacio39','Lic. Olivia Rael','altamirano.rafael@example.net','909 51 3707','Carrer Francisca, 25, 52º B, 49612, Villa Vela',' ','activo','Sed officiis nemo maxime minima et nulla.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(93,'vera.cuellar','Ing. Yago Polo','patricia.font@example.net','918 10 0030','Carrer Gael, 272, 2º A, 34724, Henríquez del Puerto',' ','inactivo','Itaque et placeat ducimus asperiores similique aut.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(94,'ismael57','Gabriel Salgado Hijo','mario.mejia@example.org','+34 959888889','Travesía Eduardo, 65, 62º B, 12721, Vall Matos',' ','inactivo','Eos magni amet dolorum quae officia et et.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(95,'kaguirre','Claudia Murillo Segundo','benavides.alex@example.com','+34 976-302590','Passeig Sedillo, 624, Bajos, 49463, Soria del Penedès',' ','activo','Tempore delectus nihil voluptatem.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(96,'irene.longoria','Abril León','pmatias@example.org','+34 959-43-4747','Calle Camacho, 389, 50º 0º, 48089, As Expósito',' ','activo','Laborum totam error libero dolorem natus.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(97,'xsaiz','Erik Oliver Tercero','nadia31@example.com','956-34-1724','Ruela Rendón, 533, 6º F, 43669, O Miguel',' ','inactivo','Fugiat delectus voluptas id deserunt quibusdam reiciendis sed iusto.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(98,'unai13','Sr. Mario Esquibel','zelaya.cesar@example.net','+34 967-975539','Plaza Marc, 107, 15º A, 70600, Villareal del Bages',' ','activo','Autem at eius nesciunt vero quae rerum sequi.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(99,'alvaro38','Dr. Santiago Quintero Hijo','dalmanza@example.com','992353889','Paseo Valentina, 4, 2º F, 32200, Vall Casado',' ','inactivo','Et enim eos quia adipisci pariatur nisi modi.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(100,'eaparicio','Ing. José Manuel Cano','joseantonio59@example.org','902 339997','Praza Montañez, 9, 97º B, 89301, O Peralta',' ','inactivo','Et voluptas soluta cum corporis ducimus.','2026-02-21 17:15:45','2026-02-21 17:15:45',NULL),
(101,'cmamani','carlos alberto mamani rondo','carlos@gmail.com','77513581','cooperativa','premiun2','inactivo','ninguna','2026-02-21 17:16:39','2026-02-21 17:18:19',NULL);
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2026_01_14_200025_clients',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nas`
--

DROP TABLE IF EXISTS `nas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `nas` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nasname` varchar(128) NOT NULL,
  `shortname` varchar(32) DEFAULT NULL,
  `type` varchar(30) DEFAULT 'other',
  `ports` int(5) DEFAULT NULL,
  `secret` varchar(60) NOT NULL DEFAULT 'secret',
  `server` varchar(64) DEFAULT NULL,
  `community` varchar(50) DEFAULT NULL,
  `description` varchar(200) DEFAULT 'RADIUS Client',
  `host` varchar(50) DEFAULT NULL,
  `user` varchar(50) DEFAULT NULL,
  `pass` text DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nasname` (`nasname`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nas`
--

LOCK TABLES `nas` WRITE;
/*!40000 ALTER TABLE `nas` DISABLE KEYS */;
INSERT INTO `nas` VALUES
(1,'10.2.2.1','mikrotik','mikrotik',NULL,'test123',NULL,NULL,'RADIUS Client',NULL,NULL,NULL,NULL,NULL),
(2,'10.2.2.2','central','mikrotik',12,'12345678',NULL,NULL,'RADIUS Client','10.2.2.2','admin','eyJpdiI6InBNQm03ZHU2cDlvK29MakhrNlJacmc9PSIsInZhbHVlIjoiOHYwbkNlQlhVbmlkenl4aVZWbi8xdz09IiwibWFjIjoiYWMwODUxODY2NTRkOWU4YmJkZWQzMzc5ZDRhZjNiNmU5M2EzYjc1ZDFlZDI1NjVkM2EwODRkZWRhZmRiY2VkNSIsInRhZyI6IiJ9',8622,'active');
/*!40000 ALTER TABLE `nas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nasreload`
--

DROP TABLE IF EXISTS `nasreload`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `nasreload` (
  `nasipaddress` varchar(15) NOT NULL,
  `reloadtime` datetime NOT NULL,
  PRIMARY KEY (`nasipaddress`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nasreload`
--

LOCK TABLES `nasreload` WRITE;
/*!40000 ALTER TABLE `nasreload` DISABLE KEYS */;
/*!40000 ALTER TABLE `nasreload` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `radacct`
--

DROP TABLE IF EXISTS `radacct`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `radacct` (
  `radacctid` bigint(21) NOT NULL AUTO_INCREMENT,
  `acctsessionid` varchar(64) NOT NULL DEFAULT '',
  `acctuniqueid` varchar(32) NOT NULL DEFAULT '',
  `username` varchar(64) NOT NULL DEFAULT '',
  `groupname` varchar(64) NOT NULL DEFAULT '',
  `realm` varchar(64) DEFAULT '',
  `nasipaddress` varchar(15) NOT NULL DEFAULT '',
  `nasportid` varchar(32) DEFAULT NULL,
  `nasporttype` varchar(32) DEFAULT NULL,
  `acctstarttime` datetime DEFAULT NULL,
  `acctupdatetime` datetime DEFAULT NULL,
  `acctstoptime` datetime DEFAULT NULL,
  `acctinterval` int(12) DEFAULT NULL,
  `acctsessiontime` int(12) unsigned DEFAULT NULL,
  `acctauthentic` varchar(32) DEFAULT NULL,
  `connectinfo_start` varchar(50) DEFAULT NULL,
  `connectinfo_stop` varchar(50) DEFAULT NULL,
  `acctinputoctets` bigint(20) DEFAULT NULL,
  `acctoutputoctets` bigint(20) DEFAULT NULL,
  `calledstationid` varchar(50) NOT NULL DEFAULT '',
  `callingstationid` varchar(50) NOT NULL DEFAULT '',
  `acctterminatecause` varchar(32) NOT NULL DEFAULT '',
  `servicetype` varchar(32) DEFAULT NULL,
  `framedprotocol` varchar(32) DEFAULT NULL,
  `framedipaddress` varchar(15) NOT NULL DEFAULT '',
  `framedipv6address` varchar(45) NOT NULL DEFAULT '',
  `framedipv6prefix` varchar(45) NOT NULL DEFAULT '',
  `framedinterfaceid` varchar(44) NOT NULL DEFAULT '',
  `delegatedipv6prefix` varchar(45) NOT NULL DEFAULT '',
  `class` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`radacctid`),
  UNIQUE KEY `acctuniqueid` (`acctuniqueid`),
  KEY `username` (`username`),
  KEY `framedipaddress` (`framedipaddress`),
  KEY `framedipv6address` (`framedipv6address`),
  KEY `framedipv6prefix` (`framedipv6prefix`),
  KEY `framedinterfaceid` (`framedinterfaceid`),
  KEY `delegatedipv6prefix` (`delegatedipv6prefix`),
  KEY `acctsessionid` (`acctsessionid`),
  KEY `acctsessiontime` (`acctsessiontime`),
  KEY `acctstarttime` (`acctstarttime`),
  KEY `acctinterval` (`acctinterval`),
  KEY `acctstoptime` (`acctstoptime`),
  KEY `nasipaddress` (`nasipaddress`),
  KEY `bulk_close` (`acctstoptime`,`nasipaddress`,`acctstarttime`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `radacct`
--

LOCK TABLES `radacct` WRITE;
/*!40000 ALTER TABLE `radacct` DISABLE KEYS */;
INSERT INTO `radacct` VALUES
(1,'81100013','fe133c99cb1e83ced44b720094dff1ba','demo_user','','','10.2.2.1','LAN','Ethernet','2025-03-19 10:41:44','2025-03-19 10:41:44','2025-03-19 10:42:53',NULL,68,'RADIUS','','',6004,853,'PPPoE_Server','08:00:27:76:69:24','User-Request','Framed-User','PPP','10.2.2.15','','','','',NULL),
(2,'81100014','33cb2bddd522b6345cd8d5c033419671','demo_user','','','10.2.2.1','LAN','Ethernet','2025-03-19 10:43:45','2025-03-19 10:43:45','2025-03-19 10:44:54',NULL,68,'RADIUS','','',5745,835,'PPPoE_Server','08:00:27:76:69:24','User-Request','Framed-User','PPP','10.2.2.15','','','','',NULL),
(3,'81100015','182c93c48c37240bf4aa9d4aa0a5ae9e','test','','','10.2.2.1','LAN','Ethernet','2025-03-19 10:45:34','2025-03-19 10:45:34','2025-03-19 10:46:42',NULL,68,'RADIUS','','',7523,913,'PPPoE_Server','08:00:27:76:69:24','User-Request','Framed-User','PPP','10.2.2.253','','','','',NULL),
(4,'81f00007','c7889f17263f5d2ac50ab18f1b22da41','user1','','','10.2.2.1','LAN','Ethernet','2025-04-06 19:08:44','2025-04-06 19:08:44','2025-04-06 19:10:20',NULL,96,'RADIUS','','',510,148,'PPPoe_server','C0:25:67:05:EA:98','Lost-Carrier','Framed-User','PPP','10.2.2.254','','','','',NULL),
(5,'81f00008','ea5b0aa95a2eee28b278bbf6265bbe41','user1','','','10.2.2.1','LAN','Ethernet','2025-04-06 19:10:25','2025-04-06 19:10:25','2025-04-06 19:14:05',NULL,221,'RADIUS','','',304038,567195,'PPPoe_server','C0:25:67:05:EA:98','User-Request','Framed-User','PPP','10.2.2.254','','','','',NULL),
(6,'81f00009','ffd3817648a9fc57dca46a1d35c392ec','user2','','','10.2.2.1','LAN','Ethernet','2025-04-06 19:14:15','2025-04-06 19:14:15','2025-04-06 19:15:25',NULL,70,'RADIUS','','',97944,364240,'PPPoe_server','C0:25:67:05:EA:98','User-Request','Framed-User','PPP','10.2.2.27','','','','',NULL),
(7,'81f0000a','3eb9c5d66b2c79bc930b2fe6e65259b2','demo_user','','','10.2.2.1','LAN','Ethernet','2025-04-06 19:15:39','2025-04-06 19:15:39',NULL,NULL,0,'RADIUS','','',0,0,'PPPoe_server','C0:25:67:05:EA:98','','Framed-User','PPP','10.2.2.15','','','','',NULL),
(8,'81f0000e','a486857ea22b06d4c1b378d60872876b','demo_user','','','10.2.2.1','LAN','Ethernet','2025-04-06 19:24:22','2025-04-06 19:24:22','2025-04-06 19:28:13',NULL,231,'RADIUS','','',205648,224014,'PPPoe_server','C0:25:67:05:EA:98','User-Request','Framed-User','PPP','10.2.2.15','','','','',NULL);
/*!40000 ALTER TABLE `radacct` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `radcheck`
--

DROP TABLE IF EXISTS `radcheck`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `radcheck` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL DEFAULT '',
  `attribute` varchar(64) NOT NULL DEFAULT '',
  `op` char(2) NOT NULL DEFAULT '==',
  `value` varchar(253) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `username` (`username`(32))
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `radcheck`
--

LOCK TABLES `radcheck` WRITE;
/*!40000 ALTER TABLE `radcheck` DISABLE KEYS */;
INSERT INTO `radcheck` VALUES
(1,'demo_user','Cleartext-Password',':=','12345'),
(2,'test','Cleartext-Password',':=','54321'),
(4,'sven','Cleartext-Password',':=','123456'),
(5,'pepe','Cleartext-Password',':=','3124'),
(6,'miguel','Cleartext-Password',':=','3124'),
(7,'acarrero','Cleartext-Password',':=','!k2\'}X5:'),
(8,'sisneros.ona','Cleartext-Password',':=','{~\\/G/vWP+K^\"\\+/'),
(9,'lpuente','Cleartext-Password',':=','w[>/pQeL`'),
(10,'juana.almonte','Cleartext-Password',':=','-K9*\'kgB]h5K}M^\'BovO'),
(11,'sandra.delatorre','Cleartext-Password',':=','qfY|mtI'),
(12,'saul.olmos','Cleartext-Password',':=','_.N|!~??!g|$yzH,~'),
(13,'roberto.manzanares','Cleartext-Password',':=','l1#%V&KTD\\9Z1'),
(14,'gsaldana','Cleartext-Password',':=','mb%n[m[AlHV!Z:W\\MNgI'),
(15,'pilar45','Cleartext-Password',':=','k,Qi1;'),
(16,'nadia.manzanares','Cleartext-Password',':=','l06}*?Mq`}j3'),
(17,'biel.montanez','Cleartext-Password',':=','S~7\\9Pyd)&&Tk='),
(18,'jorge.carreon','Cleartext-Password',':=','puGy!*RL#uE55X[5'),
(19,'hprado','Cleartext-Password',':=','@L3\"5\'y^k\"|A^L'),
(20,'mmondragon','Cleartext-Password',':=','Xa/nl7Ul'),
(21,'avalos.alba','Cleartext-Password',':=','0v0K3OJ~2v/BZ\'W+qz)2'),
(22,'ruben79','Cleartext-Password',':=','|[7,}>9!'),
(23,'palba','Cleartext-Password',':=','Q.EZT4.d5fE'),
(24,'cristian.solorio','Cleartext-Password',':=','186J3i]=L5}qsF'),
(25,'pconde','Cleartext-Password',':=','e6}K&B{r'),
(26,'alexandra.zuniga','Cleartext-Password',':=','}B.yG9kX5'),
(27,'sofia.gil','Cleartext-Password',':=','GRPn1sK!]f%'),
(28,'olivera.luis','Cleartext-Password',':=','fj.gc+I-5J}e#i!2suC$'),
(29,'gerard96','Cleartext-Password',':=','^godKdP2|,nHnw*j$I>'),
(30,'isabel.nevarez','Cleartext-Password',':=','Q*cnG7@v\'$_'),
(31,'rafael.aguirre','Cleartext-Password',':=','<.$`5#@&^'),
(32,'soto.adriana','Cleartext-Password',':=','C`J;K=2ZXPu'),
(33,'mblazquez','Cleartext-Password',':=','\\0}`YJ_)_,'),
(34,'wpina','Cleartext-Password',':=','Wfrv)5&%q'),
(35,'ugarrido','Cleartext-Password',':=','y/Lm?So,2S:@`gX$iU-'),
(36,'africa.leyva','Cleartext-Password',':=','JP*)kXX9&LN@+)0UVpR'),
(37,'rosamaria.ponce','Cleartext-Password',':=','(a0R+>s:~D]]r-is,'),
(38,'sergio80','Cleartext-Password',':=','y\'Jj}3bTC'),
(39,'vega.irene','Cleartext-Password',':=','<;q[G\'t}r90\"&@5{7'),
(40,'briseno.andres','Cleartext-Password',':=','z1prEErfb\'JyoErz-'),
(41,'mesa.alex','Cleartext-Password',':=','];$q}.g%lV_[I,>P|.'),
(42,'emilia.banuelos','Cleartext-Password',':=','RV6i_5Roxg2'),
(43,'otello','Cleartext-Password',':=','4\\GylnqEJ8liNx'),
(44,'ivan.rey','Cleartext-Password',':=','Jn5-mFnzJ-.S5(#/ICEu'),
(45,'martin.solorzano','Cleartext-Password',':=','Zqg0x/nT8IC&'),
(46,'saul.mateo','Cleartext-Password',':=','DbOP#7Lx;}S3:5\"Y'),
(47,'zavala.alonso','Cleartext-Password',':=','r!qK^2[R'),
(48,'ignacio39','Cleartext-Password',':=','.g1C{);[.7~]'),
(49,'vera.cuellar','Cleartext-Password',':=','q*X\"1yR!&I?cy-'),
(50,'ismael57','Cleartext-Password',':=','?o[^8ka^(l@'),
(51,'kaguirre','Cleartext-Password',':=','fuvo~U'),
(52,'irene.longoria','Cleartext-Password',':=','`;x5gj0l'),
(53,'xsaiz','Cleartext-Password',':=','`R\'J#fgr'),
(54,'unai13','Cleartext-Password',':=','|-)B*|+l5);V2hj[jyw'),
(55,'alvaro38','Cleartext-Password',':=',':4AnZVx|g\'<Rl^HLSG^'),
(56,'eaparicio','Cleartext-Password',':=','Fsow1\'%/u[PR'),
(57,'cmamani','Cleartext-Password',':=','123456789');
/*!40000 ALTER TABLE `radcheck` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `radgroupcheck`
--

DROP TABLE IF EXISTS `radgroupcheck`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `radgroupcheck` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `groupname` varchar(64) NOT NULL DEFAULT '',
  `attribute` varchar(64) NOT NULL DEFAULT '',
  `op` char(2) NOT NULL DEFAULT '==',
  `value` varchar(253) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `groupname` (`groupname`(32))
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `radgroupcheck`
--

LOCK TABLES `radgroupcheck` WRITE;
/*!40000 ALTER TABLE `radgroupcheck` DISABLE KEYS */;
INSERT INTO `radgroupcheck` VALUES
(1,'premiun1','Rate-Limit','=','512k/128k'),
(3,'premiun2','Rate-Limit','=','10m/15m'),
(5,'premiun 3','Mikrotik-Rate-Limit','=','20M/10M');
/*!40000 ALTER TABLE `radgroupcheck` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `radgroupreply`
--

DROP TABLE IF EXISTS `radgroupreply`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `radgroupreply` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `groupname` varchar(64) NOT NULL DEFAULT '',
  `attribute` varchar(64) NOT NULL DEFAULT '',
  `op` char(2) NOT NULL DEFAULT '=',
  `value` varchar(253) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `groupname` (`groupname`(32))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `radgroupreply`
--

LOCK TABLES `radgroupreply` WRITE;
/*!40000 ALTER TABLE `radgroupreply` DISABLE KEYS */;
INSERT INTO `radgroupreply` VALUES
(1,'Premium','Mikrotik-Rate-Limit',':=','2M/5M');
/*!40000 ALTER TABLE `radgroupreply` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `radpostauth`
--

DROP TABLE IF EXISTS `radpostauth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `radpostauth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL DEFAULT '',
  `pass` varchar(64) NOT NULL DEFAULT '',
  `reply` varchar(32) NOT NULL DEFAULT '',
  `authdate` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `class` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=182 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `radpostauth`
--

LOCK TABLES `radpostauth` WRITE;
/*!40000 ALTER TABLE `radpostauth` DISABLE KEYS */;
INSERT INTO `radpostauth` VALUES
(1,'demo','12345','Access-Accept','2025-02-04 03:41:13.548264',''),
(2,'test','54321','Access-Accept','2025-02-04 03:41:47.253384',''),
(3,'test','54321','Access-Accept','2025-02-04 03:55:12.282786',''),
(4,'demo','12345','Access-Accept','2025-03-13 18:53:23.579035',''),
(5,'carlos','carlos','Access-Reject','2025-03-13 19:02:36.309925',''),
(6,'test','54321','Access-Accept','2025-03-13 19:03:12.157430',''),
(7,'test','54321','Access-Accept','2025-03-13 19:04:09.339401',''),
(8,'test','54321','Access-Accept','2025-03-13 19:04:12.481963',''),
(9,'test','54321','Access-Accept','2025-03-13 19:04:23.760083',''),
(10,'carlos','carlos','Access-Reject','2025-03-13 20:36:32.740773',''),
(11,'carlos','carlos','Access-Reject','2025-03-13 20:36:34.701302',''),
(12,'carlos','carlos','Access-Reject','2025-03-13 20:37:43.840729',''),
(13,'carlos','carlos','Access-Reject','2025-03-13 20:38:52.959987',''),
(14,'carlos','carlos','Access-Reject','2025-03-13 20:40:02.079629',''),
(15,'carlos','carlos','Access-Reject','2025-03-13 20:41:10.197847',''),
(16,'Demo','12345','Access-Accept','2025-03-13 20:42:14.606539',''),
(17,'Demo','12345','Access-Accept','2025-03-13 20:43:23.725620',''),
(18,'Demo','12345','Access-Accept','2025-03-13 20:44:32.834772',''),
(19,'Demo','12345','Access-Accept','2025-03-13 20:45:41.932773',''),
(20,'Demo','12345','Access-Accept','2025-03-13 20:46:51.064165',''),
(21,'Demo','12345','Access-Accept','2025-03-13 20:48:00.198891',''),
(22,'Demo','0x01908497d48dad00696770cd48e264cc13','Access-Accept','2025-03-13 20:49:11.503681',''),
(23,'Demo','0x0112f55066b03360d77a408db396cd5969','Access-Accept','2025-03-13 20:50:19.931948',''),
(24,'Sven','0x01a5a3f5b83e5dff796f7a42c9eb674061','Access-Reject','2025-03-13 20:50:48.357580',''),
(25,'Sven','0x0182673902a3e85e092539d5a45c3806a7','Access-Reject','2025-03-13 20:51:57.489357',''),
(26,'Sven','','Access-Reject','2025-03-13 20:53:06.671674',''),
(27,'Sven','','Access-Reject','2025-03-13 20:54:14.792403',''),
(28,'Sven','','Access-Reject','2025-03-13 20:55:23.911605',''),
(29,'Sven','','Access-Reject','2025-03-13 20:56:33.032377',''),
(30,'Sven','','Access-Reject','2025-03-13 20:56:44.255987',''),
(31,'Sven','','Access-Reject','2025-03-13 20:57:53.376208',''),
(32,'demo','','Access-Accept','2025-03-13 20:59:39.071750',''),
(33,'demo','','Access-Accept','2025-03-13 21:00:48.197766',''),
(34,'demo','0x018417442c52d2ad86cfc18e90cb04a8f0','Access-Accept','2025-03-13 21:01:29.384971',''),
(35,'demo','','Access-Accept','2025-03-13 21:02:15.634767',''),
(36,'demo','','Access-Accept','2025-03-13 21:02:49.821457',''),
(37,'demo','12345','Access-Accept','2025-03-13 21:03:58.977054',''),
(38,'demo','12345','Access-Accept','2025-03-13 21:05:08.150184',''),
(39,'demo','12345','Access-Accept','2025-03-13 21:06:17.258926',''),
(40,'demo','12345','Access-Accept','2025-03-13 21:07:26.388481',''),
(41,'demo','12345','Access-Accept','2025-03-13 21:08:34.508747',''),
(42,'demo','12345','Access-Reject','2025-03-13 21:09:42.618722',''),
(43,'demo','12345','Access-Reject','2025-03-13 21:10:51.909041',''),
(44,'demo_user','12345','Access-Accept','2025-03-13 21:11:10.253131',''),
(45,'demo_user','12345','Access-Accept','2025-03-13 21:12:19.372784',''),
(46,'demo_user','12345','Access-Accept','2025-03-13 21:13:28.493356',''),
(47,'demo_user','12345','Access-Accept','2025-03-13 21:14:37.613171',''),
(48,'demo_user','12345','Access-Accept','2025-03-13 21:15:45.836876',''),
(49,'demo_user','12345','Access-Accept','2025-03-13 21:16:55.053915',''),
(50,'demo_user','12345','Access-Accept','2025-03-13 21:19:11.804300',''),
(51,'demo_user','12345','Access-Accept','2025-03-13 21:20:20.157705',''),
(52,'demo_user','12345','Access-Accept','2025-03-13 21:21:29.464879',''),
(53,'demo_user','12345','Access-Accept','2025-03-13 21:22:38.585530',''),
(54,'demo_user','12345','Access-Accept','2025-03-13 21:23:47.805112',''),
(55,'demo_user','12345','Access-Accept','2025-03-13 21:24:55.915433',''),
(56,'demo_user','12345','Access-Accept','2025-03-13 21:26:05.034792',''),
(57,'demo_user','12345','Access-Accept','2025-03-13 21:27:14.248926',''),
(58,'demo_user','12345','Access-Accept','2025-03-13 21:28:23.395027',''),
(59,'demo_user','12345','Access-Accept','2025-03-13 21:29:32.575269',''),
(60,'demo_user','12345','Access-Accept','2025-03-13 21:30:41.685244',''),
(61,'demo_user','12345','Access-Accept','2025-03-13 21:31:50.796443',''),
(62,'demo_user','12345','Access-Accept','2025-03-13 21:32:59.903639',''),
(63,'demo_user','12345','Access-Accept','2025-03-13 21:34:08.013613',''),
(64,'demo_user','12345','Access-Accept','2025-03-13 21:35:17.132861',''),
(65,'demo_user','12345','Access-Accept','2025-03-13 21:36:26.332101',''),
(66,'demo_user','','Access-Accept','2025-03-13 21:37:39.886342',''),
(67,'demo_user','','Access-Accept','2025-03-13 21:38:48.014751',''),
(68,'demo_user','','Access-Accept','2025-03-13 21:39:56.143971',''),
(69,'demo_user','','Access-Accept','2025-03-13 21:41:05.318033',''),
(70,'demo_user','','Access-Accept','2025-03-13 21:42:14.484042',''),
(71,'demo_user','','Access-Accept','2025-03-13 21:43:23.622557',''),
(72,'demo_user','','Access-Accept','2025-03-13 21:44:32.782560',''),
(73,'demo_user','','Access-Accept','2025-03-13 21:49:26.769446',''),
(74,'demo_user','','Access-Accept','2025-03-13 21:50:35.894793',''),
(75,'demo_user','','Access-Accept','2025-03-13 21:51:44.184963',''),
(76,'demo_user','','Access-Accept','2025-03-13 21:52:52.314197',''),
(77,'demo_user','','Access-Accept','2025-03-13 21:54:01.433881',''),
(78,'demo_user','','Access-Accept','2025-03-13 21:55:10.596796',''),
(79,'demo_user','','Access-Accept','2025-03-13 21:56:19.733315',''),
(80,'demo_user','','Access-Accept','2025-03-13 21:57:28.863009',''),
(81,'demo_user','','Access-Accept','2025-03-13 21:58:37.993126',''),
(132,'demo_user','','Access-Accept','2025-03-13 22:57:37.919379',''),
(158,'carlos','','Access-Reject','2025-03-14 20:31:23.434032',''),
(159,'test','','Access-Accept','2025-03-14 20:31:51.489889',''),
(160,'test','0x01966f60c43c0ecefaecb4ae1e873bbc6d','Access-Accept','2025-03-14 20:35:50.656491',''),
(161,'test','0x0175d9e7705766c440a8bbf4bd560b5cc4','Access-Accept','2025-03-14 20:36:25.300913',''),
(162,'test','','Access-Accept','2025-03-14 20:39:24.424202',''),
(163,'test','','Access-Accept','2025-03-14 20:39:27.085688',''),
(164,'test','','Access-Accept','2025-03-14 20:40:47.820001',''),
(165,'test_user','','Access-Reject','2025-03-14 20:44:51.292666',''),
(166,'demo_user','','Access-Accept','2025-03-14 20:45:31.534457',''),
(167,'demo_user','','Access-Accept','2025-03-14 20:50:23.532095',''),
(168,'demo_user','','Access-Accept','2025-03-14 20:51:25.609411',''),
(169,'demo_user','','Access-Accept','2025-03-14 20:51:32.672618',''),
(170,'demo_user','','Access-Accept','2025-03-14 21:21:52.506863',''),
(171,'demo_user','0x017e9bd9b61d9b546968b0b29e67cb5be3','Access-Accept','2025-03-14 21:24:55.036296',''),
(172,'demo_user','0x018c2e65ef47cda7d9833ad75685bc23c5','Access-Accept','2025-03-14 21:26:50.327129',''),
(173,'demo_user','','Access-Accept','2025-03-19 18:41:40.416960',''),
(174,'demo_user','0x01d082050d24f646f88eacd843652f4a2b','Access-Accept','2025-03-19 18:43:41.359547',''),
(175,'test','0x018cb079012a05470f22f2941a95823465','Access-Accept','2025-03-19 18:45:29.888181',''),
(176,'user1','','Access-Accept','2025-04-06 23:08:42.051407',''),
(177,'user1','','Access-Accept','2025-04-06 23:10:23.751817',''),
(178,'user2','','Access-Accept','2025-04-06 23:14:13.461091',''),
(179,'demo_user','','Access-Accept','2025-04-06 23:15:37.381831',''),
(180,'demo_user','','Access-Accept','2025-04-06 23:24:20.990587',''),
(181,'cmamani','','Access-Reject','2025-04-06 23:28:38.377167','');
/*!40000 ALTER TABLE `radpostauth` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `radreply`
--

DROP TABLE IF EXISTS `radreply`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `radreply` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL DEFAULT '',
  `attribute` varchar(64) NOT NULL DEFAULT '',
  `op` char(2) NOT NULL DEFAULT '=',
  `value` varchar(253) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `username` (`username`(32))
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `radreply`
--

LOCK TABLES `radreply` WRITE;
/*!40000 ALTER TABLE `radreply` DISABLE KEYS */;
INSERT INTO `radreply` VALUES
(3,'demo_user','Framed-IP-Address',':=','10.2.2.15'),
(4,'demo_user','Mikrotik-Rate-Limit',':=','512k/1M'),
(5,'demo_user','Framed-Protocol',':=','PPP');
/*!40000 ALTER TABLE `radreply` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `radusergroup`
--

DROP TABLE IF EXISTS `radusergroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `radusergroup` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL DEFAULT '',
  `groupname` varchar(64) NOT NULL DEFAULT '',
  `priority` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `username` (`username`(32))
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `radusergroup`
--

LOCK TABLES `radusergroup` WRITE;
/*!40000 ALTER TABLE `radusergroup` DISABLE KEYS */;
INSERT INTO `radusergroup` VALUES
(59,'test','premiun1',1),
(60,'demo_user','premiun1',1),
(61,'sven','premiun1',1),
(62,'cmamani','premiun2',1),
(63,'pepe','premiun 3',1),
(64,'miguel','premiun 3',1),
(65,'acarrero','premiun 3',1),
(66,'sisneros.ona','premiun 3',1),
(67,'lpuente','premiun 3',1),
(68,'juana.almonte','premiun 3',1),
(69,'gsaldana','premiun2',1),
(70,'roberto.manzanares','premiun2',1),
(71,'pilar45','premiun2',1);
/*!40000 ALTER TABLE `radusergroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES
('AIdhNk6XeTbR4BkOJKn9FiZCAXZKaLILYGuJScF7',1,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoialZPNm4xMWZ0d1RxQnNUQktlQ3J5TWtTR2Zic3hlMjh1TE54WENZeCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vcmFkaXVzLmxvY2FsL3JhZGFjY3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=',1771694568),
('FqHf8vASZ08ALXM6RbNaV6OQPwqYgbJ1FfeBeQGr',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUGh1S0o5bElxQWJRRGIzZTdiSUlIVklGellMczFnTVFGSXB6TXlzaiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ydXNlcmcvcHJlbWl1bjEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=',1749958909);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'Test User','test@example.com','2025-06-03 02:33:05','$2y$12$bmqO5AoN/UqGablg.b4HrudPZEHOTCkbnyeOcALkBSwEaUeiqrLje','7L8qGAaI2e5VybVtp4p4vM4k7Bdv1i9eEcYvOsxVyEOcIreA96LhQT4bJ1Ss','2025-06-03 02:33:05','2025-06-03 02:33:05');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'radius'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-21 13:23:55
