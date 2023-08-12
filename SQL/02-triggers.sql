DELIMITER $
CREATE TRIGGER price_updater
  AFTER INSERT ON offer
  FOR EACH ROW
  BEGIN
    DECLARE iname VARCHAR(100);
    DECLARE rdate DATE;
    DECLARE avgprice FLOAT(5,2);
    DECLARE finishedFLag INT;
    SELECT AVG(offer.price), offer.registration_date, item.name INTO avgprice, rdate, iname FROM offer INNER JOIN item ON offer.item_id = item.item_id GROUP BY offer.item_id, offer.registration_date HAVING offer.item_id = NEW.item_id AND offer.registration_date = NEW.registration_date;
    INSERT INTO price VALUES (iname, rdate, avgprice) ON DUPLICATE KEY UPDATE item_name=iname, date=rdate, price=avgprice;
  END$
DELIMITER ;

