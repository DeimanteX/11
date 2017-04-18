<?php

namespace app\controllers;

use Yii;
use app\services\NotEnoughMoneyException;
use app\services\TransferException;
use app\services\TransferService;
use app\services\UserNotFoundException;
use app\models\forms\TransferForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;

class TransferController extends \yii\web\Controller
{
    protected $transferService;

    public function __construct($id, $module, TransferService $transferService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->transferService = $transferService;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['send'],
                'rules' => [
                    [
                        'actions' => ['send'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'send' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Отправка перевода
     * @return array|\yii\web\Response
     */
    public function actionSend()
    {
        $form = new TransferForm();

        // ajax-валидация формы
        if (Yii::$app->request->isAjax && $form->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ActiveForm::validate($form);
        }

        // перевод средств
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->transferService->send(
                    Yii::$app->user->id,
                    $form->reciever_id,
                    $form->amount);
                Yii::$app->getSession()->setFlash('success', 'Перевод завершился успешно!');
                return $this->goHome();
            } catch (NotEnoughMoneyException $e) {
                Yii::$app->getSession()->setFlash('error', 'Недостаточно средств для перевода');
            } catch (TransferException $e) {
                Yii::$app->getSession()->setFlash('error', 'Ошибка при переводе');
            } catch (UserNotFoundException $e) {
                Yii::$app->getSession()->setFlash('error', 'Получатель или отправитель не найдены');
            }
        }
    }
}
