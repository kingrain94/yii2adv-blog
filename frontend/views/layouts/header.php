<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
$model = \common\models\User::findOne(['id' => Yii::$app->user->getId()]);
$postCount = \common\models\Post::find()->where(['user_id' => $model['id']])->count();
$friendCount = \common\models\Relationship::find()->where(['user_id_1' => $model['id'], 'status' => 1])->count()
    + \common\models\Relationship::find()->where(['user_id_2' => $model['id'], 'status' => 1])->count();

$listNewRelNotify = \common\models\RelationshipNotification::find()->where(['receive_id' => Yii::$app->user->getId()])->orderBy('status')->limit(20)->asArray()->all();
$newRelNotifyCount = \common\models\RelationshipNotification::find()->where(['receive_id' => Yii::$app->user->getId(), 'status' => 0])->count();
$listNewMsgNotify = \common\models\Message::find()->where(['receiver_id' => Yii::$app->user->getId()])->orderBy('is_notified')->limit(20)->asArray()->all();
$newMsgNotifyCount = \common\models\Message::find()->where(['receiver_id' => Yii::$app->user->getId(), 'is_notified' => 0])->count();
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">APP</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <!-- Messages: style can be found in dropdown.less-->
                <li class="notify_msg dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <span class="notify_msg_count label label-success"><?php if ($newMsgNotifyCount > 0) {echo $newMsgNotifyCount;} ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">Bạn có <?= $newMsgNotifyCount ?> tin nhắn mới</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <?php
                                foreach ($listNewMsgNotify as $msgNotify) {
                                    $sender = \common\models\User::findOne(['id' => $msgNotify['sender_id']]);
                                    if ($sender['image'] != "") {
                                        $imageSource = Yii::$app->request->baseUrl ."/images/" .$sender['image'];
                                    } else {
                                        $imageSource = Yii::$app->request->baseUrl ."/images/avatar-default.jpg";
                                    }
                                    if ($msgNotify['is_notified'] == 0) {
                                        echo '<li>
                                    <a href="?r=message/read&id='.$msgNotify['id'].'">
                                        <div class="pull-left">
                                            <img src="'.$imageSource.'" class="img-circle"
                                                 alt="User Image"/>
                                        </div>
                                        <h4><b>
                                            '.$sender['username'].'</b>
                                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                        </h4>
                                        <b><p> đã gửi cho bạn một tin nhắn</p></b>
                                    </a>
                                </li>';
                                    } else {
                                        echo '<li>
                                    <a href="?r=message/read&id='.$msgNotify['id'].'">
                                        <div class="pull-left">
                                            <img src="'.$imageSource.'" class="img-circle"
                                                 alt="User Image"/>
                                        </div>
                                        <h4>
                                            '.$sender['username'].'
                                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                        </h4>
                                        <p> đã gửi cho bạn một tin nhắn</p>
                                    </a>
                                </li>';
                                    }
                                }
                                ?>
                            </ul>
                        </li>
                        <li class="footer"><a href="#">Xem tất cả</a></li>
                    </ul>
                </li>
                <?php
                $listPostNotify = \common\models\PostNotification::find()->where(['receiver_id' => Yii::$app->user->getId()])->limit(20)->orderBy('status')->asArray()->all();
                $newPostNotifyCount = \common\models\PostNotification::find()->where(['receiver_id' => Yii::$app->user->getId(), 'status' => 0])->count();

                ?>
                <li class="notify_post dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="notify_post_count label label-warning"><?php if ($newPostNotifyCount > 0) {echo $newPostNotifyCount;} ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">Bạn có <?= $newPostNotifyCount ?> thông báo bài viết</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul >
<!--                                <li>-->
<!--                                    <a href="#">-->
<!--                                        <i class="fa fa-users text-aqua"></i> 5 new members joined today-->
<!--                                    </a>-->
<!--                                </li>-->
                                <?php
                                foreach ($listPostNotify as $notify) {
                                    $user = \common\models\User::findOne(['id' => $notify['action_id']]);
                                    if ($user['fullname'] == '') {$name = $user['username'];} else {$name = $user['fullname'];}
                                    $isOwnPost = \common\models\Post::find()->where(['id' => $notify['post_id'], 'user_id' => Yii::$app->user->getId()])->count() > 0;
                                    if ($notify['type'] == 1) {
                                        if ($isOwnPost) {
                                            echo '<li>
                                                <a href="?r=post/detail&id='.$notify['post_id'].'">
                                                <p><i class="fa fa-comment"></i> <b>'.$name.'</b> đã bình luận bài viết của bạn</p>
                                                </a>
                                              </li>';
                                        } else {
                                            echo '<li>
                                                <a href="?r=post/detail&id='.$notify['post_id'].'">
                                                <i class="fa fa-comment"></i> <b>'.$name.'</b> đã bình luận 1 bài viết mà bạn theo dõi
                                                </a>
                                              </li>';
                                        }
                                    } else {
                                        echo '<li>
                                                <a href="?r=post/detail&id='.$notify['post_id'].'">
                                                <i class="fa fa-thumbs-up"></i> <b>'.$name.'</b> đã thích bài viết của bạn
                                                </a>
                                              </li>';
                                    }
                                }
                                ?>
                            </ul>
                        </li>
                        <li class="footer"><a href="#">Xem tất cả</a></li>
                    </ul>
                </li>
                <!-- Tasks: style can be found in dropdown.less -->
                <li class="notify_rel dropdown tasks-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-users"></i>
                        <span class="rel_notify_count label label-danger"><?php if ($newRelNotifyCount > 0) echo $newRelNotifyCount ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">Bạn có <?= $newRelNotifyCount ?> thông báo về mối quan hệ </li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <?php
                                foreach ($listNewRelNotify as $newRelNotify) {
                                    $userAction = \common\models\User::findOne(['id' => $newRelNotify['action_id']]);

                                    if ($newRelNotify['status'] == 0) {
                                        if ($newRelNotify['type'] == 1) {
                                            echo '<li>
                                                <a href="?r=user/show-friend-timeline&id='.$userAction['id'].'" style="color:red">
                                                    <i class="fa fa-user-plus text-aqua"></i>&nbsp;&nbsp; '.$userAction['username'].' đã yêu cầu tạo mối quan hệ
                                                </a>
                                            </li>';
                                        } elseif ($newRelNotify['type'] == 2) {
                                            echo '<li>
                                                <a href="?r=user/show-friend-timeline&id='.$userAction['id'].'" style="color:red">
                                                    <i class="fa fa-user text-aqua"></i>&nbsp;&nbsp; '.$userAction['username'].' đã chấp nhận yêu cầu của bạn
                                                </a>
                                            </li>';
                                        }
                                    } else {
                                        if ($newRelNotify['type'] == 1) {
                                            echo '<li>
                                                <a href="?r=user/show-friend-timeline&id='.$userAction['id'].'">
                                                    <i class="fa fa-user-plus text-aqua"></i>&nbsp;&nbsp; '.$userAction['username'].' đã yêu cầu tạo mối quan hệ
                                                </a>
                                            </li>';
                                        } elseif ($newRelNotify['type'] == 2) {
                                            echo '<li>
                                                <a href="?r=user/show-friend-timeline&id='.$userAction['id'].'">
                                                    <i class="fa fa-user text-aqua"></i>&nbsp;&nbsp; '.$userAction['username'].' đã chấp nhận yêu cầu của bạn
                                                </a>
                                            </li>';
                                        }
                                    }
                                }
                                ?>
                            </ul>
                        </li>
                        <li class="footer">
                            <a href="#">Xem tất cả</a>
                        </li>
                    </ul>
                </li>
                <!-- User Account: style can be found in dropdown.less -->
                <li class="notify_rel dropdown tasks-menu">
                    <?php
                    $listScheduleNotify = \common\models\ScheduleNotification::find()->where(['receiver_id' => Yii::$app->user->getId()])->asArray()->all();
                    ?>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-flag-o"></i>
                        <span class="rel_notify_count label label-danger"><?php if (sizeof($listScheduleNotify) > 0) echo sizeof($listScheduleNotify);  ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">Bạn có <?= sizeof($listScheduleNotify) ?> thông báo công việc </li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
<!--                                <li>-->
<!--                                    <a href="?r=schedule/show-received-schedule">-->
<!--                                        <h3>-->
<!--                                            Design some buttons-->
<!--                                        </h3>-->
<!--                                        <div class="progress xs">-->
<!--                                            <div class="progress-bar progress-bar-aqua" style="width: 100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </a>-->
<!--                                </li>-->

                                <?php
                                foreach ($listScheduleNotify as $scheduleNotify) {
                                    $userActionSchedule = \common\models\User::findOne(['id' => $scheduleNotify['action_id']]);
                                    if ($userActionSchedule['fullname'] != '') {
                                        $nameActionSchedule = $userActionSchedule['fullname'];
                                    } else {
                                        $nameActionSchedule = $userActionSchedule['username'];
                                    }
                                    echo '<li>
                                    <a href="?r=schedule/show-received-schedule&id='.$scheduleNotify['id'].'">
                                        <h3>
                                            '.$nameActionSchedule.'đã thêm bạn vào một công việc
                                        </h3>
                                        <div class="progress xs">
                                            <div class="progress-bar progress-bar-aqua" style="width: 100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </a>
                                </li>';
                                }
                                ?>
                            </ul>
                        </li>
                        <li class="footer">
                            <a href="#">Xem tất cả</a>
                        </li>
                    </ul>
                </li>

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?php
                        if ($model['image'] != "") {
                            echo Yii::$app->request->baseUrl ."/images/" .$model['image'];
                        } else {
                            echo Yii::$app->request->baseUrl ."/images/avatar-default.jpg";
                        }
                        ?>" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?= $model['fullname'] ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?php
                            if ($model['image'] != "") {
                                echo Yii::$app->request->baseUrl ."/images/" .$model['image'];
                            } else {
                                echo Yii::$app->request->baseUrl ."/images/avatar-default.jpg";
                            }
                            ?>" class="img-circle"
                                 alt="User Image"/>

                            <p>
                                <?= $model['fullname'] ." - ".$model['job'] ?>
                                <small>Member since Nov. 2012</small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="col-xs-6 text-center">
                                <a href="#"><b><?= $postCount ?></b><br>Bài viết</a>
                            </div>
                            <div class="col-xs-6 text-center">
                                <a href="#"><b><?= $friendCount ?></b><br>Mối quan hệ</a>
                            </div>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?= \yii\helpers\Url::to(['/user/edit-user-profile']) ?>" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    'Sign out',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>
