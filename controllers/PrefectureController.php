<?php
/********************************************************/
/* プログラム名：PrefectureController.php               */
/*======================================================*/
/* 概要：アクションメソッドを定義                       */
/*======================================================*/
/* 作成日：2016-9-5                                     */
/* 作成者：鶴田 博文                                    */
/* 最終更新日：2016-9-21                                */
/* 更新者：鶴田 博文                                    */
/********************************************************/
class PrefectureController extends Controller
{
    const DEF_PASS_REGISTER = 'prefecture/register';
    const DEF_PASS_DELETE = 'prefecture/delete';

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: indexAction                                  */
    /*======================================================*/
    /* 概要  : 一覧画面を表示する                           */
    /* 引数  : なし                                         */
    /* 戻り値: Viewファイル                                 */
    /********************************************************/
    public function indexAction() {
        $index_view = $this->render();
        return $index_view;
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: searchAction                                 */
    /*======================================================*/
    /* 概要  : 検索結果を表示する                           */
    /* 引数  : なし                                         */
    /* 戻り値: Viewファイル                                 */
    /********************************************************/
    public function searchAction() {
        $errors = array();
        $prefectureCD = $this->_request->getGet('prefectureCD');
        $prefectureName = $this->_request->getGet('prefectureName');

        //戻るボタンが押されたときに検索条件を保持するための処理
        if (isset($_POST["return"])) {
            $prefectureCD = $this->_request->getPost('searchPrefectureCD');
            $prefectureName = $this->_request->getPost('searchPrefectureName');
            $page = $this->_request->getPost('searchPage');
        }

        //地域コードが2桁の半角数字でない場合、エラーを表示する
        if (!empty($prefectureCD) && !preg_match('/^(\d|\d\d)$/', $prefectureCD)) {
            $errors[] = "検索条件が不正です";
        }

        //エラーがない場合の処理
        if(count($errors) === 0) {
            //地域コードのゼロパディング
            if (!empty($prefectureCD)) {
                $prefectureCD = str_pad($prefectureCD, 2, 0, STR_PAD_LEFT);
            }

            //検索にヒットしたレコード数を取得
            $recordNumber = $this->_connect_model->get('Prefecture')->searchRecordNumber($prefectureCD, $prefectureName);

            //ページネーション、$pageをセットする
            if (!isset($_POST["return"])) {
                if (empty($_GET["page"])) {
                    $_GET["page"] = 1;
                    $page = $_GET["page"];
                } elseif (isset($_GET["page"])) {
                    $page = $_GET["page"];
                }
            }

            $limit = 10; // １ページの最大表示数
            $offset = $limit * ($page - 1); //表示する際のオフセット

            //検索条件にあうレコードをmst_prefectureテーブルから取得
            $searchedPrefectures = $this->_connect_model->get('Prefecture')->searchRecord($prefectureCD, $prefectureName, $limit, $offset);
            $search_view = $this->render(array(
                'searchedPrefectures' => $searchedPrefectures,
                'prefectureCD'        => $prefectureCD,
                'prefectureName'      => $prefectureName,
                'recordNumber'        => $recordNumber,
                'errors'              => $errors,
                'page'                => $page,
                'offset'              => $offset),
                'index');
            return $search_view;
        } else {
            return $this->render(array('errors' => $errors), 'index');
        }
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: registerAction                               */
    /*======================================================*/
    /* 概要  : 登録画面に遷移する                           */
    /* 引数  : なし                                         */
    /* 戻り値: Viewファイル                                 */
    /********************************************************/
    public function registerAction() {
        //一覧画面が検索結果を表示した状態から追加ボタンが押された場合
        if (isset($_POST["searchPrefectureCD"])) {
            $searchPrefectureCD = $this->_request->getPost('searchPrefectureCD');
            $searchPrefectureName = $this->_request->getPost('searchPrefectureName');
            $searchPage = $this->_request->getPost('searchPage');
            $register_view = $this->render(array(
                'searchPrefectureCD'   => $searchPrefectureCD,
                'searchPrefectureName' => $searchPrefectureName,
                'searchPage'           => $searchPage,
                '_token'               => $this->getToken(self::DEF_PASS_REGISTER)));
            return $register_view;
        } else {
          //一覧画面が検索結果を表示していない状態から追加ボタンが押された場合
            $prefectureCD = $this->_request->getPost('prefectureCD');
            $prefectureName = $this->_request->getPost('prefectureName');
            $register_view = $this->render(array(
                'prefectureCD'   => $prefectureCD,
                'prefectureName' => $prefectureName));
            return $register_view;
        }
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: confirmAction                                */
    /*======================================================*/
    /* 概要  : 登録確認画面に遷移する                       */
    /* 引数  : なし                                         */
    /* 戻り値: Viewファイル                                 */
    /********************************************************/
    public function confirmAction() {
        //POST以外のリクエストの場合はエラー
        if (!$this->_request->isPost()) {
            $this->httpNotFound();
        }

        $prefectureCD = $this->_request->getPost('prefectureCD');
        $prefectureName = $this->_request->getPost('prefectureName');
        $errors = array();

        //地域コードのバリデーション
        if (!isset($prefectureCD) || !strlen($prefectureCD) || !preg_match('/^(\d|\d\d)$/', $prefectureCD)) {
            $errors[] = "地域コードが不正です";
            $prefectureCD = '';
        }

        //INSERT START（2016/9/21)鶴田博文
        //地域コードがすでに登録済みの場合はエラーを表示
        if (!$this->_connect_model->get('Prefecture')->isUniquePrefectureCode($prefectureCD)) {
            $errors[] = "地域コードがすでに登録済みです";
        }
        //INSERT END（2016/9/21)鶴田博文

        //地域名のバリデーション
        if (!isset($prefectureName) || !strlen($prefectureName) || mb_strlen($prefectureName) > 20) {
            $errors[] = "地域名が不正です";
            $prefectureName = '';
        }

        //バリデーションを通過した場合の処理
        if (count($errors) === 0) {
            $prefectureCD = str_pad($prefectureCD, 2, 0, STR_PAD_LEFT);
            $prefectureName = mb_convert_kana($prefectureName, "ASK");
            $confirm_view = $this->render(array(
                'prefectureCD'   => $prefectureCD,
                'prefectureName' => $prefectureName,
                '_token'         => $this->getToken(self::DEF_PASS_REGISTER)));
            return $confirm_view;
        } else {
        //エラーがある場合は、登録画面から遷移せず、エラーメッセージを表示する
            $confirm_view = $this->render(array(
                'prefectureCD'   => $prefectureCD,
                'prefectureName' => $prefectureName,
                'errors'         => $errors),
                'register');
            return $confirm_view;
        }
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: completeAction                               */
    /*======================================================*/
    /* 概要  : 登録完了画面に遷移する                       */
    /* 引数  : なし                                         */
    /* 戻り値: Viewファイル                                 */
    /********************************************************/
    public function completeAction() {
        //POST以外のリクエストの場合はエラー
        if (!$this->_request->isPost()) {
            $this->httpNotFound();
        }

        $prefectureCD = $this->_request->getPost('prefectureCD');
        $prefectureName = $this->_request->getPost('prefectureName');

        //登録ボタンが押されたとき(新規登録時)
        if (isset($_POST['register'])) {
            $token = $this->_request->getPost('_token');
            if (!$this->checkToken(self::DEF_PASS_REGISTER, $token)) {
              return $this->redirect('/');
            }

            $this->_connect_model->get('Prefecture')->insert($prefectureCD, $prefectureName);
            $complete_view = $this->render(array(
                'prefectureCD'   => $prefectureCD,
                'prefectureName' => $prefectureName));
            return $complete_view;
        }

        //登録ボタンが押されたとき(更新時)
        if (isset($_POST['update'])) {
            $token = $this->_request->getPost('_token');
            if (!$this->checkToken(self::DEF_PASS_REGISTER, $token)) {
              return $this->redirect('/');
            }

            $this->_connect_model->get('Prefecture')->update($prefectureCD, $prefectureName);
            $complete_view = $this->render(array(
                'prefectureCD'   => $prefectureCD,
                'prefectureName' => $prefectureName));
            return $complete_view;
        }

        //削除ボタンが押されたとき
        if (isset($_POST['delete'])) {
            $token = $this->_request->getPost('_token');
            if (!$this->checkToken(self::DEF_PASS_DELETE, $token)) {
              return $this->redirect('/');
            }

            $this->_connect_model->get('Prefecture')->delete($prefectureCD);
            $this->redirect('/');
        }

    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: updateAction                                 */
    /*======================================================*/
    /* 概要  : 登録画面に遷移する（更新時）                 */
    /* 引数  : なし                                         */
    /* 戻り値: Viewファイル                                 */
    /********************************************************/
    public function updateAction() {
        //POST以外のリクエストの場合はエラー
        if (!$this->_request->isPost()) {
            $this->httpNotFound();
        }

        //戻るボタンが押されたときのために、検索条件を保持する
        $searchPrefectureCD = $this->_request->getPost('searchPrefectureCD');
        $searchPrefectureName = $this->_request->getPost('searchPrefectureName');
        $searchPage = $this->_request->getPost('searchPage');

        //地域コードから地域名を取得し$prefectureNameにセット
        $prefectureCD = $this->_request->getPost('prefectureCD');
        $prefectureRecord = $this->_connect_model->get('Prefecture')->getPrefectureRecord($prefectureCD);
        $prefectureName = $prefectureRecord['prefecture_name'];

        $update_view = $this->render(array(
            'prefectureCD'         => $prefectureCD,
            'prefectureName'       => $prefectureName,
            'searchPrefectureCD'   => $searchPrefectureCD,
            'searchPrefectureName' => $searchPrefectureName,
            'searchPage'           => $searchPage),
            'register');
        return $update_view;
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: deleteAction                                 */
    /*======================================================*/
    /* 概要  : 登録確認画面に遷移する（削除時）             */
    /* 引数  : なし                                         */
    /* 戻り値: Viewファイル                                 */
    /********************************************************/
    public function deleteAction() {
        //POST以外のリクエストの場合はエラー
        if (!$this->_request->isPost()) {
            $this->httpNotFound();
        }

        //戻るボタンが押されたときのために、検索条件を保持する
        $searchPrefectureCD = $this->_request->getPost('searchPrefectureCD');
        $searchPrefectureName = $this->_request->getPost('searchPrefectureName');
        $searchPage = $this->_request->getPost('searchPage');

        //地域コードから地域名を取得し$prefectureNameにセット
        $prefectureCD = $this->_request->getPost('prefectureCD');
        $prefectureRecord = $this->_connect_model->get('Prefecture')->getPrefectureRecord($prefectureCD);
        $prefectureName = $prefectureRecord['prefecture_name'];

        $delete_view = $this->render(array(
            'prefectureCD'         => $prefectureCD,
            'prefectureName'       => $prefectureName,
            'searchPrefectureCD'   => $searchPrefectureCD,
            'searchPrefectureName' => $searchPrefectureName,
            'searchPage'           => $searchPage,
            '_token'               => $this->getToken(self::DEF_PASS_DELETE)),
            'confirm');
        return $delete_view;
    }
}
