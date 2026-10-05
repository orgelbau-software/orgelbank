DELETE duplicate_session
FROM http_session AS duplicate_session
INNER JOIN http_session AS newer_session
  ON duplicate_session.id = newer_session.id
  AND duplicate_session.id2 < newer_session.id2;

ALTER TABLE `http_session`
  MODIFY `id` varchar(256) CHARACTER SET latin1 NOT NULL,
  ADD UNIQUE KEY `id` (`id`);
