<?php
/********************************************************/
/* プログラム名：ConnectModel.php                       */
/*======================================================*/
/* 概要：データベースへの接続に関する処理               */
/*======================================================*/
/* 作成日：2016-9-5                                     */
/* 作成者：鶴田 博文                                    */
/* 最終更新日：                                         */
/* 更新者：                                             */
/********************************************************/

class ConnectModel
{
    protected $_dbConnections = array();  // PDOクラスのインスタンスを配列で保持するプロパティ
    protected $_modelList = array();  // モデルクラスのインスタンスを保持するプロパティ
    protected $_connectName;  // 接続名を保持するプロパティ
    const DEF_MODEL = 'Model';  // モデルクラス名を格納する定数

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: connect                                      */
    /*======================================================*/
    /* 概要  : データベース接続用のPDOオブジェクト作成      */
    /* 引数  : 接続名、ユーザー名、パスワードなどの設定値   */
    /* 戻り値: なし                                         */
    /********************************************************/
    public function connect($name, $connection_strings) {
        try {
            $cnt = new PDO(
                $connection_strings['string'],      // 接続文字列
                $connection_strings['user'],        // ユーザー名
                $connection_strings['password']     // パスワード
            );
        } catch(PDOException $e) {
            exit("データベースの接続に失敗しました。 : {$e->getMessage()}");
        }
        $cnt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->_dbConnections[$name] = $cnt;
        $this->_connectName = $name;
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: getConnection                                */
    /*======================================================*/
    /* 概要  : データベース接続用のPDOオブジェクトを返す    */
    /* 引数  : 接続名                                       */
    /* 戻り値: dbConnectionsプロパティに格納されている      */
    /*         PDOオブジェクト                              */
    /********************************************************/
    public function getConnection($name = null) {
      // 接続名が渡されなかったときの処理
      if (is_null($name)) {
          // _dbConnectionsプロパティに格納されている先頭の要素の値を返す
          return current($this->_dbConnections);
      }
      return $this->_dbConnections[$name];
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: getModelConnection                           */
    /*======================================================*/
    /* 概要  : データモデルに対応するデータベース接続を取得 */
    /* 引数  : なし                                         */
    /* 戻り値: PDOオブジェクト                              */
    /********************************************************/
    public function getModelConnection(){
      if (isset($this->_connectName)) {
          // $model_nameキーの値を接続名として$nameに格納
          $name = $this->_connectName;
          // 接続名$nameのPDOオブジェクトを取得
          $cnt = $this->getConnection($name);
      } else {
          // _connectNameプロパティに値がなければ、先頭のPDOオブジェクトを取得
          $cnt = $this->getConnection();
      }
      // PDOオブジェクトを返す
      return $cnt;
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: get                                          */
    /*======================================================*/
    /* 概要  : データモデルオブジェクトを取得               */
    /* 引数  : モデル名                                     */
    /* 戻り値: データモデルクラスのインスタンス             */
    /********************************************************/
    public function get($model_name) {
        // _modelListプロパティの$model_nameキーに
        // データモデル名が存在しなければPDOオブジェクトを取得
        if (!isset($this->_modelList[$model_name])) {
            // データモデル名に'Model'を連結し、これをクラス名として代入
            $mdl_class = $model_name . self::DEF_MODEL;
            $cnt = $this->getModelConnection();
            $obj = new $mdl_class($cnt);
            // _modelListプロパティに「データモデル名=>データモデルクラスのインスタンス」を格納
            $this->_modelList[$model_name] = $obj;
        }
        // データモデルクラスのインスタンスを返す
        $modelObj = $this->_modelList[$model_name];
        return $modelObj;
    }

    public function __destruct() {
        foreach ($this->_modelList as $model) {
            unset($model);  // $modelを破棄
        }
        foreach ($this->_dbConnections as $cnt) {
            unset($cnt);    // $cntを破棄
        }
    }
}
