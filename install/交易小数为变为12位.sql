#btc_btccash

ALTER TABLE `btc_btccash` CHANGE `amount` `amount` DECIMAL( 20, 12 ) NULL DEFAULT NULL ,
CHANGE `fee` `fee` DECIMAL( 16, 12 ) NULL DEFAULT NULL ;

#btc_btcdeal

ALTER TABLE `btc_btcdeal` CHANGE `btccount` `btccount` DECIMAL( 16, 12 ) NULL DEFAULT '0.00000000',
CHANGE `uprice` `uprice` DECIMAL( 16, 12 ) NULL DEFAULT '0.00000000',
CHANGE `tprice` `tprice` DECIMAL( 16, 12 ) NULL DEFAULT '0.000000',
CHANGE `bbkage` `bbkage` DECIMAL( 16, 12 ) NULL DEFAULT '0.00000000',
CHANGE `sbkage` `sbkage` DECIMAL( 16, 12 ) NULL DEFAULT '0.00000000';

#btc_btcrecharge

ALTER TABLE `btc_btcrecharge` CHANGE `amount` `amount` DECIMAL( 20, 12 ) NULL DEFAULT NULL ,
CHANGE `fee` `fee` DECIMAL( 16, 12 ) NULL DEFAULT NULL ;

#btc_btctrans

ALTER TABLE `btc_btctrans` CHANGE `amount` `amount` DECIMAL( 20, 12 ) NULL DEFAULT NULL ,
CHANGE `fee` `fee` DECIMAL( 16, 12 ) NULL DEFAULT NULL ;

#btc_btcdeduct

ALTER TABLE `btc_btcdeduct` CHANGE `fee` `fee` DECIMAL( 16, 12 ) NULL DEFAULT NULL ,
CHANGE `deduct` `deduct` DECIMAL( 16, 12 ) NULL DEFAULT NULL ;

#btc_btccoin

ALTER TABLE `btc_btccoin` CHANGE `c_deposit` `c_deposit` DECIMAL( 20, 12 ) NULL DEFAULT '0.00000000',
CHANGE `c_freeze` `c_freeze` DECIMAL( 20, 12 ) NULL DEFAULT '0.00000000';

#btc_btctline

ALTER TABLE `btc_btctline` CHANGE `R_open` `R_open` DECIMAL( 16, 12 ) NULL DEFAULT '0.00000000',
CHANGE `R_high` `R_high` DECIMAL( 16, 12 ) NULL DEFAULT '0.00000000',
CHANGE `R_low` `R_low` DECIMAL( 16, 12 ) NULL DEFAULT '0.00000000',
CHANGE `R_close` `R_close` DECIMAL( 16, 12 ) NULL DEFAULT '0.00000000',
CHANGE `volume` `volume` DECIMAL( 16, 12 ) NULL DEFAULT '0.00000000';

#btc_btcorder

ALTER TABLE `btc_btcorder` CHANGE `btccount` `btccount` DECIMAL( 16, 12 ) NOT NULL DEFAULT '0.00000000',
CHANGE `uprice` `uprice` DECIMAL( 16, 12 ) NOT NULL DEFAULT '0.00000000',
CHANGE `tprice` `tprice` DECIMAL( 20, 12 ) NOT NULL DEFAULT '0.00000000',
CHANGE `bkage` `bkage` DECIMAL( 12, 12 ) NOT NULL DEFAULT '0.000000';

#btc_btcreward

ALTER TABLE `btc_btcreward` CHANGE `reward` `reward` DECIMAL( 16, 12 ) NULL DEFAULT NULL ;

#btc_btcapply

ALTER TABLE `btc_btcapply` CHANGE `btccount` `btccount` DECIMAL( 16, 12 ) NOT NULL DEFAULT '0.00000000',
CHANGE `uprice` `uprice` DECIMAL( 16, 12 ) NOT NULL DEFAULT '0.00000000',
CHANGE `tprice` `tprice` DECIMAL( 20, 12 ) NOT NULL DEFAULT '0.00000000',
CHANGE `bkage` `bkage` DECIMAL( 12, 12 ) NOT NULL DEFAULT '0.000000';


