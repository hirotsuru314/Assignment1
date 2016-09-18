<?php
/********************************************************/
/* プログラム名：Session.php                            */
/*======================================================*/
/* 概要：セッションを管理する                           */
/*======================================================*/
/* 作成日：2016-9-5                                     */
/* 作成者：鶴田 博文                                    */
/* 最終更新日：                                         */
/* 更新者：                                             */
/********************************************************/
class Session
{
    protected static $_session_flag = false;
    protected static $_generated_flag = false;

    // コンストラクター
    public function __construct() {
        if (!self::$_session_flag) {
            session_start();
            self::$_session_flag = true;
        }
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: set                                          */
    /*======================================================*/
    /* 概要  : セッション変数の設定                         */
    /* 引数  : キーと値                                     */
    /* 戻り値: なし                                         */
    /********************************************************/
    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: get                                          */
    /*======================================================*/
    /* 概要  : セッション変数の取得                         */
    /* 引数  : キー                                         */
    /* 戻り値: 取得したセッション変数                       */
    /********************************************************/
    public function get($key, $par = null) {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return $par;
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: clear                                        */
    /*======================================================*/
    /* 概要  : セッション変数の初期化                       */
    /* 引数  : なし                                         */
    /* 戻り値: なし                                         */
    /********************************************************/
    public function clear() {
        $_SESSION = array();
    }
}