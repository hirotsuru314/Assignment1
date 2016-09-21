<?php
/********************************************************/
/* プログラム名：PrefectureModel.php                    */
/*======================================================*/
/* 概要：mst_prefectureテーブルに対する操作             */
/*======================================================*/
/* 作成日：2016-9-5                                     */
/* 作成者：鶴田 博文                                    */
/* 最終更新日：2016-9-21                                */
/* 更新者：鶴田 博文                                    */
/********************************************************/
class PrefectureModel extends ExecuteModel
{
    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: insert                                       */
    /*======================================================*/
    /* 概要  : mst_prefectureテーブルへのレコード追加       */
    /* 引数  : 地域コード、地域名                           */
    /* 戻り値: なし                                         */
    /********************************************************/
    public function insert($prefectureCD, $prefectureName) {
        $sql = "INSERT INTO mst_prefecture (prefecture_cd, prefecture_name, insert_date, insert_cd)
                values (:prefecture_cd, :prefecture_name, now(), 'admin')";
        $stmt = $this->execute($sql, array(
            ':prefecture_cd'   =>  $prefectureCD,
            ':prefecture_name' =>  $prefectureName));
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: isUniquePrefectureCode                       */
    /*======================================================*/
    /* 概要  : 地域コードがすでに登録済みであるか調べる     */
    /* 引数  : 地域コード                                   */
    /* 戻り値: Boolean                                      */
    /********************************************************/
    public function isUniquePrefectureCode($prefectureCD) {
        $prefectureCD = str_pad($prefectureCD, 2, 0, STR_PAD_LEFT);  //ゼロパディング
        $sql = "SELECT COUNT(prefecture_cd) AS count
                FROM mst_prefecture WHERE prefecture_cd = :prefecture_cd";
        $row = $this->getRecord($sql, array(':prefecture_cd' => $prefectureCD));
        if ($row['count'] === '0') {
            return true;
        }
        return false;
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: searchRecord                                 */
    /*======================================================*/
    /* 概要  : 一覧画面からの検索                           */
    /* 引数  : 地域コード、地域名、最大表示数、オフセット   */
    /* 戻り値: 検索結果                                     */
    /********************************************************/
    public function searchRecord($prefectureCD, $prefectureName, $limit, $offset) {
        $sql = "SELECT * FROM mst_prefecture WHERE (prefecture_cd = CASE WHEN :prefecture_cd = ''
            THEN prefecture_cd ELSE :prefecture_cd END) AND (CASE WHEN :prefecture_name = '%%'
            THEN prefecture_name = prefecture_name ELSE prefecture_name LIKE(:prefecture_name) END)
            AND (delete_flg = '0') ORDER BY prefecture_cd ASC LIMIT $limit OFFSET $offset";
        $searchedPrefectures = $this->getAllRecord($sql, array(
            ':prefecture_cd'   =>  $prefectureCD,
            ':prefecture_name' =>  "%{$prefectureName}%"));
        return $searchedPrefectures;
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: searchRecordNumber                           */
    /*======================================================*/
    /* 概要  : 検索にヒットした総数を調べる                 */
    /* 引数  : 地域コード、地域名                           */
    /* 戻り値: 検索にヒットした総数                         */
    /********************************************************/
    public function searchRecordNumber($prefectureCD, $prefectureName) {
        $sql = "SELECT * FROM mst_prefecture WHERE (prefecture_cd = CASE WHEN :prefecture_cd = ''
            THEN prefecture_cd ELSE :prefecture_cd END) AND (CASE WHEN :prefecture_name = '%%'
            THEN prefecture_name = prefecture_name ELSE prefecture_name LIKE(:prefecture_name) END) AND (delete_flg = '0')";
        $recordNumber = $this->getAllRecord($sql, array(
            ':prefecture_cd'   =>  $prefectureCD,
            ':prefecture_name' =>  "%{$prefectureName}%"));
        return count($recordNumber);
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: getPrefectureRecord                          */
    /*======================================================*/
    /* 概要  : 地域コードに一致するレコードを抽出           */
    /* 引数  : 地域コード                                   */
    /* 戻り値: 地域コードに一致するレコード                 */
    /********************************************************/
    public function getPrefectureRecord($prefectureCD) {
        $sql = "SELECT * FROM mst_prefecture WHERE prefecture_cd = :prefecture_cd";
        $prefectureRecord = $this->getRecord($sql, array(':prefecture_cd' => $prefectureCD));
        return $prefectureRecord;
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: update                                       */
    /*======================================================*/
    /* 概要  : mst_prefectureテーブルへのレコード更新       */
    /* 引数  : 地域コード、地域名                           */
    /* 戻り値: なし                                         */
    /********************************************************/
    public function update($prefectureCD, $prefectureName) {
        $sql = "UPDATE mst_prefecture SET prefecture_name = :prefecture_name,
            update_date = now(), update_cd = 'admin' WHERE prefecture_cd = :prefecture_cd";
        $stmt = $this->execute($sql, array(
            ':prefecture_cd'   =>  $prefectureCD,
            ':prefecture_name' =>  $prefectureName));
    }

    /********************************************************/
    /* Public Function                                      */
    /*======================================================*/
    /* 関数名: delete                                       */
    /*======================================================*/
    /* 概要  : mst_prefectureテーブルのレコード削除         */
    /* 引数  : 地域コード                                   */
    /* 戻り値: なし                                         */
    /********************************************************/
    public function delete($prefectureCD) {
        $sql = "UPDATE mst_prefecture SET delete_flg = '1', delete_date = now()
            WHERE prefecture_cd = :prefecture_cd";
        $stmt = $this->execute($sql, array(':prefecture_cd' => $prefectureCD));
    }
}
