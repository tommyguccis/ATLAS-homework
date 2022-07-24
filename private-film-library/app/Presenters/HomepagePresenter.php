<?php

declare(strict_types=1);

namespace App\Presenters;
use Nette\Application\UI\Form;

use Nette;


final class HomepagePresenter extends Nette\Application\UI\Presenter
{
    public function startup()
    {
        parent::startup();

        if (!$this->getUser()->isLoggedIn() && $this->getAction() !== 'signIn')
        {
            $this->redirect('signIn');
            $this->terminate();
        }
    }

	public function actionSignIn()
	{
        $this->setLayout('signIn');
	}

    public function actionSignOut()
    {
        $this->getUser()->logout();
        $this->redirect('signIn');
    }

    protected function createComponentSignInForm() : Form
    {
        $form = new Form;

        $form->addText('username', 'Username:')
            ->setRequired();
        $form->addPassword('password', 'Password:')
            ->setRequired();
        $form->addSubmit('send', 'Sign In');

        $form->onSuccess[] = [$this, 'signInFormSuccess'];

        return $form;
    }

    public function signInFormSuccess(Form $form)
    {
        $values = $form->getValues();

        try {
            $this->getUser()->login($values->username, $values->password);
        } catch (Nette\Security\AuthenticationException $e) {
            $this->flashMessage('Username or password is not correct');
        }
        
        $this->redirect('Films:default');
    }
}
