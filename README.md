# yii2-question

仿 segmentfault 的Yii2实现

安装
----

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require --prefer-dist yuncms/yii2-question
```

or add

```json
"yuncms/yii2-question": "*"
```

to the `require` section of your composer.json.

Add following lines to your main configuration file:
``php
'modules' => [
    'question' => [
        'class' => 'yuncms\question\Module',
    ],
],
```

After you downloaded and configured Yii2-vote, the last thing you need to do is updating your database schema by applying the migrations:
`
``php
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
			//命名空间
			'migrationNamespaces' => [
                'yuncms\question\migrations',
            ],
        ],
    ],
```

```bash
$ php yii migrate/up
```

使用
----

http::www.youname.com/index.php?r=question
