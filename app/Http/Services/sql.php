SELECT * FROM `level_countries` WHERE name LIKE '%ETHIOPIE vers la Tanzanie%';
`west_africa_send_west_africa`, `central_africa_send_central_africa`, `ivoiry_send_west_africa`, `is_mobile_money`, `is_central_africa`, `is_maghreb`, `is_usa`, `is_euro`, `is_west_africa`, `west_africa_send_ivory`, `west_africa_send_centre_africa`, `centre_africa_send_west_africa`
Côte d'Ivoire
SELECT *
FROM level_countries
WHERE id BETWEEN 5666 AND 5686;

<!-- UPdate replace -->
UPDATE `level_countries`
SET name = REPLACE(REPLACE(name, ' la ', ' '), ' le ', ' ')
WHERE id BETWEEN 5666 AND 5686;

SELECT * FROM `level_countries` WHERE id BETWEEN 6546 and 6554


DELETE FROM level_countries
WHERE id BETWEEN 5666 AND 5686;


UPDATE level_countries
SET 
    is_central_africa = 0,
    is_maghreb = 0,
    is_usa = 0,
    is_euro = 0,
    is_west_africa = 0,
    is_asie = 0,
    is_eastafrica = 0,
    is_civ = 1
WHERE name LIKE '%Côte d\'ivoire vers %';

UPDATE level_countries
SET 
    is_central_africa = 0,
    is_maghreb = 1,
    is_usa = 0,
    is_euro = 0,
    is_west_africa = 0,
    is_asie = 0,
    is_eastafrica = 0,
    is_civ = 0
WHERE name LIKE '%Tunisie vers %';

SELECT * FROM `level_countries` WHERE name LIKE '%Côte d\'Ivoire vers%';

