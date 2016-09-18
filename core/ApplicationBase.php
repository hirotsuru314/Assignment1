<?php
/********************************************************/
/* プログラム名：ApplicationBase.php                    */
/*======================================================*/
/* 概要：すべてのアプリケーションに共通のメソッドを定義 */
/*======================================================*/
/* 作成日：2016-9-5                                     */
/* 作成者：鶴田 博文                                    */
/* 最終更新日：                                         */
/* 更新者：                                             */
/********************************************************/

abstract class ApplicationBase
{
    protected $_request;  // Requestクラスのインスタンスを保持するプロパティ
    protected $_response;  // Responseクラスのインスタンスを保持するプロパティ
    protected $_session;  // Sessionクラスのインスタンスを保持するプロパティ
    protected $_connectModel;  // ConnectModelクラスのインスタンスを保持するプロパティ
    protected $_router;  // Routerクラスのインスタンスを保持するプロパティ
    protected $_displayErrors;  // エラー表示のオン／オフを保持するプロパティ

    const DIF_CONTROLLER = 'Controller';  // コントローラークラス名のベース部分
    const DIF_DIR_VIEW = '/views';  // viewsフォルダーのディレクトリ
    const DIF_DIR_MODELS = '/models';  // modelsフォルダーのディレクトリ
    const DIF_DIR_WEB = '/htdocs';  // ドキュメントルートのディレクトリ
    const DIF_DIR_CONTROLLERS = '/controllers';  // controllersフォルダーのディレクトリ

    public function __construct($dspErr) {
        $this->setDisplayErrors($dspErr);
        $this->initialize();
        $this->doDbConnection();
    }

    /********************************************************/
    /* Protected Function                                   */
    /*======================================================*/
    /* 関数名: initialize                                   */
    /*======================================================*/
    /* 概要  : インスタンス作成                             */
    /* 引数  : なし                                         */
    /* 戻り値: なし、インスタンス作成                       */
    /********************************************************/
    protected function initialize(){
      $this->_router       = new Router($this->getRouteDefinition());
      $this->_connectModel = new ConnectModel();
      $this->_request      = new Request();
      $this->_response     = new Response();
      $this->_session      = new Session();
    }

    /********************************************************/
    /* Protected Function                                   */
    /*======================================================*/
    /* 関数名: setDisplayErrors                             */
    /*======================================================*/
    /* 概要  : データベースへの接続                         */
    /* 引数  : $dspErr(true or false)                       */
    /* 戻り値: なし、エラー表示設定                         */
    /********************************************************/
    protected function setDisplayErrors($dspErr){
      if ($dspErr) {
        $this->_displayErrors = true;
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ALL);
      } else {
        $this->_displayErrors = false;
        ini_set('display_errors', 0);
      }
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: isDisplayErrors                              */
    /*======================================================*/
    /* 概要  : エラー表示の確認                             */
    /* 引数  : なし                                         */
    /* 戻り値: Boolean                                      */
    /********************************************************/
    public function isDisplayErrors(){
      return $this->_displayErrors;
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: run                                          */
    /*======================================================*/
    /* 概要  : リクエストに応答し、レスポンスを返す         */
    /* 引数  : なし                                         */
    /* 戻り値: なし                                         */
    /********************************************************/
    public function run() {
        try {
            $parameters = $this->_router->getRouteParams($this->_request->getPath());

            if ($parameters === false) {
              throw new FileNotFoundException(
                'NO ROUTE ' . $this->_request->getPath());
            }

            $controller = $parameters['controller'];
            $action     = $parameters['action'];
            $this->getContent($controller, $action, $parameters);
        } catch (FileNotFoundException $e) {
            $this->dispErrorPage($e);
        }
        $this->_response->send();
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: getContent                                   */
    /*======================================================*/
    /* 概要  : Viewに埋め込むコンテンツの取得               */
    /* 引数  : コントローラ名、アクション名、パラメータ     */
    /* 戻り値: なし                                         */
    /********************************************************/
    public function getContent($controllerName, $action, $parameters = array()) {
        $controllerClass = ucfirst($controllerName) . self::DIF_CONTROLLER;
        $controller      = $this->getControllerObject($controllerClass);

        if ($controller === false) {
            throw new FileNotFoundException($controllerClass . ' NOT FOUND.');
        }

        $content = $controller->dispatch($action, $parameters);
        $this->_response->setContent($content);
    }

    /********************************************************/
    /* Protected Function                                   */
    /*======================================================*/
    /* 関数名: getControllerObject                          */
    /*======================================================*/
    /* 概要  : コントローラーオブジェクト取得               */
    /* 引数  : コントローラークラス                         */
    /* 戻り値: コントローラーオブジェクト                   */
    /********************************************************/
    protected function getControllerObject($controllerClass){
        if (!class_exists($controllerClass)) {
            $controllerFile = $this->getControllerDirectory() . '/' . $controllerClass . '.php';
            if (!is_readable($controllerFile)) {
                return false;
            } else {
              require_once $controllerFile;
              if (!class_exists($controllerClass)) {
                  return false;
              }
            }
        }
        $controller = new $controllerClass($this);
        return $controller;
    }

    /********************************************************/
    /* Protected Function                                   */
    /*======================================================*/
    /* 関数名: dispErrorPage                                */
    /*======================================================*/
    /* 概要  : エラーメッセージの表示                       */
    /* 引数  : エラーメッセージ                             */
    /* 戻り値: なし                                         */
    /********************************************************/
    protected function dispErrorPage($e){
      $this->_response
              ->setStatusCode(404, 'FILE NOT FOUND.');
      $errMessage = $this->isDisplayErrors() ? $e->getMessage() : 'FILE NOT FOUND.';
      $errMessage = htmlspecialchars($errMessage, ENT_QUOTES, 'UTF-8');
      $html = "
      <!DOCTYPE html>
      <html>
      <head>
      <meta charset='UTF-8' />
      <title>HTTP 404 Error</title>
      </head>
      <body>
      {$errMessage}
      </body>
      </html>
      ";
      $this->_response->setContent($html);
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: getViewDirectory                             */
    /*======================================================*/
    /* 概要  : Viewディレクトリへのパスを取得               */
    /* 引数  : なし                                         */
    /* 戻り値: Viewディレクトリへのパス                     */
    /********************************************************/
    public function getViewDirectory(){
        return $this->getRootDirectory() . self::DIF_DIR_VIEW;
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: getModelDirectory                            */
    /*======================================================*/
    /* 概要  : Modelディレクトリへのパスを取得              */
    /* 引数  : なし                                         */
    /* 戻り値: Modelディレクトリへのパス                    */
    /********************************************************/
    public function getModelDirectory(){
        return $this->getRootDirectory() . self::DIF_DIR_MODELS;
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: getDocDirectory                              */
    /*======================================================*/
    /* 概要  : ドキュメントルートへのパスを取得             */
    /* 引数  : なし                                         */
    /* 戻り値: ドキュメントルートへのパス                    */
    /********************************************************/
    public function getDocDirectory(){
        return $this->getRootDirectory() . self::DIF_DIR_WEB;
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: getControllerDirectory                       */
    /*======================================================*/
    /* 概要  : Controllerディレクトリへのパスを取得         */
    /* 引数  : なし                                         */
    /* 戻り値: Controllerディレクトリへのパス               */
    /********************************************************/
    public function getControllerDirectory(){
  	  return $this->getRootDirectory() . self::DIF_DIR_CONTROLLERS;
  	}

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: getRequestObject                             */
    /*======================================================*/
    /* 概要  : リクエストオブジェクトの取得                 */
    /* 引数  : なし                                         */
    /* 戻り値: リクエストオブジェクト                       */
    /********************************************************/
    public function getRequestObject(){
        return $this->_request;
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: getResponseObject                            */
    /*======================================================*/
    /* 概要  : レスポンスオブジェクトの取得                 */
    /* 引数  : なし                                         */
    /* 戻り値: レスポンスオブジェクト                       */
    /********************************************************/
    public function getResponseObject(){
        return $this->_response;
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: getSessionObject                             */
    /*======================================================*/
    /* 概要  : セッションオブジェクトの取得                 */
    /* 引数  : なし                                         */
    /* 戻り値: セッションオブジェクト                       */
    /********************************************************/
    public function getSessionObject(){
        return $this->_session;
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: getResponseObject                            */
    /*======================================================*/
    /* 概要  : ConnectModelオブジェクトの取得               */
    /* 引数  : なし                                         */
    /* 戻り値: ConnectModelオブジェクト                     */
    /********************************************************/
    public function getConnectModelObject(){
        return $this->_connectModel;
    }

    abstract protected function getRouteDefinition();

    abstract public function getRootDirectory();

    protected function doDbConnection() {}

}
