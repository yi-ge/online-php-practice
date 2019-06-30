-- 开饭平台用户信息：
-- openid	普通用户的标识，对当前开发者帐号唯一
-- nickname	普通用户昵称
-- sex	普通用户性别，1为男性，2为女性
-- province	普通用户个人资料填写的省份
-- city 普通用户个人资料填写的城市
-- country	国家，如中国为CN
-- headimgurl	用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像），用户没有头像时该项为空
-- privilege	用户特权信息，json数组，如微信沃卡用户为（chinaunicom）
-- unionid	用户统一标识。针对一个微信开放平台帐号下的应用，同一用户的unionid是唯一的。
-- 开放平台用户
CREATE TABLE `userinfo`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `openid` VARCHAR(64) NULL DEFAULT '' COMMENT '微信openid',
    `nickname` VARCHAR(128) NULL COMMENT '用户昵称',
    `realname` VARCHAR(20) NULL COMMENT '真实姓名',
    `sex` TINYINT(1) NULL COMMENT '用户性别，1男性，2女性',
    `language` VARCHAR(16) NULL COMMENT '语言',
    `province` VARCHAR(16) NULL COMMENT '用户个人资料填写的省份',
    `city` VARCHAR(16) NULL COMMENT '普通用户个人资料填写的城市',
    `country` VARCHAR(32) NULL COMMENT '国家，如中国为CN',
    `headimgurl` VARCHAR(256) NULL COMMENT '用户头像',
    `privilege` TEXT NULL COMMENT '用户特权信息，json 数组',
    `unionid` VARCHAR(128) NULL COMMENT '微信开放平台用户唯一标识',
    `admin` TINYINT(1) NULL DEFAULT 0 COMMENT '是否是管理员，1是，0不是',
    `credit` VARCHAR(128) NULL COMMENT '积分',
    `created_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    `updated_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
    PRIMARY KEY(`id`)
);


-- 题目
CREATE TABLE `problemset`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `no` BIGINT UNSIGNED NOT NULL,
    `type` VARCHAR(12) NULL COMMENT '分类',
    `name` VARCHAR(128) NULL COMMENT '题名',
    `isAnswer` VARCHAR(12) NULL COMMENT '是否有解答，1有，其余无',
    `language` VARCHAR(16) NULL COMMENT '编程语言',
    `passPercent` VARCHAR(16) NULL COMMENT '通过率',
    `difficulty` VARCHAR(16) NULL COMMENT '难度',
    `tag` VARCHAR(255) NULL COMMENT '标签',
    `mark` VARCHAR(256) NULL COMMENT '备注',
    `describe` LONGTEXT NULL COMMENT '题目描述',
    `answer` LONGTEXT NULL COMMENT '解答',
    `phpCode` LONGTEXT NULL COMMENT 'PHP代码',
    `javascriptCode` LONGTEXT NULL COMMENT 'Javascript代码',
    `phpUnitCode` LONGTEXT NULL COMMENT 'PHP测试代码',
    `testCase` LONGTEXT NULL COMMENT '测试用例',
    `expectedResult` LONGTEXT NULL COMMENT '预期结果',
    `credit` DECIMAL(8, 2) NULL COMMENT '积分',
    `isCredit` VARCHAR(5) NULL DEFAULT '0' COMMENT '是否积分，1是，0不是',
    `created_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    `updated_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
    PRIMARY KEY(`id`)
);

-- 做题记录
CREATE TABLE `record`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `problemset_id` BIGINT UNSIGNED NOT NULL,
    `type` VARCHAR(12) NULL COMMENT '分类',
    `code` LONGTEXT NULL COMMENT '代码',
    `is_pass` VARCHAR(5) NULL DEFAULT '0' COMMENT '是否通过测试，1是，0不是',
    `created_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    `updated_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
    PRIMARY KEY(`id`)
);

-- -- 在线测试的记录
-- CREATE TABLE `test_record`(
--     `user_id` BIGINT UNSIGNED NOT NULL,
--     `code` LONGTEXT NULL COMMENT '代码',
--     `created_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
--     `updated_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
--     PRIMARY KEY(`user_id`)
-- );

-- 积分变动(流水)
CREATE TABLE `credit_flow`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `problemset_id` BIGINT UNSIGNED NOT NULL,
    `log` VARCHAR(256) NULL COMMENT '事由',
    `income` DECIMAL(8) NOT NULL COMMENT '动账',
    `credit` DECIMAL(8) NOT NULL COMMENT '当时总积分',
    `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    PRIMARY KEY(`id`)
);