<?php
namespace DmitriiKoziuk\yii2UserManager\repositories;

use yii\db\Expression;
use yii\db\ActiveQuery;
use yii\data\ActiveDataProvider;
use DmitriiKoziuk\yii2Base\repositories\AbstractActiveRecordRepository;
use DmitriiKoziuk\yii2UserManager\data\UserSearchParams;
use DmitriiKoziuk\yii2UserManager\entities\User;
use DmitriiKoziuk\yii2UserManager\entities\UserProfile;

final class UserRepository extends AbstractActiveRecordRepository
{
    /**
     * @var ActiveQuery
     */
    protected $_query;

    public function __construct(User $user)
    {
        $this->_query = $user::find();
    }

    /**
     * @param int $id
     * @return User|null
     */
    public function getById(int $id): ?User
    {
        /** @var User|null $user */
        $user = $this->_query->where(['id' => $id])->one();
        return $user;
    }
    /**
     * @param string $username
     * @return User|null
     */
    public function getByUsername(string $username): ?User
    {
        /** @var User|null $user */
        $user = $this->_query->where(['username' => $username])->one();
        return $user;
    }

    public function search(UserSearchParams $searchParams)
    {
        $query = User::find();

        if (! $searchParams->validate()) {
            // if you do not want to return any records when validation fails
            $query->where('0=1');
        }

        $query->andFilterWhere([
            'id' => $searchParams->id,
            'status' => $searchParams->status,
            'created_at' => $searchParams->createdAt,
            'updated_at' => $searchParams->updatedAt,
        ]);

        $query->andFilterWhere(['like', 'username', $searchParams->username])
            ->andFilterWhere(['like', 'email', $searchParams->email]);

        $sp = $searchParams;
        if (! empty($sp->firstName) || ! empty($sp->lastName) || ! empty($sp->middleName)) {
            $query->innerJoin(UserProfile::tableName(), [
                User::tableName() . '.id' => new Expression(UserProfile::tableName() . '.user_id'),
            ]);
            $query->andFilterWhere(['like', UserProfile::tableName() . '.first_name', $sp->firstName])
                ->andFilterWhere(['like', UserProfile::tableName() . '.last_name', $sp->lastName])
                ->andFilterWhere(['like', UserProfile::tableName() . '.middle_name', $sp->middleName]);
        }

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }
}