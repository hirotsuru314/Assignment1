<?php
/********************************************************/
/* プログラム名：Router.php                             */
/*======================================================*/
/* 概要：リクエストURLをもとにルーティングする          */
/*======================================================*/
/* 作成日：2016-9-5                                     */
/* 作成者：鶴田 博文                                    */
/* 最終更新日：                                         */
/* 更新者：                                             */
/********************************************************/
class Router
{
    protected $_convertedRoutes;  // ルーティングの情報を保持するプロパティ

    /*コンストラクター*/
    public function __construct($routedef) {
        $this->_convertedRoutes = $this->routeConverter($routedef);
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: routeConverter                               */
    /*======================================================*/
    /* 概要  : ルーティング定義を処理用に変換する           */
    /* 引数  : ルーティング定義                             */
    /* 戻り値: 変換されたルーティング定義                   */
    /********************************************************/
    public function routeConverter($routedef) {
        $converted = array();
        foreach ($routedef as $url => $par) {
            $converts = explode('/', ltrim($url, '/'));
            foreach ($converts as $i => $convert) {
                //先頭が「:」であれが取り除く
                if (0 === strpos($convert, ':')){
                    $bar = substr($convert, 1);
                    $convert = '(?<' . $bar . '>[^/]+)';
                }
                $converts[$i] = $convert;
            }
            $pattern = '/' . implode('/', $converts);
            $converted[$pattern] = $par;
        }
        return $converted;
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: getRouteParams                               */
    /*======================================================*/
    /* 概要  : リクエストがルーティング定義にマッチするか確認*/
    /* 引数  : リクエストされたURLのパス                    */
    /* 戻り値: ルーティング情報                             */
    /********************************************************/
    public function getRouteParams($path) {
        //$pathの先頭に「/」がない場合追加する
        if ('/' !== substr($path, 0, 1)) {
            $path = '/' . $path;
        }
        foreach ($this->_convertedRoutes as $pattern => $par) {
            if (preg_match('#^' . $pattern . '$#', $path, $p_match)) {
                $par = array_merge($par, $p_match);
                return $par;
            }
        }
        return false;
    }
}
