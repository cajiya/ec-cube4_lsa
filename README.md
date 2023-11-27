# EC-CUBE4.2用 在庫切れアラート

在庫が０になると、自動的に店舗管理者に警告メールを送信するプラグインです。

# インストール方法

```
cd app/Plugin;
git clone https://github.com/cajiya/ec-cube4_lsa.git;
mv ec-cube4_npsr LowStockAlert;
cd ../../;
php bin/console eccube:plugin:install --code="LowStockAlert"
```

