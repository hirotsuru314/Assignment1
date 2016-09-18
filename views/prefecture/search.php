<!--*****************************************************/
/* プログラム名：search.php                           */
/*======================================================*/
/* 概要：地域マスタ一覧画面の検索結果表示部分           */
/*======================================================*/
/* 作成日：2016-9-5                                     */
/* 作成者：鶴田 博文                                    */
/* 最終更新日：                                         */
/* 更新者：                                             */
/******************************************************-->
<?php include_once('record_number.php');?>

<table class="table table-bordered">
    <thead>
    <tr>
        <th class = "code">地域コード</th>
        <th class = "name">地域名</th>
        <th class = "update_date">更新日時</th>
        <th class = "update_btn"></th>
        <th class = "delete_btn"></th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($searchedPrefectures as $row) : ?>
            <tr>
                <td><?php echo $this->escape($row['prefecture_cd']); ?></td>
                <td><?php echo $this->escape($row['prefecture_name']); ?></td>
                <td><?php if (isset($row['update_date'])): ?><?php echo $this->escape($row['update_date']); ?>
                    <?php else: ?><?php echo $this->escape($row['insert_date']); ?><?php endif; ?></td>

                <!-- 更新ボタン -->
                <form action = "<?php print $base_url; ?>/prefecture/update" method = "post" name = "update" id = "update">
                    <input type = "hidden" name = "prefectureCD" id = "prefectureCD" value = "<?php echo $row['prefecture_cd']; ?>" />
                    <input type = "hidden" name = "searchPrefectureCD" id = "searchPrefectureCD" value = "<?php echo $prefectureCD; ?>" />
                    <input type = "hidden" name = "searchPrefectureName" id = "searchPrefectureName" value = "<?php echo $prefectureName; ?>" />
                    <input type = "hidden" name = "searchPage" id = "searchPage" value = "<?php echo $page; ?>" />
                    <td><input class = "btn btn-default" type = "submit" name = "update" id = "update" value = "更新" /></td>
                </form>

                <!-- 削除ボタン -->
                <form action = "<?php print $base_url; ?>/prefecture/delete" method = "post" name = "delete" id = "delete">
                    <input type = "hidden" name = "prefectureCD" id = "prefectureCD" value = "<?php echo $row['prefecture_cd']; ?>" />
                    <input type = "hidden" name = "searchPrefectureCD" id = "searchPrefectureCD" value = "<?php echo $prefectureCD; ?>" />
                    <input type = "hidden" name = "searchPrefectureName" id = "searchPrefectureName" value = "<?php echo $prefectureName; ?>" />
                    <input type = "hidden" name = "searchPage" id = "searchPage" value = "<?php echo $page; ?>" />
                    <td><input class = "btn btn-default" type = "submit" name = "delete" id = "delete" value = "削除" /></td>
                </form>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php if ($recordNumber > 10): ?>
    <?php include_once('previous_next_button.php');?>
<?php endif; ?>
