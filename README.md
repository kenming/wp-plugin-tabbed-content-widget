# Tabbed contents widget

利用 Bootstrap nav-tab 標籤所設計位於 Siebar 的 Widget，可以切換顯示不同類型的內容。

實作效果可參考：[Kenmingの鮮思維::Blog](http://www.kenming.idv.tw/blog/)]

## Features

* Hide Widget Title option.
* Show Recent Comments option.
* Show Random Posts option.

## Notice

* 參考「[PHP針對中英文混合字符串長度判斷及截取方法](https://hk.saowen.com/a/8a0cf9ab00c484cfbf4fa2488e3ac6d79007fbff22e700529da923a14408219d)」：需實作該文 cut_str() function，將其更名為「custom_excerpt()」function，並儲存至所啟用 theme 根目錄的 functions.php。

## CHANGELOG

### 0.6
* initial.
* 實作 Show「recent-comments」與「random-posts」，預設列出10筆。