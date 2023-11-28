
-- Umbau Urlaub in Stunden und nicht in Tagen
ALTER TABLE `urlaub` CHANGE `u_tage` `u_stunden` DOUBLE(10,1) NOT NULL;
ALTER TABLE `urlaub` CHANGE `u_stunden` `u_stunden` DOUBLE(10,2) NOT NULL;
ALTER TABLE `urlaub` ADD PRIMARY KEY(`u_id`);
ALTER TABLE `urlaub` ADD UNIQUE(`u_id`);

ALTER TABLE `benutzer` ADD `be_email` VARCHAR(100) NOT NULL AFTER `be_benutzername`;