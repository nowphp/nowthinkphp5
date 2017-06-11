/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50703
Source Host           : localhost:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50703
File Encoding         : 65001

Date: 2017-06-10 16:01:35
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for trade_item
-- ----------------------------
DROP TABLE IF EXISTS `trade_item`;
CREATE TABLE `trade_item` (
  `item_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '商品id',
  `title` varchar(255) DEFAULT NULL COMMENT '商品标题',
  `content` text COMMENT '商品详情',
  `price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of trade_item
-- ----------------------------
INSERT INTO `trade_item` VALUES ('1', '小米手机', '性价比最高', '999.00');
INSERT INTO `trade_item` VALUES ('2', 'iPhone7', '操作系统牛', '5999.00');

-- ----------------------------
-- Table structure for trade_order
-- ----------------------------
DROP TABLE IF EXISTS `trade_order`;
CREATE TABLE `trade_order` (
  `order_id` int(10) NOT NULL COMMENT '订单id',
  `order_sn` varchar(32) DEFAULT NULL COMMENT '订单号',
  `user_id` int(10) DEFAULT '0' COMMENT '用户id',
  `item_id` int(10) DEFAULT '0' COMMENT '商品id',
  `status` tinyint(1) DEFAULT '1',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '价格',
  `ctime` int(10) DEFAULT NULL COMMENT '创建时间',
  `mtime` int(10) DEFAULT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of trade_order
-- ----------------------------

-- ----------------------------
-- Table structure for trade_store
-- ----------------------------
DROP TABLE IF EXISTS `trade_store`;
CREATE TABLE `trade_store` (
  `item_id` int(10) NOT NULL COMMENT '商品id',
  `number` int(10) DEFAULT '0' COMMENT '商品数量',
  `freez` int(10) DEFAULT '0' COMMENT '商品库存'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of trade_store
-- ----------------------------
INSERT INTO `trade_store` VALUES ('1', '10', '10');
INSERT INTO `trade_store` VALUES ('2', '20', '20');
