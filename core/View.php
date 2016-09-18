<?php
/********************************************************/
/* プログラム名：View.php                               */
/*======================================================*/
/* 概要：HTMLをレンダリングするための処理               */
/*======================================================*/
/* 作成日：2016-9-5                                     */
/* 作成者：鶴田 博文                                    */
/* 最終更新日：                                         */
/* 更新者：                                             */
/********************************************************/
class View
{
    protected $_baseUrl;  //Viewファイルのディレクトリのパスを保持するプロパティ
    protected $_initialValue;  //Viewファイルへ渡すデータを保持するプロパティ
    protected $_passValues = array();  //ページタイトルを保持するプロパティ

    // コンストラクター
    public function __construct($baseUrl, $initialValue = array()) {
        $this->_baseUrl = $baseUrl;
        $this->_initialValue = $initialValue;
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: render                                       */
    /*======================================================*/
    /* 概要  : Viewファイルを読み込むメソッド               */
    /* 引数  : Viewファイルへのパス、Viewファイルに渡す変数 */
    /*         レイアウトファイル名                         */
    /* 戻り値: HTMLドキュメント                             */
    /********************************************************/
    public function render($filename, $parameters = array(), $template = false) {
        $view = $this->_baseUrl . '/' . $filename . '.php';
        extract(array_merge($this->_initialValue, $parameters));
        ob_start();
        ob_implicit_flush(0);
        require $view;
        $content = ob_get_clean();
        //レイアウトファイル名が格納されていれば、読み込む
        if ($template) {
          $content = $this->render($template, array_merge($this->_passValues, array('_content' => $content)
          ));
        }
      return $content;
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: escape                                       */
    /*======================================================*/
    /* 概要  : HTMLエスケープを行うメソッド                 */
    /* 引数  : 文字列                                       */
    /* 戻り値: HTMLエスケープが実行された文字列             */
    /********************************************************/
    public function escape($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}
