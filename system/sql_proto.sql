
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