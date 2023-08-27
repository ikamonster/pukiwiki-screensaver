<?php
/*
PukiWiki - Yet another WikiWikiWeb clone.
screensaver.inc.php, v1.2.0 2021 M.Taniguchi
License: GPL v3 or (at your option) any later version

スクリーンセーバー機能を実現するプラグイン。

ブラウザー上でマウス・キーボード・タッチ操作がないまま一定時間経過すると、サイトの画面が指定色で塗りつぶされます。
操作を再開すると元に戻ります。

微妙な情報を扱うウィキにおいて、離席時の画面隠蔽に役立つかもしれません。
ただし、あくまで簡易的な実装なので過信は禁物です。期待通り動作するか必ずご確認ください。

【使い方】
#screensaver

サイト全体に導入するには、スキンファイルHTML内<body>と</body>の間の適当な箇所に次のコードを挿入する。
<?php if (exist_plugin_convert('screensaver')) echo do_plugin_convert('screensaver'); // ScreenSaver plugin ?>

【ご注意】
・スキンや他のプラグインとの相性、ブラウザーによっては機能しない場合があります。
・JavaScriptが有効でないと動作しません。
*/

/////////////////////////////////////////////////
// スクリーンセーバープラグイン（screensaver.inc.php）
if (!defined('PLUGIN_SCREENSAVER_TIMEOUT_SEC')) define('PLUGIN_SCREENSAVER_TIMEOUT_SEC', 0); // スクリーンセーバーの起動時間（秒）。0なら無効
if (!defined('PLUGIN_SCREENSAVER_COLOR'))       define('PLUGIN_SCREENSAVER_COLOR',      ''); // 画面を塗りつぶす色のHTMLカラーコード（例：'#808080'）。空ならサイトの背景色。'blur'ならボカシ（ブラウザーによっては効かないので注意）


function plugin_screensaver_convert() {
	if (!PLUGIN_SCREENSAVER_TIMEOUT_SEC || !PKWK_ALLOW_JAVASCRIPT) return '';

	$body = '';

	// 一度だけ実行
	static	$included = false;
	if (!$included) {
		$included = true;

		$timeout = (int)max((PLUGIN_SCREENSAVER_TIMEOUT_SEC * 1000), 1000);
		$color = PLUGIN_SCREENSAVER_COLOR ? "'" . PLUGIN_SCREENSAVER_COLOR . "'" : 'null';
		$body .= <<<EOT
<script>/*<!--*/
'use strict';
let		pluginScreenSaverTimer = null;
let		pluginScreenSaverEnabled = false;
const	pluginScreenSaverEle = document.createElement('dialog');
pluginScreenSaverEle.id = '__PluginScreenSaver__';
pluginScreenSaverEle.setAttribute('inert', true);
const	css = "dialog#__PluginScreenSaver__{position:fixed !important;top:0 !important;left:0 !important;width:100vw !important;height:100vh !important;max-width:100vw !important;max-height:100vh !important;border:none !important;border-radius:0 !important;box-sizing:border-box !important;margin:0 !important;padding:0 !important;overflow:hidden !important;opacity:1 !important;visibility:visible !important;z-index:2147483647 !important;user-select:none !important;pointer-events:none !important;display:none; background:transparent; backdrop-filter:blur(7px) !important;-webkit-backdrop-filter:blur(7px) !important;}dialog#__PluginScreenSaver__::backdrop{background:transparent}";
const	style = document.createElement('style');
style.appendChild(document.createTextNode(css)); 
document.getElementsByTagName('head')[0].appendChild(style); 

document.addEventListener('DOMContentLoaded', (event) => {
	if (!{$color}) {
		let	color = window.getComputedStyle(document.documentElement, null).getPropertyValue('background');
		if (!color) color = window.getComputedStyle(document.body, null).getPropertyValue('background');
		if (color) pluginScreenSaverEle.style.background = color;
	} else {
		if ({$color} != 'blur') pluginScreenSaverEle.style.background = {$color};
	}


	document.body.insertBefore(pluginScreenSaverEle, document.body.firstElementChild);

	window.addEventListener('pointermove',	e => {pluginScreenSaverReset(e)}, {passive: false});
	window.addEventListener('pointerdown',	e => {pluginScreenSaverReset(e)}, {passive: false});
	window.addEventListener('wheel',		e => {pluginScreenSaverReset(e)}, {passive: false});
	window.addEventListener('keydown',		e => {pluginScreenSaverReset(e)}, {passive: false});
	window.addEventListener('scroll',		e => {pluginScreenSaverReset(e)}, {passive: false});
	window.addEventListener('focus',		e => {pluginScreenSaverReset(e)}, {passive: false});

	pluginScreenSaverReset();
});

function pluginScreenSaverStart() {
	pluginScreenSaverTimer = null;
	if (!pluginScreenSaverEnabled) {
		pluginScreenSaverEle.style.display = 'block';
		pluginScreenSaverEle.showModal();
		pluginScreenSaverEnabled = true;
	}
}

function pluginScreenSaverReset(e = null) {
	if (pluginScreenSaverTimer) clearTimeout(pluginScreenSaverTimer);

	if (pluginScreenSaverEnabled) {
		pluginScreenSaverEle.style.display = 'none';
		pluginScreenSaverEle.close();
		pluginScreenSaverEnabled = false;
		if (e) e.preventDefault()
	}

	pluginScreenSaverTimer = setTimeout(pluginScreenSaverStart, {$timeout});
}
/*-->*/</script>
EOT;
	}

	return $body;
}
