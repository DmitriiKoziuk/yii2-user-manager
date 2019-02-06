<?php
namespace DmitriiKoziuk\yii2UserManager\services;

use DmitriiKoziuk\yii2UserManager\entities\User;

final class UserStatusService
{
    private $_statuses = [
        User::STATUS_ACTIVE => 'Active',
        User::STATUS_DELETED => 'Deleted',
    ];

    public function getUserStatuses(): array
    {
        return $this->_statuses;
    }

    public function getUserStatusName(User $user): string
    {
        $statuses = $this->getUserStatuses();
        if (array_key_exists($user->status, $statuses)) {
            return $statuses[ $user->status ];
        }
        return 'Unknown';
    }
}