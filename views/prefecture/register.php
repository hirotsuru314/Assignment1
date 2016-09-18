<!--*****************************************************/
/* プログラム名：register.php                           */
/*======================================================*/
/* 概要：地域マスタ登録画面                             */
/*======================================================*/
/* 作成日：2016-9-5                                     */
/* 作成者：鶴田 博文                                    */
/* 最終更新日：                                         */
/* 更新者：                                             */
/******************************************************-->

<div class = "container-fluid">
    <div class = "row">
        <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
            <h1>地域マスタ登録</h1>

            <div class="well well-lg">
                <div class = "row">
                    <div class = "col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">

                        <!-- エラメッセージ -->
                        <?php if (isset($errors) && count($errors) > 0): ?>
                        <?php print $this->render('errors', array('errors' => $errors)); ?>
                        <?php endif; ?>

                        <!-- 登録フォーム -->
                        <form action = "<?php print $base_url; ?>/prefecture/confirm" method = "post" class = "form-horizontal" name = "register" id = "register">
                            <div class = "form-group">
                                <label for="prefectureCD" class = "col-sm-3 control-label">地域コード</label>
                                <div class = "col-sm-9">

                                    <?php if (isset($_POST["register"]) || isset($_POST["registerReturn"])) : ?>
                                        <input type="text" class="form-control" name = "prefectureCD" id = "prefectureCD" value = "<?php if (isset($prefectureCD)) : ?><?php echo $this->escape($prefectureCD); ?><?php endif; ?>" />
                                    <?php endif; ?>

                                    <!-- 更新のときは地域コードをlabel状態にする -->
                                    <?php if (isset($_POST['update']) || isset($_POST["updateReturn"])) : ?>
                                        <label for="prefectureCD" class = "control-label"><?php echo $this->escape($prefectureCD); ?></label>
                                        <input type = "hidden" name = "prefectureCD" id = "prefectureCD" value = "<?php echo $this->escape($prefectureCD); ?>" />
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class = "form-group">
                                <label for="prefectureName" class = "col-sm-3 control-label">地域名</label>
                                <div class = "col-sm-9">
                                    <input type = "text" class = "form-control" name = "prefectureName" id = "prefectureName" value = "<?php if (isset($prefectureName)) : ?><?php echo $this->escape($prefectureName); ?><?php endif; ?>" />
                                </div>
                            </div>

                            <!-- 登録ボタン -->
                            <div class = "form-group">
                                <?php if (isset($_POST["register"]) || isset($_POST["registerReturn"])): ?>
                                    <input class = "btn btn-default pull-right" type = "submit" name = "register" id = "register" style = "width: 100px" value="登録" />
                                <?php endif; ?>

                                <?php if (isset($_POST["update"]) || isset($_POST["updateReturn"])): ?>
                                    <input class = "btn btn-default pull-right" type = "submit" name = "update" id = "update" style = "width: 100px" value="登録" />
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- 戻るボタン -->
            <?php if (isset($searchPrefectureCD)): ?>
                <form action = "<?php print $base_url; ?>/prefecture/search" method = "post" class = "form-horizontal">
                    <input type = "hidden" name = "searchPrefectureCD" id = "searchPrefectureCD" value = "<?php echo $searchPrefectureCD; ?>" />
                    <input type = "hidden" name = "searchPrefectureName" id = "searchPrefectureName" value = "<?php echo $searchPrefectureName; ?>" />
                    <input type = "hidden" name = "searchPage" id = "searchPage" value = "<?php echo $searchPage; ?>" />
                    <input class = "btn btn-default" type = "submit" name = "return" style = "width: 100px" value = "戻る" />
                </form>
            <?php else: ?>
                <a class="btn btn-default" href="/" role="button" style = "width: 100px">戻る</a>
            <?php endif; ?>
        </div>
    </div>
</div>
