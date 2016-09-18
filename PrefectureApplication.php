<?php
/********************************************************/
/* プログラム名：PrefectureApplication.php              */
/*======================================================*/
/* 概要：アプリケーション固有のデータベース接続および   */
/*       ルーティング定義                               */
/*======================================================*/
/* 作成日：2016-9-5                                     */
/* 作成者：鶴田 博文                                    */
/* 最終更新日：                                         */
/* 更新者：                                             */
/********************************************************/

class PrefectureApplication extends ApplicationBase
{
    /********************************************************/
    /* Protected Function                                   */
    /*======================================================*/
    /* 関数名: doDbConnection                               */
    /*======================================================*/
    /* 概要  : データベースへの接続                         */
    /* 引数  : なし                                         */
    /* 戻り値: なし、インスタンス作成                       */
    /********************************************************/
    protected function doDbConnection() {
        $this->_connectModel->connect('master', array(
            'string'   => 'mysql:dbhost=localhost;dbname=mst_prefecture_db;charset=utf8',
            'user'     => 'dbuser',
            'password' => 'password'));
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: getRootDirectory                             */
    /*======================================================*/
    /* 概要  : ルートディレクトリへのパスを返す             */
    /* 引数  : なし                                         */
    /* 戻り値: ルートディレクトリ                           */
    /********************************************************/
    public function getRootDirectory() {
        return dirname(__FILE__);
    }

    /********************************************************/
    /* Protected Function                                   */
    /*======================================================*/
    /* 関数名: getRouteDefinition                           */
    /*======================================================*/
    /* 概要  : ルーティング定義を返す                       */
    /* 引数  : なし                                         */
    /* 戻り値: ルーティング定義(連想配列)                   */
    /********************************************************/
    protected function getRouteDefinition() {
        return array(
            '/'
                => array('controller' => 'prefecture',
                         'action'     => 'index'),
            '/prefecture/search'
                => array('controller' => 'prefecture',
                         'action'     => 'search'),
            '/prefecture/register'
                => array('controller' => 'prefecture',
                         'action'     => 'register'),
            '/prefecture/confirm'
                => array('controller' => 'prefecture',
                        'action'     => 'confirm'),
            '/prefecture/complete'
                => array('controller' => 'prefecture',
                        'action'     => 'complete'),
            '/prefecture/update'
                => array('controller' => 'prefecture',
                         'action'     => 'update'),
            '/prefecture/delete'
                => array('controller' => 'prefecture',
                         'action'     => 'delete'),
            );
    }
}
