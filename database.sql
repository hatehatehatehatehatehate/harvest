-- MySQL dump 10.13  Distrib 8.0.22, for Win64 (x86_64)
--
-- Host: localhost    Database: do_world_better
-- ------------------------------------------------------
-- Server version	8.0.22

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `emoji` char(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_title_uindex` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Ремонт дорог','🚗'),(2,'Уборка мусора','🧹'),(3,'Другое','🔧');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `post_id` int NOT NULL,
  `text` varchar(2000) NOT NULL,
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `comments_posts_id_fk` (`post_id`),
  KEY `comments_users_id_fk` (`user_id`),
  CONSTRAINT `comments_posts_id_fk` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `comments_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (1,6,5,'Переодически на Авито выскакивают обьявления о продажи залогового имущества. Причем продаются квартиры за очень привлекательную цену. Но! Но имеется - ДЕЙСТВУЮЩИЕ ОБРЕМЕНЕНИЯ ПО ОБЪЕКТУ: наличие проживающих граждан, наличие зарегистрированных граждан.','2020-11-20 12:54:31'),(2,7,8,'У меня знакомый взял 51к. И не отдал. Я устал в суд год хожу, а ему ваще похуям. Ещё и сраный вирус, из-за которого 4 раза переносили. Через 10 дней очередное заседание, надеюсь суд не придумает в очередной раз говно какое-нибудь для переноса.','2020-11-20 12:56:43'),(3,8,8,'человека, отказывающегося дать расписку, нужно посылать - сразу, жёстко и далеко.','2020-12-28 13:39:59'),(4,9,7,'Вот тоже один знакомый хотел взять 50к, я попросил расписку. Он оскорбился и сказал, что лучше уж он тогда в банке возьмет. Я сказал, что да, лучше. Всё )','2020-11-20 12:56:43'),(6,11,6,'говорит москва. остальные работают','2020-11-20 13:03:10'),(7,12,6,'Заходит Путин в бар и говорит - \"Всё\" ','2020-11-20 13:03:10'),(10,1,2,'Школьник копается в помойке. Мимо проходящий Путин спрашивает у него:\n- Мальчик, ты в каком классе?\n- В среднем ','2020-12-28 13:31:48'),(11,2,2,'Старый политический анекдот\nМосква. Центр. Стоит мужик в пробке. Вдруг стук в окно. Опускает стекло и спрашивает, что надо.\n- Понимаете, террористы взяли президента Путина в заложники и требуют 10 миллионов долларов выкуп, иначе они обольют его бензином и подожгут. Мы решили пройти по машинам, кто сколько даст.\n- Ну... я - литров 5 могу дать...\n','2020-12-28 13:31:52'),(12,3,2,'А бармен такой','2020-12-28 13:31:54'),(18,4,5,'adsadasdsa\r\nasd\r\nasd\r\nasd\r\nsad\r\nsad\r\nas\r\ndsa','2021-03-18 13:19:34'),(19,17,4,'','2021-03-19 14:07:19'),(20,4,7,'asdasdsad','2021-03-19 15:11:22');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `text` varchar(5000) NOT NULL,
  `author` int NOT NULL,
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_fixed` tinyint(1) DEFAULT NULL,
  `category_id` int NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `posts_users_id_fk` (`author`),
  KEY `posts_categories_id_fk` (`category_id`),
  CONSTRAINT `posts_categories_id_fk` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `posts_users_id_fk` FOREIGN KEY (`author`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,'Маленькие хитрости РенТВ','Не знаю даже, куда писать да и зачем. Но.\nСейчас (20.45Мск) по РенТВ идёт фильм Интерстеллар. Лично мне фильм нравится по многим причинам (уж точно не научным), поэтому я его очень много раз смотрел.\nИ тут прям с самого начала мозг мне сообщает: фильм ускорен! Паузы не те, саспенса нет, всё как-то в кучу.',1,'2020-11-19 11:17:20',1,1,0),(2,'На злобу дня','Заходит как-то Путин в бар и говорит:\r\n- Всем пива за счет заведения!',2,'2020-11-19 11:17:20',1,1,0),(3,'True story','Шел сегодня по улице. Смотрю бабуля на лавке сидит за сердце держится. Я бегом к ней, сбегал в аптеку купил нитроглицерин и отвез в больницу. Пока сидел в приемном покое, заметил в окно, что около одной машины подозрительные типы трутся. Видимо угнать хотят. Я выбежал из больницы. Они от меня бегом в свою машину. Я в свою прыгнул, еду за ними. Позвонил в полицию. В общем вместе с ДПСниками догнали их. Поехали в отделение. Пока сидел в отделении смотрю в окно, там горит дом. Я бегом туда. В окне на третьем этаже ребенок кричит. Я его достал его. Оказалось у него родители подожги дом и убежали. В общем теперь собираем документы на усыновление. Вот такой пост добра. Чего не напиздишь ради плюсов.',4,'2020-11-19 11:17:20',0,3,0),(4,'xss demo','&lt;h1 style=&quot;color:red&quot;&gt;GABE&lt;/h1&gt;&lt;style&gt;body{background:green;}&lt;/style&gt;',3,'2020-11-19 13:03:04',1,3,0),(5,'То неловкое чувство, когда все говорят про какого-то котика...','А ты сидишь с мобильного приложения, и вообще не понимаешь о чем идет речь...',5,'2020-11-20 12:52:51',1,3,0),(6,'Хорошие новости','С таксиста, который спас девушку от насильников, \"Яндекс\" пожизненно снял комиссию.\n\nДмитрий Филин — тот самый водитель, к которому через приложение \"Яндекса\" среди ночи внезапно прилетела просьба о помощи. Парень не растерялся, примчал по адресу, позвонил в полицию. Оказалось, там действительно была девушка, которую силой удерживали в вагончике-бытовке.\n\nКомпания решила отблагодарить сотрудника и пожизненно освободила его от уплаты комиссии — 20 процентов с каждой поездки. Теперь Дмитрий оставляет себе всё, что зарабатывает, и очень рад такому внезапному подарку.',10,'2020-11-20 13:02:55',1,3,0),(7,'Кругом обман','Пошли с ребёнком делать Мантушку.\nЗаходим в кабинет, малышка уже все усекла, ревёт.\nМедсестра:\n- Если прекратишь плакать, получишь шарик.\nРебёнок успокоился, ведь за послушание её вознаградят, взглядом ищет шарики.\nМедсестра начинает медленно колоть, от введённого раствора под кожей раздувается.\nМедсестра шёпотом:\n-А вот и шаааарик появляется.',4,'2020-12-28 13:38:30',1,3,0),(8,'Ты что,хочешь забрать мою квартиру?!?','История произошла с моим братом. Один знакомый попросил у него очень крупную сумму в долг,брат согласился дать в долг,но под залог квартиры. Далее диалог.\n- Ты что,хочешь забрать мою квартиру?\n- А ты хочешь не отдать долг?',8,'2020-12-28 13:38:32',NULL,3,0),(9,'Всё через одно место','Тут в России волна пошла по поводу списания &quot;невозвратных долгов&quot; за коммуналку. Ну да, исковой срок давности по таким делам - три года. Всё, что старше, по закону не взыщешь. Никак.\r\n\r\nИ... как добросовестный плательщик, чувствую себя дураком. Кому-то, кто не платил, долги спишут, а мне, естественно, никаких компенсаций. Нафига я за всё платил?',4,'2020-12-28 15:33:20',NULL,3,0),(26,'asdfsadf','asdfsadfsadfd',17,'2021-04-03 16:01:56',NULL,1,1);
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `login` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `password` char(32) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `is_ban` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_login_uindex` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'stilfer','stilfer','stilfer','stilfer','81dc9bdb52d04dc20036dbd8313ed055',0,1),(2,'Holonder','Holonder','Holonder','Holonder','81dc9bdb52d04dc20036dbd8313ed055',0,1),(3,'LPTM','LPTM','LPTM','LPTM','81dc9bdb52d04dc20036dbd8313ed055',0,1),(4,'forum','forum','forum','forum','cff93ea3a05c70091921c9cffb3ace90',1,0),(5,'Venomnomnom','Venomnomnom','Venomnomnom','Venomnomnom','81dc9bdb52d04dc20036dbd8313ed055',0,0),(6,'HelgaMax','HelgaMax','HelgaMax','HelgaMax','81dc9bdb52d04dc20036dbd8313ed055',0,0),(7,'Remborn','Remborn','Remborn','Remborn','81dc9bdb52d04dc20036dbd8313ed055',0,1),(8,'postak','postak','postak','postak','81dc9bdb52d04dc20036dbd8313ed055',0,0),(9,'cynical.look','cynical.look','cynical.look','cynical.look','81dc9bdb52d04dc20036dbd8313ed055',0,0),(10,'Ash214','Ash214','Ash214','Ash214','81dc9bdb52d04dc20036dbd8313ed055',0,0),(11,'nocknick3','nocknick3','nocknick3','nocknick3','81dc9bdb52d04dc20036dbd8313ed055',0,0),(12,'Alexx313','Alexx313','Alexx313','Alexx313','81dc9bdb52d04dc20036dbd8313ed055',0,0),(13,'Light07','Light07','Light07','Light07','81dc9bdb52d04dc20036dbd8313ed055',0,1),(14,'zibel','zibel','zibel','zibel','81dc9bdb52d04dc20036dbd8313ed055',0,0),(15,'gabe@the.dog','gabe','gabe','gabe','81dc9bdb52d04dc20036dbd8313ed055',0,0),(17,'admin@mail.test','admin','admin','admin','69e0aa3a5ad703a65623d301f6ea8f73',1,0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-04-08 11:59:56
