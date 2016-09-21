<?php
/********************************************************/
/* プログラム名：Controller.php                         */
/*======================================================*/
/* 概要：アクションを実行するためのメソッドやプロパティ */
/*       を定義                                         */
/*======================================================*/
/* 作成日：2016-9-5                                     */
/* 作成者：鶴田 博文                                    */
/* 最終更新日：                                         */
/* 更新者：                                             */
/********************************************************/
abstract class Controller
{
    protected $_application; // アプリケーションクラスインスタンスを保持するプロパティ
    protected $_controller;  // コントローラーのクラス名を保持するプロパティ
    protected $_action;     // アクション名を保持するプロパティ
    protected $_request;    // Requestクラスのインスタンスを保持するプロパティ
    protected $_response;   // Responseクラスのインスタンスを保持するプロパティ
    protected $_session;    // Sessionクラスのインスタンスを保持するプロパティ
    protected $_connect_model;  // ConnectModelクラスのインスタンスを保持するプロパティ
    const DEF_PROTOCOL = 'http://';
    const DEF_ACTION = 'Action';


    /*コンストラクター*/
    public function __construct($application){
        $this->_controller     = strtolower(substr(get_class($this), 0, -10));
        $this->_application    = $application;
        $this->_request        = $application->getRequestObject();
        $this->_response       = $application->getResponseObject();
        $this->_session        = $application->getSessionObject();
        $this->_connect_model  = $application->getConnectModelObject();
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: dispatch                                     */
    /*======================================================*/
    /* 概要  : アクションを実行するためのメソッド           */
    /* 引数  : アクション名、ルーティング情報               */
    /* 戻り値: アクションを実行して取得したコンテンツ       */
    /********************************************************/
    public function dispatch($action, $params = array()) {
        $this->_action = $action;
        $action_method = $action . self::DEF_ACTION;
        //メソッドが存在しない場合はエラー画面に遷移
        if (!method_exists($this, $action_method)) {
            $this->httpNotFound();
        }
        $content = $this->$action_method($params);
        return $content;
    }

    /********************************************************/
    /* Protected Function                                   */
    /*======================================================*/
    /* 関数名: httpNotFound                                 */
    /*======================================================*/
    /* 概要  : エラーオブジェクトを生成するメソッド         */
    /* 引数  : なし                                         */
    /* 戻り値: なし                                         */
    /********************************************************/
    protected function httpNotFound() {
        throw new FileNotFoundException('FILE NOT FOUND '
            . $this->_controller . '/' . $this->_action);
    }

    /********************************************************/
    /* Protected Function                                   */
    /*======================================================*/
    /* 関数名: render                                       */
    /*======================================================*/
    /* 概要  : Viewクラスのオブジェクトを生成し、コンテンツ */
    /*         をレンダリングするメソッド                   */
    /* 引数  : テンプレートに渡す変数の連想配列、           */
    /*         Viewファイル名、レイアウトファイル名         */
    /* 戻り値: レンダリングしたViewファイルの内容           */
    /********************************************************/
    protected function render($param = array(), $viewFile = null, $template = null) {
        $info = array(
            'request'  => $this->_request,
            'base_url' => $this->_request->getBaseUrl(),
            'session'  => $this->_session);

        $view = new View($this->_application->getViewDirectory(), $info);
        //$viewFileの指定がない場合は、アクション名をファイル名として設定
        if (is_null($viewFile)) {
            $viewFile = $this->_action;
        }
        if (is_null($template)) {
            $template = 'template';
        }
        $path = $this->_controller . '/' .$viewFile;
        $contents = $view->render($path, $param, $template);
        return $contents;
    }

    /********************************************************/
    /* Protected Function                                   */
    /*======================================================*/
    /* 関数名: redirect                                     */
    /*======================================================*/
    /* 概要  : 指定されたURLにリダイレクトするメソッド      */
    /* 引数  : URL                                          */
    /* 戻り値: なし                                         */
    /********************************************************/
    protected function redirect($url) {
        $host = $this->_request->getHostName();
        $base_url = $this->_request->getBaseUrl();
        $url = self::DEF_PROTOCOL . $host . $base_url . $url;
        $this->_response
            ->setStatusCode(302, 'Found');
        $this->_response
            ->setHeader('Location', $url);
    }

    /********************************************************/
    /* Protected Function                                   */
    /*======================================================*/
    /* 関数名: getToken                                     */
    /*======================================================*/
    /* 概要  : トークンを生成するメソッド                   */
    /* 引数  : キーの一部として使用するデータ               */
    /* 戻り値: 生成したトークン                             */
    /********************************************************/
    protected function getToken($form) {
        $key      = 'token/' . $form;
        $tokens   = $this->_session
                         ->get($key, array());
        //要素の数が10以上あれば先頭の要素を削除する
        if (count($tokens) >= 10) {
            array_shift($tokens);
        }
        //パスワードハッシュをトークンとして返す
        $password = session_id() . $form;
        $token    = password_hash($password,
                                  PASSWORD_DEFAULT);
        $tokens[] = $token;
        $this->_session->set($key, $tokens);
        return $token;
    }

    /********************************************************/
    /* Protected Function                                   */
    /*======================================================*/
    /* 関数名: checkToken                                   */
    /*======================================================*/
    /* 概要  : トークンをチェックするメソッド               */
    /* 引数  : 「コントローラー名/アクション名」、トークン  */
    /* 戻り値: なし                                         */
    /********************************************************/
    protected function checkToken($form, $token) {
        $key    = 'token/' . $form;
        $tokens = $this->_session->get($key, array());
        //呼び出し元のアクションが保持しているトークンと一致するかチェック
        if (false !== ($present = array_search($token, $tokens, true))) {
            unset($tokens[$present]);
            $this->_session
                 ->set($key, $tokens);
            return true;
        }
        return false;
    }
}
