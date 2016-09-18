<!--*****************************************************/
/* プログラム名：complete.php                           */
/*======================================================*/
/* 概要：地域マスタ登録完了画面                         */
/*======================================================*/
/* 作成日：2016-9-5                                     */
/* 作成者：鶴田 博文                                    */
/* 最終更新日：                                         */
/* 更新者：                                             */
/******************************************************-->

<div class = "container-fluid">
    <div class = "row">
        <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
            <?php if (isset($_POST["register"]) || isset($_POST["update"])): ?>
                <h1>地域マスタ登録完了</h1>
            <?php endif; ?>
            <?php if (isset($_POST['delete'])): ?>
                <h1>地域マスタ削除完了</h1>
            <?php endif; ?>

            <div class="well well-lg">
                <div class = "row">
                    <div class = "col-sm-8 col-sm-offset-2 col-xs-8 col-xs-offset-2">
                        <p>地域マスタの登録が完了しました。</p>

                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class = "code">地域コード</td>
                                    <td class = "name"><?php echo $this->escape($prefectureCD); ?></td>
                                </tr>
                                <tr>
                                    <td class = "code">地域名</td>
                                    <td class = "name"><?php echo $this->escape($prefectureName); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <a class="btn btn-default" href="/" style = "width: 100px" role="button">戻る</a>
        </div>
    </div>
</div>
