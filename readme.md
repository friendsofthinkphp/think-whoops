# think-whoops
让Whoops接管ThinkPHP6异常, Whoops提供stackbased错误捕获及超美观的错误

## 安装
```php
$ composer require xiaodi/think-whoops
```

## 开启/关闭接管
开启 `APP_DEBUG` 才正常接管， 关闭默认转交ThinkPHP处理
`config/whoops.php`
```php
 <?php
 
 return [
  'enable' = true
 ];
```

## 打开编辑器
帮助您从异常堆栈跟踪中打开代码编辑器  

支持编辑器 `sublime,textmate,emacs,macvim,phpstorm,idea,vscode,atom,espresso  `

`config/whoops.php`
```php
 <?php
 
 return [
   'editor' = 'vscode'
 ];
```

## 效果
![img](https://www.xiaodim.com/images/whoops.png)
