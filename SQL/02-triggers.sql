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

DELIMITER $
CREATE TRIGGER update_offer
  AFTER INSERT ON review
  FOR EACH ROW
  BEGIN
    IF (SELECT rating FROM review WHERE review_id = NEW.review_id) = 'like' THEN
      UPDATE offer SET number_of_likes = number_of_likes + 1 WHERE offer_id = NEW.offer_id;
    ELSEIF (SELECT rating FROM review WHERE review_id = NEW.review_id) = 'dislike' THEN
      UPDATE offer SET number_of_dislikes = number_of_dislikes + 1 WHERE offer_id = NEW.offer_id;
    END IF;
  END$
DELIMITER ;

DELIMITER $
CREATE TRIGGER update_offer_2
  AFTER UPDATE ON review
  FOR EACH ROW
  BEGIN
    IF (OLD.rating = 'like' && NEW.rating = 'dislike') THEN
      UPDATE offer SET number_of_likes = number_of_likes - 1, number_of_dislikes = number_of_dislikes + 1 WHERE offer_id = NEW.offer_id;
    ELSEIF (OLD.rating = 'dislike' && NEW.rating = 'like') THEN
      UPDATE offer SET number_of_dislikes = number_of_dislikes - 1, number_of_likes = number_of_likes + 1 WHERE offer_id = NEW.offer_id;
    END IF;
  END$
DELIMITER ;