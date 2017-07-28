# yii2-question

仿 segmentfault 的Yii2实现

[![Latest Stable Version](https://poser.pugx.org/yuncms/yii2-question/v/stable.png)](https://packagist.org/packages/yuncms/yii2-question)
[![Total Downloads](https://poser.pugx.org/yuncms/yii2-question/downloads.png)](https://packagist.org/packages/yuncms/yii2-question)
[![Reference Status](https://www.versioneye.com/php/yuncms:yii2-question/reference_badge.svg)](https://www.versioneye.com/php/yuncms:yii2-question/references)
[![Build Status](https://img.shields.io/travis/yiisoft/yii2-question.svg)](http://travis-ci.org/yuncms/yii2-question)
[![Dependency Status](https://www.versioneye.com/php/yuncms:yii2-question/dev-master/badge.png)](https://www.versioneye.com/php/yuncms:yii2-question/dev-master)
[![License](https://poser.pugx.org/yuncms/yii2-question/license.svg)](https://packagist.org/packages/yuncms/yii2-question)



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
        'class' => 'yuncms\question\frontend\Module',
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

## License

this is released under the MIT License. See the bundled [LICENSE.md](LICENSE.md)
for details.
