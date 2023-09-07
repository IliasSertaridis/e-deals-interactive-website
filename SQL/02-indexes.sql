CREATE INDEX user_details
ON user (email,username);

CREATE INDEX item_name
ON item (name);

CREATE INDEX offer_store
ON offer (store_id);

CREATE INDEX offer_expiration
ON offer (expiration_date,price);

CREATE INDEX store_type
ON store (store_type);