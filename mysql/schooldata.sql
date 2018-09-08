/*
 Navicat MySQL Data Transfer

 Source Server         : 本地
 Source Server Type    : MySQL
 Source Server Version : 50721
 Source Host           : localhost:3306
 Source Schema         : schooldata

 Target Server Type    : MySQL
 Target Server Version : 50721
 File Encoding         : 65001

 Date: 21/08/2018 16:25:07
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for course
-- ----------------------------
DROP TABLE IF EXISTS `course`;
CREATE TABLE `course`  (
  `cno` char(4) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `cname` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `cpno` char(4) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `ccredit` smallint(255) NOT NULL,
  `num` int(255) NOT NULL,
  `tno` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `studentnum` int(255) NOT NULL,
  `dno` char(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `coursetime` int(3) NOT NULL,
  PRIMARY KEY (`cno`) USING BTREE,
  INDEX `cpno`(`cpno`) USING BTREE,
  INDEX `tno`(`tno`) USING BTREE,
  INDEX `cdno`(`dno`) USING BTREE,
  CONSTRAINT `cdno` FOREIGN KEY (`dno`) REFERENCES `department` (`dno`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `cpno` FOREIGN KEY (`cpno`) REFERENCES `course` (`cno`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tno` FOREIGN KEY (`tno`) REFERENCES `teacher` (`tno`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of course
-- ----------------------------
INSERT INTO `course` VALUES ('1001', '数学分析', NULL, 6, 30, '10000001', 1, '10001', 64);
INSERT INTO `course` VALUES ('1002', '高等代数', NULL, 6, 30, '10000002', 1, '10001', 64);
INSERT INTO `course` VALUES ('1003', '解析几何', NULL, 4, 30, '10000003', 1, '10001', 48);
INSERT INTO `course` VALUES ('1004', '常微分方程', '1001', 4, 30, '10000004', 1, '10001', 48);
INSERT INTO `course` VALUES ('1005', '数理逻辑', NULL, 3, 30, NULL, 0, '10001', 48);
INSERT INTO `course` VALUES ('1006', '抽象代数', '1005', 4, 30, '10000007', 0, '10001', 64);
INSERT INTO `course` VALUES ('1007', '微分几何', '1003', 4, 30, '10000003', 0, '10001', 48);
INSERT INTO `course` VALUES ('1008', '复变函数', '1001', 5, 30, '10000005', 0, '10001', 48);
INSERT INTO `course` VALUES ('1009', '数值分析', '1002', 4, 30, '10000006', 0, '10001', 48);
INSERT INTO `course` VALUES ('1010', '概率论', '1005', 4, 30, NULL, 0, '10001', 64);
INSERT INTO `course` VALUES ('1011', '点集拓扑', '1006', 4, 30, '10000003', 0, '10001', 64);
INSERT INTO `course` VALUES ('1012', '数学物理方程', '1004', 4, 30, '10000003', 0, '10001', 48);
INSERT INTO `course` VALUES ('1013', '泛函分析', '1001', 4, 30, NULL, 0, '10001', 64);
INSERT INTO `course` VALUES ('1014', '运筹学', NULL, 4, 30, '10000007', 6, '10001', 48);
INSERT INTO `course` VALUES ('1015', '偏微分方程数值解', '1004', 3, 30, '10000007', 0, '10001', 48);
INSERT INTO `course` VALUES ('1016', '测度论', '1013', 3, 30, NULL, 0, '10001', 48);
INSERT INTO `course` VALUES ('1017', '随机过程', '1010', 3, 30, NULL, 0, '10001', 48);
INSERT INTO `course` VALUES ('1018', '实变函数', '1001', 4, 30, NULL, 0, '10001', 64);
INSERT INTO `course` VALUES ('2001', '大学物理1', NULL, 3, 60, '20000001', 0, '20002', 64);
INSERT INTO `course` VALUES ('2002', '大学物理2', '2002', 3, 60, '20000002', 0, '20002', 64);
INSERT INTO `course` VALUES ('3001', '体育1', NULL, 2, 36, '30000001', 0, '30004', 24);
INSERT INTO `course` VALUES ('3002', '体育2', '3001', 2, 36, '30000002', 0, '30004', 24);
INSERT INTO `course` VALUES ('3003', '体育3', '3002', 2, 36, '30000001', 0, '30004', 24);
INSERT INTO `course` VALUES ('3004', '体育4', '3003', 2, 36, '30000002', 0, '30004', 24);
INSERT INTO `course` VALUES ('4001', 'C++程序设计', NULL, 4, 60, '40000001', 0, '40003', 64);
INSERT INTO `course` VALUES ('4002', '数据结构', '4002', 4, 60, '40000001', 0, '40003', 64);
INSERT INTO `course` VALUES ('4003', '数据库', '4002', 4, 60, '40000002', 0, '40003', 48);
INSERT INTO `course` VALUES ('4004', '面向对象', '4001', 3, 60, '40000002', 0, '40003', 48);
INSERT INTO `course` VALUES ('4005', '计算机网络', NULL, 4, 40, '40000003', 0, '40003', 48);
INSERT INTO `course` VALUES ('4006', '操作系统', NULL, 3, 40, '40000003', 0, '40003', 64);
INSERT INTO `course` VALUES ('4007', '软件工程', NULL, 3, 40, '40000002', 0, '40003', 48);

-- ----------------------------
-- Table structure for department
-- ----------------------------
DROP TABLE IF EXISTS `department`;
CREATE TABLE `department`  (
  `dno` char(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `dname` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `dmaster` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `dtele` char(11) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`dno`) USING BTREE,
  INDEX `dmaster`(`dmaster`) USING BTREE,
  CONSTRAINT `dmaster` FOREIGN KEY (`dmaster`) REFERENCES `teacher` (`tno`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of department
-- ----------------------------
INSERT INTO `department` VALUES ('10001', '数学系', '10000007', '15912349875');
INSERT INTO `department` VALUES ('20002', '物理系', '20000001', '15874521368');
INSERT INTO `department` VALUES ('30004', '体育系', '30000001', '');
INSERT INTO `department` VALUES ('40003', '计算机系', '40000001', '13456842398');

-- ----------------------------
-- Table structure for judge
-- ----------------------------
DROP TABLE IF EXISTS `judge`;
CREATE TABLE `judge`  (
  `yesornot` tinyint(1) NOT NULL,
  `name` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of judge
-- ----------------------------
INSERT INTO `judge` VALUES (0, 'load');
INSERT INTO `judge` VALUES (0, 'choose');

-- ----------------------------
-- Table structure for manager
-- ----------------------------
DROP TABLE IF EXISTS `manager`;
CREATE TABLE `manager`  (
  `id` int(2) NOT NULL,
  `num` char(12) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `mpassword` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`, `num`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of manager
-- ----------------------------
INSERT INTO `manager` VALUES (1, '101', 'scut');
INSERT INTO `manager` VALUES (2, '102', 'scut');

-- ----------------------------
-- Table structure for profession
-- ----------------------------
DROP TABLE IF EXISTS `profession`;
CREATE TABLE `profession`  (
  `pno` char(6) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pname` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `instructor` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `tele` char(11) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `dno` char(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`pno`) USING BTREE,
  INDEX `pdno`(`dno`) USING BTREE,
  CONSTRAINT `pdno` FOREIGN KEY (`dno`) REFERENCES `department` (`dno`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of profession
-- ----------------------------
INSERT INTO `profession` VALUES ('100001', '数学与应用数学', '郑璐', '14589623587', '10001');
INSERT INTO `profession` VALUES ('100002', '信息系统与信息管理', '刘邦', '17568942369', '10001');
INSERT INTO `profession` VALUES ('100003', '信息与计算科学', '刘秀', '15678426519', '10001');
INSERT INTO `profession` VALUES ('100004', '统计学', '刘备', '15907512387', '10001');
INSERT INTO `profession` VALUES ('200001', '应用物理', '刘婵', '15907513216', '20002');
INSERT INTO `profession` VALUES ('200002', '光电科学', '刘彻', '15907516897', '20002');
INSERT INTO `profession` VALUES ('300001', '体育与艺术', NULL, NULL, '30004');
INSERT INTO `profession` VALUES ('400001', '计算机科学与技术', '黎明', '', '40003');
INSERT INTO `profession` VALUES ('400002', '软件工程', '包拯', '', '40003');

-- ----------------------------
-- Table structure for ptemp
-- ----------------------------
DROP TABLE IF EXISTS `ptemp`;
CREATE TABLE `ptemp`  (
  `pno` char(6) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pname` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `instructor` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `tele` char(12) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `stunum` int(4) NULL DEFAULT NULL,
  `dno` char(5) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`pno`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sc
-- ----------------------------
DROP TABLE IF EXISTS `sc`;
CREATE TABLE `sc`  (
  `sno` char(12) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `cno` char(4) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `grade` smallint(255) NULL DEFAULT NULL,
  PRIMARY KEY (`sno`, `cno`) USING BTREE,
  INDEX `sccno`(`cno`) USING BTREE,
  CONSTRAINT `sccno` FOREIGN KEY (`cno`) REFERENCES `course` (`cno`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `scsno` FOREIGN KEY (`sno`) REFERENCES `student` (`sno`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Redundant STATS_AUTO_RECALC = 0;

-- ----------------------------
-- Records of sc
-- ----------------------------
INSERT INTO `sc` VALUES ('201630450201', '1014', 87);
INSERT INTO `sc` VALUES ('201630450209', '1014', 62);
INSERT INTO `sc` VALUES ('201630450320', '1002', NULL);
INSERT INTO `sc` VALUES ('201630450320', '1003', NULL);
INSERT INTO `sc` VALUES ('201630450320', '1014', 91);
INSERT INTO `sc` VALUES ('201630450322', '1014', 96);
INSERT INTO `sc` VALUES ('201630450323', '1014', 56);
INSERT INTO `sc` VALUES ('201630450326', '1014', 59);
INSERT INTO `sc` VALUES ('201630450331', '1001', NULL);

-- ----------------------------
-- Table structure for stemp
-- ----------------------------
DROP TABLE IF EXISTS `stemp`;
CREATE TABLE `stemp`  (
  `dno` char(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `dname` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `tname` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `dtele` char(11) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `pronum` int(2) NULL DEFAULT NULL,
  `coursenum` int(3) NULL DEFAULT NULL,
  `teanum` int(3) NULL DEFAULT NULL,
  `stunum` int(4) NULL DEFAULT NULL,
  PRIMARY KEY (`dno`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for student
-- ----------------------------
DROP TABLE IF EXISTS `student`;
CREATE TABLE `student`  (
  `sno` char(12) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `sname` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ssex` char(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `spassword` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pno` char(6) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `sbday` date NOT NULL,
  PRIMARY KEY (`sno`) USING BTREE,
  INDEX `spno`(`pno`) USING BTREE,
  CONSTRAINT `spno` FOREIGN KEY (`pno`) REFERENCES `profession` (`pno`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of student
-- ----------------------------
INSERT INTO `student` VALUES ('201630450201', '范冰冰', '女', '1234', '400001', '1998-03-02');
INSERT INTO `student` VALUES ('201630450202', '刘丽', '女', '1234', '400002', '1998-03-02');
INSERT INTO `student` VALUES ('201630450203', '和伊喧', '女', '1234', '200002', '1998-03-02');
INSERT INTO `student` VALUES ('201630450204', '杨跃民', '女', '1234', '200002', '1998-03-02');
INSERT INTO `student` VALUES ('201630450205', '蓬莲云', '女', '1234', '200001', '1998-03-02');
INSERT INTO `student` VALUES ('201630450206', '邓丽君', '女', '1234', '200001', '1998-03-02');
INSERT INTO `student` VALUES ('201630450207', '杨超越', '女', '1234', '100004', '1997-03-02');
INSERT INTO `student` VALUES ('201630450208', '杨颖', '女', '1234', '100004', '1998-03-02');
INSERT INTO `student` VALUES ('201630450209', '央金', '女', '1234', '100003', '1996-03-02');
INSERT INTO `student` VALUES ('201630450210', '于旅', '女', '1234', '100003', '1999-03-02');
INSERT INTO `student` VALUES ('201630450320', '罗一凡', '男', '1234', '100001', '1998-03-02');
INSERT INTO `student` VALUES ('201630450321', '某兵权', '男', '1234', '100001', '1999-03-02');
INSERT INTO `student` VALUES ('201630450322', '临战也', '男', '1234', '100001', '1998-03-02');
INSERT INTO `student` VALUES ('201630450323', '桃红衫', '男', '1234', '100001', '1998-03-02');
INSERT INTO `student` VALUES ('201630450324', '岑解冻', '男', '1234', '100002', '1998-03-02');
INSERT INTO `student` VALUES ('201630450325', '丽江枫', '男', '1234', '100002', '1997-03-02');
INSERT INTO `student` VALUES ('201630450326', '林涛困', '男', '1234', '100001', '1996-03-02');
INSERT INTO `student` VALUES ('201630450327', '聚成一', '男', '1234', '100001', '1998-03-02');
INSERT INTO `student` VALUES ('201630450328', '码率为', '男', '1234', '100002', '1998-03-02');
INSERT INTO `student` VALUES ('201630450329', '李总光', '男', '1234', '100001', '1998-03-02');
INSERT INTO `student` VALUES ('201630450330', '陈家俊', '男', '1234', '100001', '2000-03-02');
INSERT INTO `student` VALUES ('201630450331', '礼为先', '男', '1234', '100001', '1998-03-02');
INSERT INTO `student` VALUES ('201630450333', '林炯找', '男', '1234', '100001', '1997-03-02');
INSERT INTO `student` VALUES ('201630450334', '身世博', '男', '1234', '100001', '1999-03-02');
INSERT INTO `student` VALUES ('201630450335', '黄鱼', '男', '1234', '100001', '1995-03-02');
INSERT INTO `student` VALUES ('201630450336', '刘均', '男', '1234', '400002', '1996-03-02');

-- ----------------------------
-- Table structure for teacher
-- ----------------------------
DROP TABLE IF EXISTS `teacher`;
CREATE TABLE `teacher`  (
  `tno` char(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `tname` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `tsex` char(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `tpassword` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `dno` char(5) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `tbday` date NOT NULL,
  PRIMARY KEY (`tno`) USING BTREE,
  INDEX `tdno`(`dno`) USING BTREE,
  CONSTRAINT `tdno` FOREIGN KEY (`dno`) REFERENCES `department` (`dno`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of teacher
-- ----------------------------
INSERT INTO `teacher` VALUES ('10000001', '张三', '男', '5678', '10001', '1975-08-03');
INSERT INTO `teacher` VALUES ('10000002', '李四', '男', '5678', '10001', '1983-08-03');
INSERT INTO `teacher` VALUES ('10000003', '王五', '男', '5678', '10001', '1990-08-03');
INSERT INTO `teacher` VALUES ('10000004', '罗荣', '男', '5678', '10001', '1962-08-03');
INSERT INTO `teacher` VALUES ('10000005', '刘涛', '女', '5678', '10001', '1988-08-03');
INSERT INTO `teacher` VALUES ('10000006', '蔡菊', '女', '5678', '10001', '1980-08-03');
INSERT INTO `teacher` VALUES ('10000007', '朱方', '男', '5678', '10001', '1967-08-03');
INSERT INTO `teacher` VALUES ('20000001', '李丽', '女', '5678', '20002', '1979-08-03');
INSERT INTO `teacher` VALUES ('20000002', '吴成', '男', '5678', '20002', '1978-08-03');
INSERT INTO `teacher` VALUES ('30000001', '吴江', '男', '5678', '30004', '1977-08-03');
INSERT INTO `teacher` VALUES ('30000002', '刘畅', '女', '5678', '30004', '1989-08-03');
INSERT INTO `teacher` VALUES ('40000001', '吴霞', '女', '5678', '40003', '1974-08-03');
INSERT INTO `teacher` VALUES ('40000002', '单利', '男', '5678', '40003', '1965-08-03');
INSERT INTO `teacher` VALUES ('40000003', '李沁', '女', '5678', '40003', '1986-08-03');

-- ----------------------------
-- Table structure for temp
-- ----------------------------
DROP TABLE IF EXISTS `temp`;
CREATE TABLE `temp`  (
  `cno` char(4) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `cname` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ccredit` smallint(2) NOT NULL,
  `num` int(4) NOT NULL,
  `studentnum` int(3) NOT NULL,
  `gradednum` int(3) NOT NULL,
  `avgrade` float NOT NULL,
  `passnum` int(3) NOT NULL,
  `passpercents` float NOT NULL,
  `nopassnum` int(3) NOT NULL,
  `nopasspercents` float NOT NULL,
  `exstudentnum` int(3) NOT NULL,
  `expercents` float NOT NULL,
  `mannum` int(3) NOT NULL,
  `womannum` int(3) NOT NULL,
  PRIMARY KEY (`cno`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
