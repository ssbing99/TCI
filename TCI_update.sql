-- ## NEW

alter table orders
add column is_skype int(11) NULL default 0 after amount;
 
