<?php
return array(
    'basePath' => '..',
    'modules' => array(
        'admin' => array(
            'defaultController' => 'home',
        ),
        'proxy' => array(
            'defaultController' => 'home',
        ),
        'site' => array(
            'defaultController' => 'home',
        ),
        'wechat',
    ),
    'defaultController' => 'site',
    'import' => array(
        'ext.controllers.*',
        'application.libs.AmsProxy.AmsProxy',
        'application.models.*',
    ),
    'components' => array(
        'db' => array(
            'connectionString' => 'sqlite:../data/amsProxy.db',
        ),
        'viewRenderer' => array(
            'class' => 'CPradoViewRenderer',
            'fileExtension' => '.tpl',
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                ''       => 'site/home/index',
                'about'  => 'site/home/about',
                'faq'    => 'site/home/faq',
                'wechat' => 'site/wechat/index',

                'api'          => 'site/home/api',
                'api/login'    => 'site/api/login',
                'api/courses'  => 'proxy/api/courses',
                'api/scores'   => 'proxy/api/scores',
                'api/rankExam' => 'proxy/api/rankExam',
                'api/archives' => 'proxy/api/archives',
                'api/exam'     => 'proxy/api/exam',
            ),
        ),
    ),
);
