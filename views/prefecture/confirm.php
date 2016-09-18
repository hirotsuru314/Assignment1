<!--*****************************************************/
/* プログラム名：confirm.php                            */
/*======================================================*/
/* 概要：地域マスタ登録確認画面                         */
/*======================================================*/
/* 作成日：2016-9-5                                     */
/* 作成者：鶴田 博文                                    */
/* 最終更新日：                                         */
/* 更新者：                                             */
/******************************************************-->

<div class = "container-fluid">
    <div class = "row">
        <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
            <?php if (isset($_POST["register"]) || isset($_POST["update"])) : ?>
                <h1>地域マスタ登録確認</h1>
            <?php endif; ?>
            <?php if (isset($_POST['delete'])) : ?>
                <h1>地域マスタ削除確認</h1>
            <?php endif; ?>

            <div class="well well-lg">
                <div class = "row">
                    <div class = "col-sm-8 col-sm-offset-2 col-xs-8 col-xs-offset-2">
                        <table class="table table-bordered">
                        	<tbody>
                        		<tr>
                        			<td class = "code">地域コード</td>
                        			<td class = "name"><?php echo $this->escape($prefectureCD) ?></td>
                        		</tr>
                        		<tr>
                        			<td class = "code">地域名</td>
                        			<td class = "name"><?php echo $this->escape($prefectureName) ?></td>
                        		</tr>
                        	</tbody>
                        </table>

                        <form action="<?php print $base_url; ?>/prefecture/complete" method="post" name = "complete" id = "complete">
                            <div class="form-group">
                                <input type = "hidden" name = "prefectureCD" id = "prefectureCD" value = "<?php echo $this->escape($prefectureCD); ?>" />
                                <input type = "hidden" name = "prefectureName" id = "prefectureName" value = "<?php echo $this->escape($prefectureName); ?>" />

                                <!-- 登録ボタン -->
                                <?php if (isset($_POST["register"])): ?>
                                    <input class = "btn btn-default pull-right" type = "submit" name = "register" id = "register" style = "width: 150px" value = "登録" />
                                    <input type = "hidden" name = "_token" id = "_token" value = "<?php print $this->escape($_token); ?>" />
                                <?php endif; ?>

                                <?php if (isset($_POST["update"])): ?>
                                    <input class = "btn btn-default pull-right" type = "submit" name = "update" id = "update" style = "width: 150px" value = "登録" />
                                    <input type = "hidden" name = "_token" id = "_token" value = "<?php print $this->escape($_token); ?>" />
                                <?php endif; ?>

                                <!-- 削除ボタン -->
                                <?php if (isset($_POST['delete'])): ?>
                                    <input class = "btn btn-default pull-right" type = "submit" name = "delete" id = "delete" style = "width: 150px" value = "削除" />
                                    <input type = "hidden" name = "_token" id = "_token" value = "<?php print $this->escape($_token); ?>" />
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

                <!-- 戻るボタン -->
                <?php if (isset($_POST["register"])): ?>
                    <form action = "<?php print $base_url; ?>/prefecture/register" method = "post" name = "register" id = "register">
                        <input type = "hidden" name = "prefectureCD" id = "prefectureCD" value = "<?php echo $prefectureCD; ?>" />
                        <input type = "hidden" name = "prefectureName" id = "prefectureName" value = "<?php echo $prefectureName; ?>" />
                        <input class = "btn btn-default" type = "submit" name = "registerReturn" id = "registerReturn" style = "width: 100px" value = "戻る" />
                    </form>
                <?php endif; ?>

                <?php if (isset($_POST["update"])): ?>
                    <form action = "<?php print $base_url; ?>/prefecture/register" method = "post" name = "update" id = "update">
                        <input type = "hidden" name = "prefectureCD" id = "prefectureCD" value = "<?php echo $prefectureCD; ?>" />
                        <input type = "hidden" name = "prefectureName" id = "prefectureName" value = "<?php echo $prefectureName; ?>" />
                        <input class = "btn btn-default" type = "submit" name = "updateReturn" id = "updateReturn" style = "width: 100px" value = "戻る" />
                    </form>
                <?php endif; ?>

                <?php if (isset($_POST["delete"])): ?>
                    <form action = "<?php print $base_url; ?>/prefecture/search" method = "post" name = "delete" id = "delete">
                        <input class = "btn btn-default" type = "submit" name = "return" id = "return" style = "width: 100px" value = "戻る" />
                        <input type = "hidden" name = "searchPrefectureCD" id = "searchPrefectureCD" value = "<?php echo $searchPrefectureCD; ?>" />
                        <input type = "hidden" name = "searchPrefectureName" id = "searchPrefectureName" value = "<?php echo $searchPrefectureName; ?>" />
                        <input type = "hidden" name = "searchPage" id = "searchPage" value = "<?php echo $searchPage; ?>" />
                    </form>
                <?php endif; ?>
        </div>
    </div>
</div>
