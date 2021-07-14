-- ユーザー作成 --
CREATE USER 'IH22_M'@'localhost' IDENTIFIED BY 'N3wtYXVK';
GRANT ALL ON *.* TO 'IH22_M'@'localhost';


-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- ホスト: localhost:3306
-- 生成日時: 2021 年 7 月 13 日 08:31
-- サーバのバージョン： 5.7.30
-- PHP のバージョン: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- データベース: `IH_DB`
--
CREATE DATABASE IF NOT EXISTS `IH_DB` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `IH_DB`;

-- --------------------------------------------------------

--
-- テーブルの構造 `follows`
--

CREATE TABLE `follows` (
  `ff_index` int(11) NOT NULL,
  `follow_index` int(11) NOT NULL,
  `follower_index` int(11) NOT NULL,
  `follow_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- テーブルの構造 `follows_request`
--

CREATE TABLE `follows_request` (
  `request_index` int(11) NOT NULL,
  `follow_user` varchar(128) NOT NULL,
  `follower_user` varchar(128) NOT NULL,
  `permit_flg` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `genre_a`
--

CREATE TABLE `genre_a` (
  `genre_a_index` int(11) NOT NULL,
  `genre_a_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- テーブルの構造 `genre_b`
--

CREATE TABLE `genre_b` (
  `genre_b_index` int(11) NOT NULL,
  `in_genre_a` varchar(64) NOT NULL,
  `genre_b_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `groups`
--

CREATE TABLE `groups` (
  `group_index` int(11) NOT NULL,
  `group_name` varchar(128) NOT NULL,
  `group_picture` varchar(255) DEFAULT NULL,
  `random_code` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `groups_invitation`
--

CREATE TABLE `groups_invitation` (
  `invitation_index` int(11) NOT NULL,
  `group_index` int(11) NOT NULL,
  `inv_user_index` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `group_user`
--

CREATE TABLE `group_user` (
  `gu_index` int(11) NOT NULL,
  `group_index` int(11) NOT NULL,
  `user_index` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `likes`
--

CREATE TABLE `likes` (
  `like_index` int(11) NOT NULL,
  `user_index` int(11) NOT NULL,
  `genre_a` int(11) NOT NULL,
  `genre_b` int(11) NOT NULL,
  `genre_c` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `likes`
--

INSERT INTO `likes` (`like_index`, `user_index`, `genre_a`, `genre_b`, `genre_c`) VALUES
(1, 4, 1, 1, '牛肉'),
(2, 4, 2, 4, 'ボルダリング'),
(3, 4, 4, 7, 'キジトラ'),
(4, 4, 1, 3, 'にんじん'),
(5, 4, 6, 8, 'シン・ゴジラ'),
(6, 4, 1, 1, 'カルビ'),
(7, 5, 1, 16, 'マグロ'),
(8, 5, 1, 16, 'サーモン'),
(9, 4, 1, 16, 'マグロ'),
(10, 4, 1, 16, 'ホタテ'),
(11, 6, 1, 1, '牛肉'),
(12, 6, 2, 4, 'ボルダリング'),
(13, 6, 6, 8, '７つの会議'),
(14, 6, 1, 16, 'イカ');

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `user_index` int(11) NOT NULL,
  `email` varchar(128) NOT NULL,
  `user_id` varchar(128) NOT NULL,
  `name` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `create_at` date NOT NULL,
  `delete_flg` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `follows`
--
ALTER TABLE `follows`
  ADD PRIMARY KEY (`ff_index`);

--
-- テーブルのインデックス `follows_request`
--
ALTER TABLE `follows_request`
  ADD PRIMARY KEY (`request_index`);

--
-- テーブルのインデックス `genre_a`
--
ALTER TABLE `genre_a`
  ADD PRIMARY KEY (`genre_a_index`);

--
-- テーブルのインデックス `genre_b`
--
ALTER TABLE `genre_b`
  ADD PRIMARY KEY (`genre_b_index`);

--
-- テーブルのインデックス `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`group_index`);

--
-- テーブルのインデックス `groups_invitation`
--
ALTER TABLE `groups_invitation`
  ADD PRIMARY KEY (`invitation_index`);

--
-- テーブルのインデックス `group_user`
--
ALTER TABLE `group_user`
  ADD PRIMARY KEY (`gu_index`);

--
-- テーブルのインデックス `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`like_index`);

--
-- テーブルのインデックス `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_index`);

--
-- ダンプしたテーブルのAUTO_INCREMENT
--

--
-- テーブルのAUTO_INCREMENT `follows`
--
ALTER TABLE `follows`
  MODIFY `ff_index` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- テーブルのAUTO_INCREMENT `follows_request`
--
ALTER TABLE `follows_request`
  MODIFY `request_index` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルのAUTO_INCREMENT `genre_a`
--
ALTER TABLE `genre_a`
  MODIFY `genre_a_index` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- テーブルのAUTO_INCREMENT `genre_b`
--
ALTER TABLE `genre_b`
  MODIFY `genre_b_index` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- テーブルのAUTO_INCREMENT `groups`
--
ALTER TABLE `groups`
  MODIFY `group_index` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- テーブルのAUTO_INCREMENT `groups_invitation`
--
ALTER TABLE `groups_invitation`
  MODIFY `invitation_index` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルのAUTO_INCREMENT `group_user`
--
ALTER TABLE `group_user`
  MODIFY `gu_index` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- テーブルのAUTO_INCREMENT `likes`
--
ALTER TABLE `likes`
  MODIFY `like_index` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- テーブルのAUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `user_index` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
