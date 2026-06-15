-- MySQL dump 10.13  Distrib 8.0.46, for Linux (x86_64)
--
-- Host: localhost    Database: secondhand
-- ------------------------------------------------------
-- Server version	8.0.46-0ubuntu0.24.04.2

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
-- Table structure for table `sh_product`
--

DROP TABLE IF EXISTS `sh_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sh_product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '商品标题',
  `description` text COMMENT '商品描述',
  `selling_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '成交价',
  `original_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价',
  `pickup_address` varchar(500) NOT NULL DEFAULT '' COMMENT '自提地址',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1在售 2已完成 3已撤消',
  `create_time` int NOT NULL DEFAULT '0',
  `update_time` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='出售商品表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sh_product`
--

LOCK TABLES `sh_product` WRITE;
/*!40000 ALTER TABLE `sh_product` DISABLE KEYS */;
INSERT INTO `sh_product` VALUES (1,'iPhone 14 Pro 256GB 深空黑','九成新，无拆无修，原装屏幕，所有功能正常。带原装充电器和包装盒。',5200.00,8999.00,'朝阳区望京SOHO T1一楼大厅',1,1781511098,1781511098),(2,'MacBook Pro M1 2020 8GB+256GB','自用办公本，屏幕完美无坏点，电池循环约200次，机身有一处轻微划痕。',5800.00,9999.00,'海淀区中关村创业大街3号楼咖啡厅',1,1781511098,1781511098),(3,'索尼 WH-1000XM5 头戴式降噪耳机','仅用过几次，几乎全新，音质完美，降噪效果出色。因换了AirPods Max故出。',1500.00,2999.00,'浦东新区张江高科技园区门口',1,1781511098,1781511098),(4,'捷安特 ATX 860 山地自行车','骑行距离约500公里，变速精准，刹车灵敏。送车锁和水壶架。',1200.00,2598.00,'深圳市南山区科技园南路地铁站B出口',2,1781511098,1781511098),(5,'PS5 国行光驱版 双手柄','买来玩了几次就吃灰了，包装齐全，手柄几乎没有使用痕迹。',3200.00,4828.00,'广州市天河区体育西路地铁站C口',3,1781511098,1781511098);
/*!40000 ALTER TABLE `sh_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sh_product_image`
--

DROP TABLE IF EXISTS `sh_product_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sh_product_image` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL DEFAULT '0',
  `image_url` varchar(500) NOT NULL DEFAULT '' COMMENT '图片路径',
  `sort` tinyint(1) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='商品图片表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sh_product_image`
--

LOCK TABLES `sh_product_image` WRITE;
/*!40000 ALTER TABLE `sh_product_image` DISABLE KEYS */;
/*!40000 ALTER TABLE `sh_product_image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sh_wanted`
--

DROP TABLE IF EXISTS `sh_wanted`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sh_wanted` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '求购标题',
  `description` text COMMENT '求购描述',
  `image_url` varchar(500) NOT NULL DEFAULT '' COMMENT '求购图片',
  `wanted_address` varchar(500) NOT NULL DEFAULT '' COMMENT '求购地址',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1求购中 2已完成 3已撤消',
  `create_time` int NOT NULL DEFAULT '0',
  `update_time` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='求购信息表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sh_wanted`
--

LOCK TABLES `sh_wanted` WRITE;
/*!40000 ALTER TABLE `sh_wanted` DISABLE KEYS */;
INSERT INTO `sh_wanted` VALUES (1,'求购二手 iPad Air 5 64GB','要求无拆修，屏幕无划痕，电池健康度85%以上，颜色无所谓。','','成都市锦江区春熙路地铁站附近',1,1781511098,1781511098),(2,'求购佳能 EOS R6 Mark II 机身','快门数最好在2万以内，无磕碰掉漆，功能一切正常。','','杭州市西湖区文三路电子市场门口',1,1781511098,1781511098),(3,'求购二手 Nintendo Switch OLED版','带包装盒和所有配件，手柄无漂移，屏幕无划痕。','','武汉市洪山区光谷广场B1出口',1,1781511098,1781511098);
/*!40000 ALTER TABLE `sh_wanted` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-15 16:48:08
