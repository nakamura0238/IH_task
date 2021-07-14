SELECT *
FROM aaaaaa
GROUP BY 


SELECT
    likes.like_index
    , likes.user_index
    , likes.genre_a
    , A.genre_a_name
    , likes.genre_b
    , B.genre_b_name
    , likes.genre_c
    , genre_cf.AB_cf
    , count(likes.genre_c) as ABC_cf
FROM
    likes
    JOIN genre_a as A
        ON likes.genre_a = A.genre_a_index
    JOIN genre_b as B
        ON likes.genre_b = B.genre_b_index
    LEFT JOIN (
        SELECT
            likes.genre_a as A_cf
            , likes.genre_b as B_cf
            , count(likes.genre_b) as AB_cf
        FROM
            likes
            JOIN genre_a as A
                ON likes.genre_a = A.genre_a_index
            JOIN genre_b as B
                ON likes.genre_b = B.genre_b_index
        GROUP BY
            likes.genre_a, likes.genre_b
        ) as genre_cf
        ON likes.genre_a = genre_cf.A_cf AND likes.genre_b = genre_cf.B_cf
GROUP BY
    likes.genre_a, likes.genre_b, likes.genre_c
ORDER BY likes.genre_a, likes.genre_b, likes.genre_c



(
SELECT
    genre_ab.A_cf
    , genre_ab.B_cf
    , count(genre_ab.B_cf)
FROM (
    SELECT DISTINCT
        likes.user_index
        , likes.genre_a as A_cf
        , likes.genre_b as B_cf
    FROM
        likes
        JOIN genre_a as A
            ON likes.genre_a = A.genre_a_index
        JOIN genre_b as B
            ON likes.genre_b = B.genre_b_index
    WHERE
        likes.user_index = 4 OR likes.user_index = 5
    ) as genre_ab
GROUP BY
    genre_ab.A_cf, genre_ab.B_cf
) as genre_ab_cf







SELECT
    *
FROM (
    SELECT
        likes.like_index
        , likes.user_index as user
        , likes.genre_a
        , A.genre_a_name
        , likes.genre_b
        , B.genre_b_name
        , likes.genre_c
        , genre_ab_cf.AB_cf
        , genre_abc_cf.ABC_cf
    FROM
        likes
        JOIN genre_a as A
            ON likes.genre_a = A.genre_a_index
        JOIN genre_b as B
            ON likes.genre_b = B.genre_b_index
        LEFT JOIN (
            -- AB一致抽出 --
            SELECT
                genre_ab.A_cf as A_cf
                , genre_ab.B_cf as B_cf
                , count(genre_ab.B_cf) as AB_cf
            FROM (
                -- ABジャンル抽出 --
                SELECT DISTINCT
                    likes.user_index
                    , likes.genre_a as A_cf
                    , likes.genre_b as B_cf
                FROM
                    likes
                    JOIN genre_a as A
                        ON likes.genre_a = A.genre_a_index
                    JOIN genre_b as B
                        ON likes.genre_b = B.genre_b_index
                WHERE
                    likes.user_index = 4 OR likes.user_index = 5
                ) as genre_ab
            GROUP BY
                genre_ab.A_cf, genre_ab.B_cf
        ) as genre_ab_cf
            ON likes.genre_a = genre_ab_cf.A_cf AND likes.genre_b = genre_ab_cf.B_cf

    -- ここからABC一致 --
        LEFT JOIN (
            -- AB一致抽出 --
            SELECT
                genre_abc.A_cf as A_cf
                , genre_abc.B_cf as B_cf
                , genre_abc.C_cf as C_cf
                , count(genre_abc.C_cf) as ABC_cf
            FROM (
                -- ABジャンル抽出 --
                SELECT DISTINCT
                    likes.user_index
                    , likes.genre_a as A_cf
                    , likes.genre_b as B_cf
                    , likes.genre_c as C_cf
                FROM
                    likes
                    JOIN genre_a as A
                        ON likes.genre_a = A.genre_a_index
                    JOIN genre_b as B
                        ON likes.genre_b = B.genre_b_index
                WHERE
                    likes.user_index = 4 OR likes.user_index = 5
                ) as genre_abc
            GROUP BY
                genre_abc.A_cf, genre_abc.B_cf, genre_abc.C_cf
        ) as genre_abc_cf
            ON likes.genre_a = genre_abc_cf.A_cf AND likes.genre_b = genre_abc_cf.B_cf AND likes.genre_c = genre_abc_cf.C_cf
    -- ここまでABC一致 --

    GROUP BY
        likes.user_index, likes.genre_a, likes.genre_b, likes.genre_c
    ORDER BY
        likes.genre_a, likes.genre_b, likes.genre_c
    ) as genre_all
WHERE
    genre_all.user = 4

