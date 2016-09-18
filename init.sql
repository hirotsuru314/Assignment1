/********************************************************/
/* プログラム名：init.sql                               */
/*======================================================*/
/* 概要：データベース、テーブルの初期設定               */
/*======================================================*/
/* 作成日：2016-9-5                                     */
/* 作成者：鶴田 博文                                    */
/* 最終更新日：                                         */
/* 更新者：                                             */
/********************************************************/

CREATE DATABASE mst_prefecture_db;

grant all on mst_prefecture_db.* to dbuser@localhost identified by 'password';

use mst_prefecture_db

CREATE TABLE `mst_prefecture` (
    prefecture_cd CHAR(2) NOT NULL,
    prefecture_name VARCHAR(20) NOT NULL,
    insert_date DATETIME NOT NULL,
    insert_cd CHAR(5) NOT NULL,
    update_date DATETIME DEFAULT NULL,
    update_cd CHAR(5) DEFAULT NULL,
    delete_date DATETIME DEFAULT NULL,
    delete_flg CHAR(1) NOT NULL DEFAULT '0',
    PRIMARY KEY(prefecture_cd)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
