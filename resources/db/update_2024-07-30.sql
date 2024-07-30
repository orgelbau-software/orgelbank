-- Die Spalte re_id fehlt beim Abschlag, sodass man erkennen kann zu welcher Endrechnung ein Abschlag gehört.
ALTER TABLE `rechnung_abschlag` ADD `re_id` INT NULL DEFAULT NULL AFTER `g_id`;