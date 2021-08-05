
-- ざっくり --
SELECT * 
FROM (
	SELECT user_index, genre_a_index, genre_a_name, genre_b_index, genre_b_name, genre_c
	FROM likes AS l
	JOIN genre_a AS g_a
		ON l.genre_a = g_a.genre_a_index
	JOIN genre_b AS g_b
		ON l.genre_b = g_b.genre_b_index
	WHERE l.user_index in (5, 6)
)



-- メンバーの好み抽出 大分類 使わない？ --
(
	SELECT user_index, genre_a_index, genre_a_name, genre_b_index, genre_b_name, genre_c
	FROM likes AS l
	JOIN genre_a AS g_a
		ON l.genre_a = g_a.genre_a_index
	JOIN genre_b AS g_b
		ON l.genre_b = g_b.genre_b_index
	WHERE l.user_index in (5, 6) AND genre_a = 1
)

-- メンバーの好み集計 大分類・中分類--
(
	SELECT user_index, genre_a_index, genre_a_name, genre_b_index, genre_b_name, genre_c
	FROM likes AS l
	JOIN genre_a AS g_a
		ON l.genre_a = g_a.genre_a_index
	JOIN genre_b AS g_b
		ON l.genre_b = g_b.genre_b_index
	GROUP BY user_index, genre_a, genre_b
	HAVING l.user_index in (4, 5, 6)
)

-- 好み一致数集計 --
(
	SELECT genre_b_agg.genre_a_name, genre_b_agg.genre_b_name, count(*) AS num
	FROM (
		SELECT user_index, genre_a, genre_a_name, genre_b, genre_b_name, genre_c
		FROM likes AS l
		JOIN genre_a AS g_a
			ON l.genre_a = g_a.genre_a_index
		JOIN genre_b AS g_b
			ON l.genre_b = g_b.genre_b_index
		GROUP BY user_index, genre_a, genre_b
		HAVING l.user_index in (5, 6) AND genre_a = 1
	) AS genre_b_agg
	GROUP BY genre_b_agg.genre_a, genre_b_agg.genre_b
	ORDER BY num DESC
)

-- 小分類集計 --
(
	SELECT user_index, genre_a_index, genre_a_name, genre_b_index, genre_b_name, genre_c, count(*) AS num 
	FROM likes AS l
	JOIN genre_a AS g_a
		ON l.genre_a = g_a.genre_a_index
	JOIN genre_b AS g_b
		ON l.genre_b = g_b.genre_b_index
	GROUP BY genre_a, genre_b, genre_c
	HAVING l.user_index in (4, 5, 6)
)


-- 詳しく --



(SELECT user_index, user_id, name, picture, follow_at
FROM (SELECT follow_index, follower_index, follow_at FROM follows WHERE follow_index = 4) AS f1 
LEFT OUTER JOIN (SELECT follow_index FROM follows WHERE follower_index = 4) AS f2 
	ON f1.follower_index = f2.follow_index
JOIN users AS u
	ON f2.follow_index = u.user_index
WHERE f2.follow_index IS NOT NULL
ORDER BY follow_at DESC)

SELECT * 
FROM `group_user` AS g_u
LEFT OUTER JOIN (SELECT user_index, user_id, name, picture, follow_at
	FROM (SELECT follow_index, follower_index, follow_at FROM follows WHERE follow_index = 4) AS f1 
	LEFT OUTER JOIN (SELECT follow_index FROM follows WHERE follower_index = 4) AS f2 
		ON f1.follower_index = f2.follow_index
	JOIN users AS u
		ON f2.follow_index = u.user_index
	WHERE f2.follow_index IS NOT NULL
	ORDER BY follow_at DESC) AS ff
	ON g_u.user_index = ff.user_index
WHERE `group_index` = 16

SELECT ff.user_index, ff.user_id, ff.name, ff.picture, ff.follow_at, g_u.gu_index, g_i.invitation_index
FROM (SELECT user_index, user_id, name, picture, follow_at
	FROM (SELECT follow_index, follower_index, follow_at FROM follows WHERE follow_index = 4) AS f1 
	LEFT OUTER JOIN (SELECT follow_index FROM follows WHERE follower_index = 4) AS f2 
		ON f1.follower_index = f2.follow_index
	JOIN users AS u
		ON f2.follow_index = u.user_index
	WHERE f2.follow_index IS NOT NULL
	ORDER BY follow_at DESC) AS ff
LEFT OUTER JOIN (SELECT * FROM group_user WHERE group_index = 16) AS g_u
	ON ff.user_index = g_u.user_index
LEFT OUTER JOIN (SELECT * FROM groups_invitation WHERE group_index = 16) AS g_i
	ON ff.user_index = g_i.inv_user_index




SELECT f.ff_index, f.follow_index, u.user_id, u.name, u.picture, f_i.ff_index
        FROM follows AS f
        JOIN users AS u 
			ON f.follow_index = u.user_index
		LEFT OUTER JOIN (SELECT * FROM follows WHERE follow_index = 4) AS f_i
			ON f.follow_index = f_i.follower_index
        WHERE f.follower_index = ?
        ORDER BY f.follow_at DESC
