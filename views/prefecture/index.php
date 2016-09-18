<!--*****************************************************/
/* プログラム名：index.php                              */
/*======================================================*/
/* 概要：地域マスタ一覧画面(検索結果表示を除く)         */
/*======================================================*/
/* 作成日：2016-9-5                                     */
/* 作成者：鶴田 博文                                    */
/* 最終更新日：                                         */
/* 更新者：                                             */
/******************************************************-->

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
            <h1>地域マスタ一覧</h1>

            <div class="well well-lg">
                <div class="row">
                    <!-- エラーメッセージ -->
                    <?php if (isset($errors) && count($errors) > 0): ?>
                    <?php print $this->render('errors', array('errors' => $errors)); ?>
                    <?php endif; ?>

                    <!-- 検索フォーム -->
                    <form action="<?php print $base_url; ?>/prefecture/search" method="get" class="form-inline" id = "search" name = "search">
                        <div class = "col-sm-9">

                            <div class="form-group">
                                <label for="prefectureCD" class = "control-label">地域コード</label>
                                <input type="text" class="form-control" name = "prefectureCD" id = "prefectureCD" style = "width: 90px"
                                 value = "<?php if (isset($prefectureCD)): ?><?php echo $this->escape($prefectureCD); ?><?php endif; ?>" />
                            </div>

                            <div class="form-group">
                                <label for="prefectureName" class = "control-label">地域名</label>
                                <input type="text" class="form-control" name = "prefectureName" id = "prefectureName" style = "width: 160px" value = "<?php if (isset($prefectureName)) : ?><?php echo $this->escape($prefectureName); ?><?php endif; ?>" />
                            </div>
                        </div>

                        <!-- 検索ボタン -->
                        <div class = "col-sm-3">
                            <div class="form-group">
                            <input class="btn btn-default pull-right" type="submit" name = "search" id = "search" style = "width: 100px" value="検索" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 検索結果表示 -->
            <?php if ((!empty($_GET["search"]) | !empty($_POST["return"])) && count($errors) === 0  && count($searchedPrefectures) > 0): ?>
                <?php include_once('search.php');?>
            <?php elseif (isset($searchedPrefectures) && count($searchedPrefectures) === 0): ?>
                <p>データが存在しません</p>
            <?php endif; ?>

            <!-- 追加ボタン-->
            <form action="<?php print $base_url; ?>/prefecture/register" method="post" id = "register" name = "register">
                <input class = "btn btn-default pull-right" type = "submit" style = "width: 100px" name = "register" value = "追加" />
                <?php if (isset($searchedPrefectures)): ?>
                    <input type = "hidden" name = "searchPrefectureCD" id = "searchPrefectureCD" value = "<?php echo $this->escape($prefectureCD); ?>" />
                    <input type = "hidden" name = "searchPrefectureName" id = "searchPrefectureName" value = "<?php echo $this->escape($prefectureName); ?>" />
                    <input type = "hidden" name = "searchPage" id = "searchPage" value = "<?php echo $page; ?>" />
                <?php endif; ?>
            </form>

        </div>
    </div>
</div>
