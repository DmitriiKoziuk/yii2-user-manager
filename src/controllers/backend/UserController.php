<?php
namespace DmitriiKoziuk\yii2UserManager\controllers\backend;

use Yii;
use yii\base\Module;
use yii\web\Controller;
use yii\filters\VerbFilter;
use DmitriiKoziuk\yii2UserManager\data\UserSearchParams;
use DmitriiKoziuk\yii2UserManager\forms\UserProfileInputForm;
use DmitriiKoziuk\yii2UserManager\forms\UserInputForm;
use DmitriiKoziuk\yii2UserManager\services\UserActionService;
use DmitriiKoziuk\yii2UserManager\services\UserStatusService;
use DmitriiKoziuk\yii2UserManager\services\UserSearchService;

/**
 * UserController implements the CRUD actions for User model.
 */
final class UserController extends Controller
{
    /**
     * @var UserActionService
     */
    private $_userActionService;

    /**
     * @var UserStatusService
     */
    private $_userStatusService;

    /**
     * @var UserSearchService
     */
    private $_userSearchService;

    public function __construct(
        string $id,
        Module $module,
        UserActionService $userActionService,
        UserStatusService $userStatusService,
        UserSearchService $userSearchService,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->_userActionService = $userActionService;
        $this->_userStatusService = $userStatusService;
        $this->_userSearchService = $userSearchService;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchParams = new UserSearchParams();
        $searchParams->load(Yii::$app->request->queryParams);
        $usersDataProvider = $this->_userSearchService->searchBy($searchParams);

        return $this->render('index', [
            'usersDataProvider' => $usersDataProvider,
            'searchParams' => $searchParams,
            'userStatusService' => $this->_userStatusService,
        ]);
    }

    public function actionCreate()
    {
        $userInputForm = new UserInputForm(['scenario' => UserInputForm::SCENARIO_CREATE]);
        $userProfileInputForm = new UserProfileInputForm();

        if (
            Yii::$app->request->isPost &&
            $userInputForm->load(Yii::$app->request->post()) &&
            $userProfileInputForm->load(Yii::$app->request->post()) &&
            ($userInputForm->validate() && $userProfileInputForm->validate())
        ) {
            $this->_userActionService->createUser($userInputForm, $userProfileInputForm);
        }

        return $this->render('create', [
            'userInputForm' => $userInputForm,
            'userProfileInputForm' => $userProfileInputForm,
        ]);
    }

    public function actionUpdate(int $id)
    {
        $userInputForm = new UserInputForm(['scenario' => UserInputForm::SCENARIO_UPDATE]);
        $userProfileInputForm = new UserProfileInputForm();

        if (
            Yii::$app->request->isPost &&
            $userInputForm->load(Yii::$app->request->post()) &&
            $userProfileInputForm->load(Yii::$app->request->post()) &&
            ($userInputForm->validate() && $userProfileInputForm->validate())
        ) {
            $this->_userActionService->updateUser($id, $userInputForm, $userProfileInputForm);
            $userInputForm->clearPassword();
        } else {
            $userData = $this->_userActionService->getById($id);
            $userInputForm = $userData->getUserUpdateForm();
            $userProfileInputForm = $userData->getUserProfileUpdateForm();
        }

        return $this->render('update', [
            'id' => $id,
            'userInputForm' => $userInputForm,
            'userProfileInputForm' => $userProfileInputForm,
        ]);
    }

    public function actionDelete($id)
    {
        $this->_userActionService->deleteUserById($id);
        return $this->redirect(['index']);
    }
}
