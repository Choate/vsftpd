<?php
/**
 * 邢帅教育
 * 本源代码由邢帅教育及其作者共同所有，未经版权持有者的事先书面授权，
 * 不得使用、复制、修改、合并、发布、分发和/或销售本源代码的副本。
 * @copyright Copyright (c) 2013 xsteach.com all rights reserved.
 */
/* @var \yii\bootstrap\ActiveForm $form */
/* @var \choate\vsftpd\models\User $model */

echo $form->field($model, 'username');
echo $form->field($model, 'password');
echo $form->field($model, 'name');
echo $form->field($model, 'localRoot')->hint('请设置该路径下的文件：' . $this->context->module->vsftpdLocalRoot);
echo $form->field($model, 'downloadEnable')->radioList($model->getDownloadStatusItem());
echo $form->field($model, 'writeEnable')->radioList($model->getWriteEnableStatusItem());
echo $form->field($model, 'cmdsDenied')->checkboxList($model->getFileOperationItem());
