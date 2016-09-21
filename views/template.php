<!--*****************************************************/
/* プログラム名：template.php                           */
/*======================================================*/
/* 概要：Viewファイルの共通部分                         */
/*======================================================*/
/* 作成日：2016-9-5                                     */
/* 作成者：鶴田 博文                                    */
/* 最終更新日：2016-9-21                                */
/* 更新者：鶴田 博文                                    */
/******************************************************-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrapの読み込み -->
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

        <!-- CSSの読み込み -->
        <link rel="stylesheet" type="text/css" href="/css/reset.css" />
        <link rel="stylesheet" type="text/css" href="/css/style.css" />

        <title>地域マスタ</title>
    </head>

    <body>
        <?php print $_content; ?>
    </body>
</html>
