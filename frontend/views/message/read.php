<?php
/**
 * Created by PhpStorm.
 * User: Nguyen
 * Date: 11/12/2015
 * Time: 4:28 PM
 */
$this->title = 'Tin nhắn';
$this->params['breadcrumbs'][] = $this->title;
$newCount = \common\models\Message::find()->where(['receiver_id' => Yii::$app->user->getId(), 'status' => 0])->count();
?>

<div class="row">
    <div class="col-md-3">
        <a href="?r=message/compose" class="btn btn-primary btn-block margin-bottom">Soạn</a>
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Danh mục</h3>
                <div class="box-tools">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body no-padding">
                <ul class="nav nav-pills nav-stacked">
                    <li><a href="?r=message/show-inbox"><i class="fa fa-inbox"></i> Tin đã nhận <span class="label label-primary pull-right"><?= $newCount ?></span></a></li>
                    <li><a href="?r=message/show-outbox"><i class="fa fa-envelope-o"></i> Tin đã gửi</a></li>
                    <li><a href="?r=message/show-drafts"><i class="fa fa-file-text-o"></i> Tin nháp</a></li>
                </ul>
            </div><!-- /.box-body -->
        </div><!-- /. box -->
    </div><!-- /.col -->
    <div class="col-md-9">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Read Mail</h3>
                <div class="box-tools pull-right">
                    <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Previous"><i class="fa fa-chevron-left"></i></a>
                    <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Next"><i class="fa fa-chevron-right"></i></a>
                </div>
            </div><!-- /.box-header -->
            <div class="box-body no-padding">
                <div class="mailbox-read-info">
                    <h3><?= $model['subject'] ?></h3>
                    <h5>From: <?php
                        $user = \common\models\User::findOne(['id' => $model['sender_id']]);
                        if ($user['fullname'] != "") {
                            echo $user['username'].' ('.$user['fullname'].')';
                        } else {
                            echo $user['username'];
                        }
                        ?> <span class="mailbox-read-time pull-right"><?= $model['create_at'] ?></span></h5>
                    <h5>To: <?php
                        $user = \common\models\User::findOne(['id' => $model['receiver_id']]);
                        if ($user['fullname'] != "") {
                            echo $user['username'].' ('.$user['fullname'].')';
                        } else {
                            echo $user['username'];
                        }
                        ?></h5>
                </div><!-- /.mailbox-read-info -->
                <div class="mailbox-controls with-border text-center">
                    <div class="btn-group">
                        <button class="btn btn-default btn-sm" data-toggle="tooltip" title="Delete"><i class="fa fa-trash-o"></i></button>
                        <button class="btn btn-default btn-sm" data-toggle="tooltip" title="Reply"><i class="fa fa-reply"></i></button>
                        <button class="btn btn-default btn-sm" data-toggle="tooltip" title="Forward"><i class="fa fa-share"></i></button>
                    </div><!-- /.btn-group -->
                    <button class="btn btn-default btn-sm" data-toggle="tooltip" title="Print"><i class="fa fa-print"></i></button>
                </div><!-- /.mailbox-controls -->
                <div class="mailbox-read-message">
                    <p>
                        <?= $model['content'] ?>
                    </p>
                </div><!-- /.mailbox-read-message -->
            </div><!-- /.box-body -->
            <div class="box-footer">
                <div class="pull-right">
                    <button class="btn btn-default"><i class="fa fa-reply"></i> Reply</button>
                    <button class="btn btn-default"><i class="fa fa-share"></i> Forward</button>
                </div>
                <button class="btn btn-default"><i class="fa fa-trash-o"></i> Delete</button>
                <button class="btn btn-default"><i class="fa fa-print"></i> Print</button>
            </div><!-- /.box-footer -->
        </div><!-- /. box -->
    </div><!-- /.col -->
</div>
