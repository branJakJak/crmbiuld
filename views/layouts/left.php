<?php


$menuItems = [
    ['label' => 'Home', 'icon' => 'dashboard', 'url' => ['/']],
    ['label' => 'New Record', 'icon' => 'file-code-o', 'url' => ['/record/create']],
//                    ['label' => 'Export Leads', 'icon' => 'file-excel-o', 'url' => ['/record/create']],
//                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
//                    [
//                        'label' => 'Same tools',
//                        'icon' => 'share',
//                        'url' => '#',
//                        'items' => [
//                            ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],],
//                            ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],],
//                            [
//                                'label' => 'Level One',
//                                'icon' => 'circle-o',
//                                'url' => '#',
//                                'items' => [
//                                    ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
//                                    [
//                                        'label' => 'Level Two',
//                                        'icon' => 'circle-o',
//                                        'url' => '#',
//                                        'items' => [
//                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
//                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
//                                        ],
//                                    ],
//                                ],
//                            ],
//                        ],
//                    ],
];
if (
    Yii::$app->user->can('Admin') ||
    Yii::$app->user->can('admin') ||
    Yii::$app->user->can('Manager') ||
    Yii::$app->user->can('Senior Manager')
) {
    $menuItems[] = [
        'label' => 'Not Submitted',
        'url' => ['/not-submitted'],
        // 'items' => [
        //     ['label' => 'All', 'url' => ['/cavity']],
        //     ['label' => 'Create', 'url' => ['/cavity/create']]
        // ],
    ];
}
if (Yii::$app->user->can('Consultant')) {
    $menuItems[] = ['label' => 'Open Cavity Questionaire', 'url' => ['/cavity/create']];


}


?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">

                <img src="/img/user-160x160.png" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= @Yii::$app->user->getIdentity()->username ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
                <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => $menuItems
            ]
        ) ?>

    </section>

</aside>
