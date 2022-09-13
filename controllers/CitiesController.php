<?php

declare(strict_types=1);

namespace app\controllers;

use app\cases\City\adapter\CityViewAdapter;
use app\cases\City\dto\CityViewDto;
use app\cases\City\form\CityForm;
use app\cases\City\service\CitySearch;
use app\cases\City\service\CityService;
use RuntimeException;
use Throwable;
use Yii;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\base\Module;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

final class CitiesController extends Controller
{
    private CitySearch $search;

    private CityService $service;

    public function __construct(
        string $id,
        Module $module,
        CitySearch $search,
        CityService $service,
        array $config = []
    ) {
        $this->search = $search;
        $this->service = $service;

        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => [
                            'create',
                            'update',
                            'index',
                            'view',
                            'delete'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['POST'],
                    'update' => ['PUT'],
                    'index' => ['GET'],
                    'view' => ['GET'],
                    'delete' => ['DELETE'],
                ],
            ],
        ];
    }

    /**
     * @param $action
     * @return bool
     * @throws BadRequestHttpException
     */
    public function beforeAction($action): bool
    {

        \Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    /**
     * @return CityViewDto
     * @throws BadRequestHttpException
     * @throws ServerErrorHttpException|InvalidConfigException
     */
    public function actionCreate(): CityViewDto
    {
        $form = new CityForm();
        $form->setAttributes(Yii::$app->request->post());
        if ($form->validate() === false) {
            /** todo: error to class */
            $listErrors = [];
            foreach ($form->getErrors() as $attribute => $errors) {
                $listErrors[] = "[$attribute] " . implode('; ', $errors);
            }
            throw new BadRequestHttpException(sprintf('Validation. [%s]', implode(';', $listErrors)));
        }

        try {
            return (new CityViewAdapter($this->service->create($form->toDto())))->toDto();
        } catch (RuntimeException $e) {
            throw new ServerErrorHttpException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function actionIndex(): array
    {
        try {
            return $this->search->search(Yii::$app->request->get());
        } catch (InvalidConfigException $e) {
            throw new ServerErrorHttpException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function actionUpdate(int $id): CityViewDto
    {
        $form = new CityForm();
        $form->setAttributes(Yii::$app->request->post());
        if ($form->validate() === false) {
            $listErrors = [];
            foreach ($form->getErrors() as $attribute => $errors) {
                $listErrors[] = "[$attribute] " . implode('; ', $errors);
            }
            throw new BadRequestHttpException(sprintf('Validation. [%s]', implode(';', $listErrors)));
        }
        
        try {
            return (new CityViewAdapter($this->service->update($id, $form->toDto())))->toDto();
        } catch (RuntimeException $e) {
            if ($e->getCode() === 404) {
                throw new NotFoundHttpException($e->getMessage());
            }
            throw new ServerErrorHttpException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param int $id
     * @return CityViewDto
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): CityViewDto
    {
        try {
            return (new CityViewAdapter($this->search->getById($id)))->toDto();
        } catch (RuntimeException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
    }

    /**
     * @param int $id
     * @return void
     * @throws ServerErrorHttpException
     */
    public function actionDelete(int $id): void
    {
        try {
            $this->service->delete($id);
        } catch (StaleObjectException $e) {
            throw new ServerErrorHttpException($e->getMessage(), $e->getCode(), $e);
        } catch (Throwable $e) {
            throw new ServerErrorHttpException($e->getMessage(), $e->getCode());
        }
    }
}
