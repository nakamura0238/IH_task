-- ユーザー作成 --
CREATE USER 'IH22_M'@'localhost' IDENTIFIED BY 'N3wtYXVK';
GRANT ALL ON *.* TO 'IH22_M'@'localhost';



SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- データベース: `IH_DB`
--
CREATE DATABASE IF NOT EXISTS `IH_DB` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `IH_DB`;


--
-- テーブルの構造 `follows`
--

CREATE TABLE `follows` (
  `ff_index` int(11) NOT NULL,
  `follow_index` int(11) NOT NULL,
  `follower_index` int(11) NOT NULL,
  `follow_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `random_code` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



--
-- テーブルの構造 `follows_request`
--

CREATE TABLE `follows_request` (
  `request_index` int(11) NOT NULL,
  `follow_index` varchar(128) NOT NULL,
  `follower_index` varchar(128) NOT NULL,
  `permit_flg` tinyint(1) NOT NULL,
  `request_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



--
-- テーブルの構造 `genre_a`
--

CREATE TABLE `genre_a` (
  `genre_a_index` int(11) NOT NULL,
  `genre_a_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



--
-- テーブルの構造 `genre_b`
--

CREATE TABLE `genre_b` (
  `genre_b_index` int(11) NOT NULL,
  `in_genre_a` varchar(64) NOT NULL,
  `genre_b_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



--
-- テーブルの構造 `groups`
--

CREATE TABLE `groups` (
  `group_index` int(11) NOT NULL,
  `group_name` varchar(128) NOT NULL,
  `group_picture` varchar(255) DEFAULT NULL,
  `random_code` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



--
-- テーブルの構造 `groups_invitation`
--

CREATE TABLE `groups_invitation` (
  `invitation_index` int(11) NOT NULL,
  `group_index` int(11) NOT NULL,
  `inv_user_index` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



--
-- テーブルの構造 `group_user`
--

CREATE TABLE `group_user` (
  `gu_index` int(11) NOT NULL,
  `group_index` int(11) NOT NULL,
  `user_index` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



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
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `user_index` int(11) NOT NULL,
  `email` varchar(128) DEFAULT NULL,
  `user_id` varchar(128) NOT NULL,
  `name` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `create_at` date NOT NULL,
  `delete_flg` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



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
  MODIFY `ff_index` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- テーブルのAUTO_INCREMENT `follows_request`
--
ALTER TABLE `follows_request`
  MODIFY `request_index` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `group_index` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- テーブルのAUTO_INCREMENT `groups_invitation`
--
ALTER TABLE `groups_invitation`
  MODIFY `invitation_index` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- テーブルのAUTO_INCREMENT `group_user`
--
ALTER TABLE `group_user`
  MODIFY `gu_index` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- テーブルのAUTO_INCREMENT `likes`
--
ALTER TABLE `likes`
  MODIFY `like_index` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- テーブルのAUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `user_index` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
