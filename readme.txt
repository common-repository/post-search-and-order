=== post search and order ===
Contributors: yukimichi
Tags: search,filter,sort,order,customfield,customtaxnomy,period
Requires at least: 5.0
Tested up to: 5.7.2
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires PHP: 7.0
Donate link:https://ofuse.me/yk034

== Description ==

post search and orderは、様々な条件で検索や並べ替えをすることのできるプラグインです。

パフォーマンスに大きな影響を与えず、競合も少ないよう設計されており、テーマ本来のデザインそのままに検索結果が表示されます。

## 検索・並べ替え条件
通常検索、and・or・厳格検索、カテゴリー、タグ、カスタムタクソノミ、日付、指定期間、カスタムフィールド（数字、文字、数字範囲検索）、最大表示数変更、投稿者、コメント数、ランダム、タイトル順、更新日順  
また、表示する投稿タイプもオプションから変更可能です。

## 利用方法
利用方法はショートコードを設置することで可能です。
[template_psao]というショートコードを設置すると、基本的な検索フォームが表示されますので、最初のテストにおすすめです。

## template_psaoショートコード引数
- indexurl 表示される検索結果のurlを変更可能（通常はホームページ）
- class フォームタグに任意のクラスを追加
- submit 検索ボタンの文字を変更可能（デフォルトは検索）
- strict 厳格検索を有効可できる（日本語のひらがなとカタカナ、英語の小文字と大文字を区別して検索）
- tagtype タグ検索欄は通常はチェックボックスだが、カテゴリと同様のセレクトボックスに変更可能
- option and・or検索のチェックボックスを非表示にできる

サンプル：[template_psao indexurl="https://~" class="test" submit="送信" strict="on" tagtype="select" option="off"]

## ショートコードリスト
テンプレート以外の利用可能なショートコード  
※指定可能な引数詳細は近日中にホームページを用意して解説予定  

- [taxonomy_psao]
重要引数
	taxname　タクソノミ名を変更可能（初期値はcategory。タグはpost_tag）
- [tax_simplecheck_psao]
	同上
- [author_psao]
	role　指定権限ユーザーのみ表示（複数の場合は,区切りで入力　author,administrator）
	excluderole　指定権限名のユーザー除外
- [cnum_psao]
	key　　　並べ替えカスタムフィールド名指定
	label　　セレクトボックス初期ラベル
	keylabel　カスタムフィールド表示名
- [cnumrange_psao]
	key　　　検索カスタムフィールド名指定
- [daterange_psao]
- [checkpass_psao]
- [checkcom_psao]
- [order_psao]
- [orderby_psao]
- [period_psao]
- [postsnum_psao]
- [reset_psao]
- [search_psao]
	strict　templateと同じ
	option　templateと同じ
	inkey　カスタムフィールドを検索対象に含める事ができる
- [formwrap_psao]

## その他ショートコードの使い方
各ショートコードを、formwrapで囲む（template以外を使用する場合）。  
※indexurlは検索結果をホーム以外に表示したい場合以外は不要です。  
※常にすべてを含む必要はないので、カスタムフィールドなど必要のないショートコードは削除してください。  

サンプル  
[formwrap_psao indexurl="https://~"]  
[taxonomy_psao]  
[taxonomy_psao taxname="post_tag" type="check"]
[taxonomy_psao taxname="custom" type="check"]
[author_psao excluderole="administrator"]  
[cnum_psao key="price,distance" keylabel="値段,距離" label="物件"]  
[cnumrange_psao key="price"]  
[daterange_psao]  
[checkpass_psao]  
[checkcom_psao]  
[order_psao]  
[orderby_psao]  
[period_psao]  
[postsnum_psao]  
[search_psao]  
[/formwrap_psao]  
[reset_psao]  

※リセットを利用する場合はformwrapの外に入力

== Installation ==

1. From the WP admin panel, click “Plugins” -> “Add new”.
2. In the browser input box, type “post search and order”.
3. Select the “post search and order” plugin and click “Install”.
4. Activate the plugin.

OR…

1. Download the plugin from this page.
2. Save the .zip file to a location on your computer.
3. Open the WP admin panel, and click “Plugins” -> “Add new”.
4. Click “upload”.. then browse to the .zip file downloaded from this page.
5. Click “Install”.. and then “Activate plugin”.


== Frequently asked questions ==



== Screenshots ==



== Changelog ==

= 1.1 =
ショートコードの不具合修正
cssの微調整


== Upgrade notice ==

