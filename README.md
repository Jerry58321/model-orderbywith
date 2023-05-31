## **安裝**

可以使用 Composer 來安裝 model-orderbywith。請在你的專案目錄中運行以下命令：

```
composer require jerry58321/model-orderbywith
```

## **說明**

這個程式庫是 Laravel 中 Eloquent Builder 的擴充方法，能夠透過 Eloquent Relationships 的方式實現關聯排序。

除此之外，它還具備以下優化特點：

1. 可以利用聚合函數進行排序，提供更靈活的排序需求處理方式。
2. 可以在遠程關聯中進行排序，使得在關聯模型之間的遠程關聯中進行排序成為可能。
3. 可以在關聯中加入其他條件，進一步細化關聯排序的範圍和結果。

透過這個程式庫，您可以更有效率且便捷地在使用 Eloquent Builder 進行關聯排序時實現以上功能。

## **實際使用方法**

[參考單元測試範例](https://github.com/Jerry58321/model-orderbywith/blob/master/tests/OrderByWithTest.php)