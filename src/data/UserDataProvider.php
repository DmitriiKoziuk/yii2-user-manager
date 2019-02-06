<?php
namespace DmitriiKoziuk\yii2UserManager\data;

use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\data\Pagination;

final class UserDataProvider implements DataProviderInterface
{
    /**
     * @var UserData[]
     */
    private $_users;

    /**
     * @var ActiveDataProvider
     */
    private $_activeDataProvider;

    /**
     * UsersData constructor.
     * @param UserData[] $users
     * @param ActiveDataProvider $activeDataProvider
     */
    public function __construct(array $users, ActiveDataProvider $activeDataProvider)
    {
        $this->_users = $users;
        $this->_activeDataProvider = $activeDataProvider;
    }

    public function prepare($forcePrepare = false)
    {
    }

    public function getCount()
    {
        return $this->_activeDataProvider->getCount();
    }

    public function getTotalCount()
    {
        return $this->_activeDataProvider->getTotalCount();
    }

    /**
     * @return UserData[]
     */
    public function getModels(): array
    {
        $models = [];
        foreach ($this->_users as $user) {
            $models[] = $user;
        }
        return $models;
    }

    public function getKeys(): array
    {
        $keys = [];
        foreach ($this->_users as $user) {
            $keys[] = $user->getId();
        }
        return $keys;
    }

    public function getSort(): bool
    {
        return false;
    }

    public function getPagination(): Pagination
    {
        return $this->_activeDataProvider->getPagination();
    }
}