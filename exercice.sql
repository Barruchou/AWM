--CECI EST DU SQLITE
--la commande de date n'est pas la même
--right et full join ne fonctionne pas

INSERT INTO "movie" ("title", "synopsis", "duration", "type", "public_restriction", "poster", "release_date")
VALUES ('Film SQL', 'Je suis un synopsis', 120, 'Action', '12',
        'https://images.pexels.com/photos/533923/pexels-photo-533923.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
        '2019-05-02 00:00:00.783');

SELECT title
FROM movie;

SELECT DISTINCT email, nickname
FROM user;

DELETE
FROM "movie"
WHERE "id" = :id;

UPDATE "movie"
SET "title" = 'Film SQL 2'
WHERE "id" = :id;

SELECT *
FROM movie
ORDER BY title ASC;

SELECT *
FROM movie
WHERE release_date BETWEEN '2018-01-01' AND '2019-12-31';

SELECT *
FROM user
WHERE email LIKE '%@gmail.com';

ALTER TABLE user
    ADD pseudonyme VARCHAR(255);

SELECT *
FROM movie
WHERE release_date < DATE('now', '-2 years')
  AND title LIKE 'I%';

SELECT *, SUM(seats) as totalSeats
FROM reservation
GROUP BY user_id
HAVING totalSeats > 2;

SELECT *
FROM session
WHERE room_id = (
    SELECT 1
    FROM room
);

SELECT *
FROM session
LEFT JOIN movie ON session.movie_id = movie.id;

SELECT *
FROM user
RIGHT JOIN reservation ON user.id = reservation.user_id;

SELECT *
FROM room
FULL JOIN session on room.id = session.room_id

