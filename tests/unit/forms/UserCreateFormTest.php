<?php
namespace DmitriiKoziuk\yii2UserManager\tests\unit\forms;

use Codeception\Test\Unit;
use DmitriiKoziuk\yii2UserManager\forms\UserCreateForm;

class UserCreateFormTest extends Unit
{
    public function testEmptyUserCreateForm()
    {
        $form = new UserCreateForm();
        $form->validate();
        expect('Error in username', $form->errors)->hasKey('username');
        expect('Error in password', $form->errors)->hasKey('password');
        expect('Error in email', $form->errors)->hasKey('email');
    }

    /**
     * @dataProvider dataProviderCorrectUserNames
     * @param string $username
     */
    public function testUserCreateFormAttributeUsernameValid(string $username): void
    {
        $form = new UserCreateForm();
        $form->username = $username;
        $form->validate();
        expect('Form errors', $form->errors)->hasntKey('username');
    }

    public function dataProviderCorrectUserNames()
    {
        return [
            'Single character' => ['D'],
            'Simply name' => ['Dmitrii'],
            'Simply name with first latter' => ['Alex A.'],
        ];
    }

    /**
     * @dataProvider dataProviderNotValidUserNames
     * @param string $username
     */
    public function testUserCreateFormAttributeUsernameNotValid(string $username): void
    {
        $form = new UserCreateForm();
        $form->username = $username;
        $form->validate();
        expect('Username has error', $form->errors)->hasKey('username');
    }

    public function dataProviderNotValidUserNames()
    {
        return [
            'More then 25 symbols' => ['Difjaskdjf jal;kfjalksjf;lkasj kafhsd fkjha ldfd fajsdfk;ljasf'],
            'Username has only spaces' => ['      '],
            'Username start from space' => [" Alex .T"],
            'Username start from \n' => ["\nAlex .T"],
            'Username start from \r' => ["\rAlex .T"],
            'Username start from \t' => ["\tAlex .T"],
            'Username end with space' => ["Alex .T "],
            'Username end with \n' => ["Alex .T\n"],
            'Username end with \t' => ["Alex .T\t"],
            'Username end with \r' => ["Alex .T\r"],
        ];
    }
}