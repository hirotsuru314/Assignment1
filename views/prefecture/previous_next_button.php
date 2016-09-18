<!--*****************************************************/
/* プログラム名：previous_next_button.php               */
/*======================================================*/
/* 概要：『前のページ』『次のページ』ボタンを表示       */
/*======================================================*/
/* 作成日：2016-9-5                                     */
/* 作成者：鶴田 博文                                    */
/* 最終更新日：                                         */
/* 更新者：                                             */
/******************************************************-->

<form action = "<?php print $base_url; ?>/prefecture/search" method = "get" name = "search" id = "search">
    <?php if ($page > 1): ?>
        <input class = "btn btn-default" type = "submit" name = "search" style = "width: 100px" value = "前のページ" />
        <input type = "hidden" name = "page" id = "page" value = "<?php echo $this->escape($page - 1); ?>" />
        <input type = "hidden" name = "prefectureCD" id = "prefectureCD" value = "<?php echo $prefectureCD; ?>" />
        <input type = "hidden" name = "prefectureName" id = "prefectureName" value = "<?php echo $prefectureName; ?>" />
    <?php endif; ?>
    <?php if ($recordNumber > $page*10): ?>
      <input class = "btn btn-default" type = "submit" name = "search" style = "width: 100px" value = "次のページ" />
      <input type = "hidden" name = "page" id = "page" value = "<?php echo $this->escape($page + 1); ?>" />
      <input type = "hidden" name = "prefectureCD" id = "prefectureCD" value = "<?php echo $prefectureCD; ?>" />
      <input type = "hidden" name = "prefectureName" id = "prefectureName" value = "<?php echo $prefectureName; ?>" />
    <?php endif; ?>
</form>
