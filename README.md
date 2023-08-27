# PukiWiki用プラグイン<br>スクリーンセーバー screensaver.inc.php

スクリーンセーバー機能を実現する[PukiWiki](https://pukiwiki.osdn.jp/)用プラグイン。  
ブラウザー上でマウス・キーボード・タッチ操作がないまま一定時間経過すると、サイトの画面が指定色で塗りつぶされます。  
操作を再開すると元に戻ります。

微妙な情報を扱うウィキにおいて、離席時の画面隠蔽に役立つかもしれません。  
ただし、あくまで簡易的な実装なので過信は禁物です。期待通り動作するか必ずご確認ください。

|対象PukiWikiバージョン|対象PHPバージョン|
|:---:|:---:|
|PukiWiki 1.5.4 (UTF-8)|PHP 8.2|

## インストール

下記GitHubページからダウンロードした screensaver.inc.php を PukiWiki の plugin ディレクトリに配置してください。

[https://github.com/ikamonster/pukiwiki-screensaver](https://github.com/ikamonster/pukiwiki-screensaver)

## 使い方

**特定のページにのみ導入する場合**
```
#screensaver
```

**サイト全体に導入する場合**  
スキンファイルHTML内\<body\>と\</body\>の間の適当な箇所に次のコードを挿入する。
```PHP
<?php if (exist_plugin_convert('screensaver')) echo do_plugin_convert('screensaver'); // ScreenSaver plugin ?>
```

## ご注意

- スキンや他のプラグインとの相性、ブラウザーによっては機能しない場合があります。
- JavaScriptが有効でないと動作しません。

## 設定

ソース内の下記の定数で動作を制御することができます。

|定数名|値|既定値|意味|
|:---|:---:|:---:|:---|
|PLUGIN_SCREENSAVER_TIMEOUT_SEC|数値|0|スクリーンセーバーの起動時間（秒）。0なら無効|
|PLUGIN_SCREENSAVER_COLOR|HTMLカラーコード|''|画面を塗りつぶす色のHTMLカラーコード（例：'#808080'）。空ならサイトの背景色。'blur'ならボカシ（ブラウザーによっては効かないので注意）|
