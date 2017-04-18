<?php

namespace app\controllers;

use Yii;
use yii\base\Exception;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\forms\LoginForm;
use app\models\forms\SignupForm;
use app\services\UserService;
use app\services\InvalidPasswordException;
use app\services\UserNotFoundException;

class UserController extends Controller
{
    protected $userService;

    public function __construct($id, $module, UserService $userService, $config = [])
    {
        $this->userService = $userService;
        parent::__construct($id, $module, $config);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Авторизация
     * @return string|\yii\web\Response
     */
    public function actionSignin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $loginForm = new LoginForm();
        if ($loginForm->load(Yii::$app->request->post())) {
            try {
                $this->userService->login(
                    $loginForm->name,
                    $loginForm->password,
                    $loginForm->rememberMe);
                return $this->goBack();
            } catch (InvalidPasswordException $e) {
                $loginForm->setInvalidPasswordOrLoginError();
            } catch (UserNotFoundException $e) {
                $loginForm->setInvalidPasswordOrLoginError();
            } catch (Exception $e) {
                Yii::$app->getSession()->setFlash('error', 'Ошибка авторизации');
            }
        }
        return $this->render('signin.twig', [
            'model' => $loginForm,
        ]);
    }

    /**
     * Регистрация нового пользователя
     * @return string|\yii\web\Response
     */
    public function actionSignup()
    {
        $form = new SignupForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->userService->signup(
                    $form->name,
                    $form->password,
                    $form->balance);
                Yii::$app->getSession()->setFlash('success', 'Вы успешно зарегистрированы!');
                return $this->goHome();
            } catch (Exception $e) {
                Yii::$app->getSession()->setFlash('error', 'Ошибка регистрации');
            }
        }

        return $this->render('signup.twig', [
            'model' => $form
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
